<template>
    <div>
        <div>
            <app-table-panel
                :title="'Comentários'"
                titleCollapsed="Comentários"
                :per-page="perPage"
                :filter-text="filterText"
                @input-filter-text="filterText = $event.target.value"
                @set-per-page="perPage = $event"
                :collapsedLabel="selected.name"
                :is-selected="selected.id !== null"
                :subTitle="entries.selected.object + ' - ' + entries.selected.value_formatted"
                v-if="environment.user != null"
                :isLoading="tableLoading"
            >

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-11">
                                <div class="row">
                                    <div class="col-4">
                                        <p class="font-weight-bold mb-3">
                                            Keila Abrantes
                                        </p>

                                    </div>
                                    <div class="col-4">
                                        <p class="font-weight-bold mb-3">
                                            16/11/2021
                                        </p>
                                    </div>

                                    <div class="col-4">
                                        <p class="font-weight-bold mb-3">
                                            # 2478
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        R$ 55,00 - Nota fiscal n.º 00161650 emitida em 12/11 e lançada em 19/08/2021. ( Verificar duplicidade de lançamento)
                                    </div>
                                </div>

                            </div>
                            <div class="col-1">
                                <button title="Editar comentário" dusk="editComment" class="btn btn-micro btn-primary"><i class="fa fa-edit"></i></button> <button title="Deletar Comentário" class="button btn btn-micro btn-danger"><span class="fa fa-trash"> </span></button>
                                <button title="Logs" type="button" class="btn justify-content-center btn btn-micro btn-primary button btn-secondary">
                                    <i class="fas fa-clipboard-list"></i> <!---->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <template slot="buttons">
                    <button
                        v-if="can('entry-comments:store')"
                        :disabled="!can('entry-comments:store')"
                        class="btn btn-primary btn-sm pull-right"
                        @click="createComment()"
                        title="Novo Comentário"
                        dusk="newEntryComment"
                        id="commentButton"
                    >
                        <i class="fa fa-plus"></i>
                    </button>
                </template>

                <app-table
                    :pagination="pagination"
                    @goto-page="gotoPage($event)"
                    :columns="getTableColumns()"
                >
                    <tr
                        @click="selectEntryComment(comment)"
                        v-for="comment in entryComments.data.rows"
                        :class="{
                            'cursor-pointer': true,
                            'bg-primary-lighter text-white': isCurrent(comment, selected),
                        }"
                    >
                        <td v-if="can('tables:view-ids')" class="align-middle">{{ comment.id }}</td>

                        <td class="align-middle">
                            {{ comment.text }}
                        </td>

                        <td class="align-middle text-left">
                            {{ comment.user.name }}
                        </td>

                        <td class="align-middle text-left">
                            {{ comment.formatted_created_at }}
                        </td>

                        <td class="align-middle text-center">
                            <button
                                :disabled="
                                    !can('entry-comments:update') ||
                                    !can(
                                        'entry-comments:update:' +
                                            (comment.creator_is_congressman
                                                ? 'congressman'
                                                : 'not-congressman'),
                                    )
                                "
                                class="btn btn-micro btn-primary"
                                @click="editComment(comment)"
                                title="Editar comentário"
                                dusk="editComment"
                            >
                                <i class="fa fa-edit"></i>
                            </button>

                            <app-action-button
                                :disabled="
                                    !can('entry-comments:delete') ||
                                    !can(
                                        'entry-comments:delete:' +
                                            (comment.creator_is_congressman
                                                ? 'congressman'
                                                : 'not-congressman'),
                                    )
                                "
                                classes="btn btn-micro btn-danger"
                                title="Deletar Comentário"
                                :model="comment"
                                swal-title="Deseja realmente DELETAR este comentários?"
                                label=""
                                icon="fa fa-trash"
                                store="entryComments"
                                method="delete"
                                :spinner-config="{ size: '0.02em' }"
                                :swal-message="{ r200: 'Deletado com sucesso' }"
                            >
                            </app-action-button>

                            <app-audits-button
                                model="entryComments"
                                :row="comment"
                            ></app-audits-button>
                        </td>
                    </tr>
                </app-table>

                <app-comment-form :show.sync="showModal"></app-comment-form>
            </app-table-panel>
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import crud from '../../views/mixins/crud'
import entries from '../../views/mixins/entries'
import permissions from '../../views/mixins/permissions'
import entryComments from '../../views/mixins/entryComments'
import congressmanBudgets from '../../views/mixins/congressmanBudgets'

const service = {
    name: 'entryComments',

    uri: 'congressmen/{congressmen.selected.id}/budgets/{congressmanBudgets.selected.id}/entries/{entries.selected.id}/comments',
}

export default {
    mixins: [crud, entryComments, permissions, entries, congressmanBudgets],

    data() {
        return {
            service: service,

            showModal: false,
        }
    },

    computed: {
        ...mapGetters({
            congressmanBudgetsClosedAt: 'congressmanBudgets/selectedClosedAt',
        }),
        ...mapState(service.name, ['tableLoading']),
    },

    methods: {
        ...mapActions(service.name, ['clearForm', 'clearErrors', 'selectEntryComment']),

        getTableColumns() {
            let columns = []

            if (can('tables:view-ids')) {
                columns.push({
                    type: 'label',
                    title: '#',
                    trClass: 'text-center',
                })
            }
            columns.push('Comentário')
            columns.push('Autor')
            columns.push('Criado em')
            columns.push('')

            return columns
        },

        trash(comment) {
            this.$swal({
                title: 'Deseja realmente DELETAR este comentário?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryComments/delete', comment)
                }
            })
        },

        editComment(comment) {
            this.showModal = true
        },

        createComment() {
            if (filled(this.form.id)) {
                this.clearForm()
            }
            this.showModal = true
        },
    },
}
</script>
