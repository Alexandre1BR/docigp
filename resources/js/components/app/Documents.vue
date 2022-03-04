<template>
    <div>
    
    <app-table-panel 
        :title="'Documentos' + (tableLoading ? '' : '  (' + pagination.total + ')')"
        titleCollapsed="Documento"
        :per-page="perPage"
        :filter-text="filterText"
        @input-filter-text="filterText = $event.target.value"
        @set-per-page="perPage = $event"
        :collapsedLabel="selected.name"
        :is-selected="selected.id !== null"
        :subTitle="entries.selected.object + ' - ' + entries.selected.value_formatted"
    >
        <template slot="buttons">
            <button
                v-if="can('entry-documents:buttons') || can('entry-documents:store')"
                :disabled="!can('entry-documents:store') || congressmanBudgetsClosedAt"
                class="btn btn-primary btn-sm pull-right"
                @click="createDocument()"
                title="Novo documento"
                dusk="newEntryDocument"
            >
                <i class="fa fa-plus"></i>
            </button>
        </template>

        <div v-if="tableLoading" class="p-5">
            <clip-loader 
                margin='2px'
                v-if="tableLoading"
                color="#0a008a"
                :size="'4em'"
                class="d-flex justify-content-center pt-5"
            >
            </clip-loader>
        </div>

        <app-table v-if="!tableLoading"
            :pagination="pagination"
            @goto-page="gotoPage($event)"
            :columns="getTableColumns()"
        >
            <tr
                @click="selectEntryDocument(document)"
                v-for="document in entryDocuments.data.rows"
                :class="{
                    'cursor-pointer': true,
                    'bg-primary-lighter text-white': isCurrent(document, selected),
                }"
            >
                <td v-if="can('tables:view-ids')" class="align-middle">{{ document.id }}</td>

                <td class="align-middle">
                    {{ document.attached_file.original_name }}
                </td>

                <td v-if="can('entry-documents:show')" class="align-middle text-center">
                    <app-active-badge
                        :value="document.verified_at"
                        :labels="['sim', 'não']"
                    ></app-active-badge>
                </td>

                <td v-if="can('entry-documents:show')" class="align-middle text-center">
                    <app-active-badge
                        :value="document.analysed_at"
                        :labels="['sim', 'não']"
                    ></app-active-badge>
                </td>

                <td v-if="can('entry-documents:show')" class="align-middle text-center">
                    <app-active-badge
                        :value="document.published_at"
                        :labels="['documento público', 'documento privado']"
                    ></app-active-badge>
                </td>

                <td class="align-middle text-right">

                    <div>
                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.verify.visible"
                        :disabled="getEntryDocumentState(document).buttons.verify.disabled"
                        classes="btn btn-sm btn-micro btn-primary"
                        :title="getEntryDocumentState(document).buttons.verify.title"
                        :model="document"
                        swal-title="Verificar este documento?"
                        label="verificar"
                        icon="fa fa-check"
                        store="entryDocuments"
                        method="verify"
                        :spinner-config="{ color: 'white' }"
                            
                            >
                    </app-action-button>
                    
                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.unverify.visible"
                        :disabled="getEntryDocumentState(document).buttons.unverify.disabled"
                        classes="btn btn-sm btn-micro btn-warning"
                        :title="getEntryDocumentState(document).buttons.unverify.title"
                        :model="document"
                        swal-title="Retirar verificação deste documento?"
                        label="verificado"
                        icon="fa fa-ban"
                        store="entryDocuments"
                        method="unverify"
                        :spinner-config="{ color: 'black' }"
                            
                            >
                    </app-action-button>
                    
                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.analyse.visible"
                        :disabled="getEntryDocumentState(document).buttons.analyse.disabled"
                        classes="btn btn-sm btn-micro btn-success"
                        :title="getEntryDocumentState(document).buttons.analyse.title"
                        :model="document"
                        swal-title="Deseja analisar este documento?"
                        label="analisar"
                        icon="fa fa-ban"
                        store="entryDocuments"
                        method="analyse"
                        :spinner-config="{ color: 'white' }"
                            
                            >
                    </app-action-button>
                    
                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.unanalyse.visible"
                        :disabled="getEntryDocumentState(document).buttons.unanalyse.disabled"
                        classes="btn btn-sm btn-micro btn-danger"
                        :title="getEntryDocumentState(document).buttons.unanalyse.title"
                        :model="document"
                        swal-title="Retirar a análise deste documento?"
                        label="analisado"
                        icon="fa fa-ban"
                        store="entryDocuments"
                        method="unanalyse"
                        :spinner-config="{ color: 'white' }"
                            
                            >
                    </app-action-button>

                      <app-action-button
                        v-if="getEntryDocumentState(document).buttons.publish.visible"
                        :disabled="getEntryDocumentState(document).buttons.publish.disabled"
                        classes="btn btn-sm btn-micro btn-danger"
                        :title="getEntryDocumentState(document).buttons.publish.title"
                        :model="document"
                        swal-title="Deseja tornar este documento público?"
                        label="tornar público"
                        icon="fa fa-check"
                        store="entryDocuments"
                        method="publish"
                        :spinner-config="{ color: 'white' }"
                            
                            >
                    </app-action-button>    

                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.unpublish.visible"
                        :disabled="getEntryDocumentState(document).buttons.unpublish.disabled"
                        classes="btn btn-sm btn-micro btn-success"
                        :title="getEntryDocumentState(document).buttons.unpublish.title"
                        :model="document"
                        swal-title="Deseja despublicar este documento?"
                        label="tornar privado"
                        icon="fa fa-ban"
                        store="entryDocuments"
                        method="unpublish"
                        :spinner-config="{ color: 'white' }"
                            
                            >
                    </app-action-button>


                    <a
                        :href="document.url"
                        target="_blank"
                        title="Visualizar documento"
                        class="btn btn-sm btn-micro btn-warning cursor-pointer"
                    >
                        <i class="fa fa-eye"></i>
                    </a>

                    <app-action-button
                        v-if="getEntryDocumentState(document).buttons.delete.visible"
                        :disabled="getEntryDocumentState(document).buttons.delete.disabled"
                        classes="btn btn-sm btn-micro btn-danger"
                        :title="getEntryDocumentState(document).buttons.delete.title"
                        :model="document"
                        swal-title="Deseja realmente DELETAR este documento?"
                        label=""
                        icon="fa fa-trash"
                        store="entryDocuments"
                        method="delete"
                        :spinner-config="{ size: '0.02em' }"
                        :swal-message="{ r200: 'Deletado com sucesso' }"
                            
                            >
                    </app-action-button>

                    

                    
                    <app-audits-button model="entryDocuments" :row="document"></app-audits-button>
                </div>
                </td>
            </tr>
        </app-table>

        <app-document-form :show.sync="showModal"></app-document-form>
    </app-table-panel>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import crud from '../../views/mixins/crud'
import entries from '../../views/mixins/entries'
import permissions from '../../views/mixins/permissions'
import entryDocuments from '../../views/mixins/entryDocuments'
import congressmanBudgets from '../../views/mixins/congressmanBudgets'

const service = {
    name: 'entryDocuments',

    uri: 'congressmen/{congressmen.selected.id}/budgets/{congressmanBudgets.selected.id}/entries/{entries.selected.id}/documents',
}

export default {
    mixins: [crud, entryDocuments, permissions, entries, congressmanBudgets],

    data() {
        return {
            service: service,

            showModal: false,
        }
    },

    computed: {
        ...mapGetters({
            congressmanBudgetsClosedAt: 'congressmanBudgets/selectedClosedAt',
            getEntryDocumentState: 'entryDocuments/getEntryDocumentState',
        }),
        ...mapState(service.name, ['tableLoading'])

        // return this.$store.dispatch('congressmanBudgets/changePercentage', {
        //     congressmanBudget: congressmanBudget,
        //     percentage: value
        // });
    },

    methods: {
        ...mapActions(service.name, ['clearForm', 'clearErrors', 'selectEntryDocument']),

        getTableColumns() {
            let columns = []

            if (can('tables:view-ids')) {
                columns.push({
                    type: 'label',
                    title: '#',
                    trClass: 'text-center',
                })
            }

            columns.push('Nome do arquivo')

            if (can('entry-documents:show')) {
                columns.push({
                    type: 'label',
                    title: 'Verificado',
                    trClass: 'text-center',
                })

                columns.push({
                    type: 'label',
                    title: 'Analisado',
                    trClass: 'text-center',
                })

                columns.push({
                    type: 'label',
                    title: 'Publicidade',
                    trClass: 'text-center',
                })
            }

            columns.push('')

            return columns
        },

        trash(document) {
            this.$swal({
                title: 'Deseja realmente DELETAR este documento?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/delete', document)
                }
            })
        },

        verify(entry) {
            this.$swal({
                title: 'Confirma a marcação deste lançamento como "VERIFICADO"?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/verify', entry)
                }
            })
        },

        unverify(entry) {
            this.$swal({
                title: 'O status de "VERIFICADO" será removido deste lançamento, confirma?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/unverify', entry)
                }
            })
        },

        analyse(document) {
            this.$swal({
                title: 'Este documento foi ANALISADO?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/analyse', document)
                }
            })
        },

        unanalyse(document) {
            this.$swal({
                title: 'Deseja remover o status "ANALISADO" deste lançamento?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/unanalyse', document)
                }
            })
        },

        publish(document) {
            this.$swal({
                title: 'Confirma a PUBLICAÇÃO deste documento?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/publish', document)
                }
            })
        },

        unpublish(document) {
            this.$swal({
                title: 'Confirma a DESPUBLICAÇÃO deste documento?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('entryDocuments/unpublish', document)
                }
            })
        },

        createDocument() {
            if (filled(this.form.id)) {
                this.clearForm()
            }

            this.showModal = true
        },
    },
}
</script>
