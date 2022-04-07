<template>

    <div>
    <div v-if="entries.selected.id">
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

                <td class="align-middle d-inline-block text-truncate" style="max-width: 300px;">
                    {{ comment.text }}
                </td>

                <td class="align-middle text-left">
                    {{ comment.user.name }}
                </td>

                <td class="align-middle text-left">
                    {{ comment.formatted_created_at }}
                </td>

                <td class="align-middle text-right">
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
                        class="btn btn-sm  btn-primary"
                        @click="editComment(comment)"
                        title="Editar comentário"
                        dusk="editComment"
                    >
                        <i class="fa fa-edit"></i>
                    </button>


                    <app-action-button
                        :disabled="!can('entry-comments:delete') ||
                            !can(
                                'entry-comments:delete:' +
                                    (comment.creator_is_congressman
                                        ? 'congressman'
                                        : 'not-congressman'),
                            )"
                        classes="btn btn-sm  btn-danger"
                        title="Deletar Comentário"
                        :model="comment"
                        swal-title="Deseja realmente DELETAR este comentários?"
                        label=""
                        icon="fa fa-trash"
                        store="entryComments"
                        method="delete"
                        :spinner-config="{ size: '0.02em' }"
                        :swal-message="{ r200: 'Deletado com sucesso' }"
                            :model-id="comment.id"
                            >
                    </app-action-button>

                    <app-audits-button model="entryComments" :row="comment"></app-audits-button>
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
        ...mapState(service.name, ['tableLoading', 'showComponent'])
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

