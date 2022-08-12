<template>
    <div v-if="entries.selected.id">
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
            :isLoading="tableLoading"
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

            <div class="card mb-3">
                <div class="card-body">

                    <div class="row row-visible-onlyadmin">
                        <div class="id-onlyadmin offset-4 col-4 offset-lg-5 col-lg-2 text-center">
                            # 17017
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-4">
<!--                            <p class="font-weight-bold mb-3">
                                Documento:
                            </p>-->
                            papelex 302.40 .pdf
                        </div>
                        <div class="col-4">
<!--                            <p class="font-weight-bold mb-3">
                                Status:
                            </p>-->
                            <div class="text-uppercase" style="font-size: 9.6px; font-weight: 700;">
                                <div class="mb-3">
                                    Verificado:
                                    <span class="badge-success rounded-top p-1">sim</span>
                                </div>
                                <div class="mb-3">
                                    Analisado:
                                    <span class="badge-success rounded-0 p-1">sim</span>
                                </div>
                                <div class="mb-3">
                                    Publicidade:
                                    <span class="badge-danger rounded-bottom p-1">privado</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
<!--                            <p class="font-weight-bold mb-3">
                                Açôes:
                            </p>-->
                            <div class="form-row justify-content-center">
                                <button disabled="disabled" title="O orçamento mensal está fechado" class="button col-xl-3 col-lg-5 btn btn-micro btn-warning"><span class="fa fa-ban"> verificado</span></button> <button title="Cancelar marcação de 'analisado'" class="button col-xl-3 col-lg-5 btn btn-micro btn-danger"><span class="fa fa-ban"> analisado</span></button> <button title="Remover do Portal da Transparência" class="button col-xl-3 col-lg-5 btn btn-micro btn-danger"><span class="fa fa-ban"> despublicar</span></button>
                                <div class="col-md-12 text-center"><button disabled="disabled" title="O orçamento mensal está fechado" class="button btn btn-micro  btn-danger smallButton"><span class="fa fa-trash"></span></button> <button title="O orçamento mensal está fechado" class="btn btn-micro btn-primary button smallButton"><i class="fa fa-edit"></i></button> <button title="Logs" type="button" class="btn justify-content-center btn btn-micro btn-primary button smallButton btn-secondary"><i class="fas fa-clipboard-list"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <app-table
                :pagination="pagination"
                @goto-page="gotoPage($event)"
                :columns="getTableColumns()"
                statusSize="2"
                actionsSize="5"
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
                        <app-status-badge
                            class="text-uppercase"
                            :rows="[
                                {
                                    value: document.verified_at,
                                    title: 'Verificado: ',
                                    labels: ['sim', 'não'],
                                },
                                {
                                    value: document.analysed_at,
                                    title: 'Analisado: ',
                                    labels: ['sim', 'não'],
                                },
                                {
                                    value: document.published_at,
                                    title: 'Publicidade: ',
                                    labels: ['público', 'privado'],
                                },
                            ]"
                        ></app-status-badge>
                    </td>

                    <td class="align-middle">
                        <div class="text-center">
                            <app-action-button
                                v-if="getEntryDocumentState(document).buttons.verify.visible"
                                :disabled="getEntryDocumentState(document).buttons.verify.disabled"
                                classes="btn btn-micro  btn-primary col-xl-3 col-sm-6"
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
                                :disabled="
                                    getEntryDocumentState(document).buttons.unverify.disabled
                                "
                                classes="btn btn-micro  btn-warning col-xl-3 col-sm-6"
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
                                classes="btn btn-micro  btn-success col-xl-3 col-sm-6"
                                :title="getEntryDocumentState(document).buttons.analyse.title"
                                :model="document"
                                swal-title="Deseja analisar este documento?"
                                label="analisar"
                                icon="fa fa-check"
                                store="entryDocuments"
                                method="analyse"
                                :spinner-config="{ color: 'white' }"
                            >
                            </app-action-button>

                            <app-action-button
                                v-if="getEntryDocumentState(document).buttons.unanalyse.visible"
                                :disabled="
                                    getEntryDocumentState(document).buttons.unanalyse.disabled
                                "
                                classes="btn btn-micro  btn-danger col-xl-3 col-sm-6"
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
                                classes="btn btn-micro  btn-danger col-xl-3 col-sm-6"
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
                                :disabled="
                                    getEntryDocumentState(document).buttons.unpublish.disabled
                                "
                                classes="btn btn-micro  btn-success col-xl-3 col-sm-6"
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
                            <div class="col-12 text-center">
                                <app-action-button
                                    v-if="getEntryDocumentState(document).buttons.delete.visible"
                                    :disabled="
                                        getEntryDocumentState(document).buttons.delete.disabled
                                    "
                                    classes="btn btn-micro btn-danger"
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

                                <a
                                    :href="document.url"
                                    target="_blank"
                                    title="Visualizar documento"
                                    class="btn btn-micro btn-warning cursor-pointer button"
                                >
                                    <i class="fa fa-eye"></i>
                                </a>
                                <app-audits-button
                                    model="entryDocuments"
                                    :row="document"
                                ></app-audits-button>
                            </div>
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
        ...mapState(service.name, ['tableLoading']),

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
                /* columns.push({
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
                }) */
                columns.push({
                    type: 'label',
                    title: 'Status',
                    trClass: 'text-center',
                })
                columns.push({
                    type: 'label',
                    title: 'Ações',
                    trClass: 'text-center',
                })
            }

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
