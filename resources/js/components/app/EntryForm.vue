<template>
    <div>
        <b-modal v-model="showModal" :title="formTitle" @shown="onShow()">
            <b-form>
                <div class="row">
                    <div class="col-6">
                        <app-input
                            name="date"
                            label="Data"
                            v-model="form.fields.date"
                            :readonly="!can('entries:update') || isRefund()"
                            :required="true"
                            :form="form"
                            v-mask="'##/##/####'"
                            :focus="true"
                            dusk="date"
                        ></app-input>
                    </div>

                    <div class="col-6" >
                        <app-input
                            name="value"
                            type="money"
                            label="Valor pago"

                            v-model="form.fields.value_abs"
                            :readonly="!can('entries:update')"
                            :required="true"
                            :form="form"
                        ></app-input>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <app-select
                            name="entry_type_id"
                            label="Meio"
                            v-model="form.fields.entry_type_id"
                            :readonly="!can('entries:update') || isRefund()"
                            :required="true"
                            :form="form"
                            :options="getEntryTypeRows()"
                        ></app-select>
                    </div>

                    <div class="col-4">
                        <app-input
                            name="document_number"
                            label="Documento"
                            v-model="form.fields.document_number"
                            :readonly="!can('entries:update')"
                            :form="form"
                        ></app-input>
                    </div>
                </div>

                <app-input
                    name="object"
                    label="Objeto"
                    v-model="form.fields.object"
                    :readonly="!can('entries:update') || isRefund()"
                    :required="true"
                    :form="form"
                ></app-input>

                <app-input
                    name="provider_cpf_cnpj"
                    label="CPF / CNPJ"
                    v-model="form.fields.provider_cpf_cnpj"
                    :readonly="!can('entries:update') || isRefund()"
                    :required="true"
                    :form="form"
                    v-mask="['###.###.###-##', '##.###.###/####-##']"
                    @input="onCpfCnpjChange($event)"
                    :bottomMessage="cpfCnpjMessage"
                    :bottomMessageType="cpfCnpj.is_valid ? '' : 'error'"
                ></app-input>

                <app-input
                    v-if="!isCreating"
                    name="provider_name"
                    label="Nome da pessoa ou razão social"
                    v-model="form.fields.provider_name"
                    :form="form"
                    :required="true"
                    :readonly="!newCpfCnpj || !can('entries:update') || isRefund()"
                ></app-input>

                <app-input
                    v-if="isCreating"
                    name="to"
                    label="Nome da pessoa ou razão social"
                    v-model="form.fields.to"
                    :required="true"
                    :form="form"
                    :readonly="!newCpfCnpj || !can('entries:update') || isRefund()"
                ></app-input>

                <app-select
                    name="cost_center_id"
                    label="Centro de custo"
                    v-model="form.fields.cost_center_id"
                    :readonly="!can('entries:update') || isRefund()"
                    :required="true"
                    :form="form"
                    :options="getCostCenterRows()"
                ></app-select>
            </b-form>

            <template slot="modal-footer">
                <button
                    @click="saveAndClose()"
                    class="btn btn-success btn-sm"
                    dusk="record"
                    v-if="can('entries:update')"
                >
                    <i v-if="busy" class="fas fa-compact-disc fa-spin"></i>

                    Gravar
                </button>

                <button dusk='cancel' @click="close()" class="btn btn-outline-danger btn-sm">Cancelar</button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
import crud from '../../views/mixins/crud'
import entries from '../../views/mixins/entries'
import permissions from '../../views/mixins/permissions'

const service = {
    name: 'entries',

    uri: 'congressmen/{congressmen.selected.id}/budgets/{congressmanBudgets.selected.id}/entries',
}

const __cpfCnpj = {
    person: null,
    is_valid: false,
    type: false,
    formatted: false,
    unformatted: '',
}

export default {
    mixins: [crud, entries, permissions],

    props: ['show', 'refund','dusk'],

    data() {
        return {
            busy: false,

            costCenters: Store.state.costCenters,

            entryTypes: Store.state.entryTypes,

            service: service,

            checkCpfCnpj: _.debounce((cpfCnpj) => {
                this.checkCpfCnpjChecker(cpfCnpj)
            }, 650),

            cpfCnpj: clone(__cpfCnpj),
        }
    },

    methods: {
        ...mapActions(service.name, ['clearForm', 'clearErrors']),

        close() {
            this.showModal = false
        },

        saveAndClose() {
            this.busy = true

            this.saveModel(() => {
                this.closeModal(true)
            })
        },

        onBoot() {
            this.$store.dispatch('costCenters/load')
            this.$store.dispatch('entryTypes/load')
        },

        getCostCenterRows() {
            return this.costCenters.data && this.costCenters.data.rows
                ? this.costCenters.data.rows
                : []
        },

        getEntryTypeRows() {
            return this.entryTypes.data && this.entryTypes.data.rows
                ? this.entryTypes.data.rows
                : []
        },

        onCpfCnpjChange(cpfCnpj) {
            this.checkCpfCnpj(cpfCnpj)
        },

        checkCpfCnpjChecker(cpfCnpj) {
            post('/api/v1/cpf-cnpj/check', {
                cpf_cnpj: cpfCnpj,
            }).then((response) => {
                this.cpfCnpj = response.data
            })
        },

        closeModal(clearForm = false) {
            this.showModal = false

            if (clearForm) {
                this.clearErrors()
                this.clearForm()
                this.clearCpfCnpj()
            }
        },

        clearCpfCnpj() {
            this.cpfCnpj = clone(__cpfCnpj)
        },

        onShow() {
            this.busy = false

            this.checkCpfCnpjChecker(this.form.fields.provider_cpf_cnpj)
        },

        isRefund() {
            return this.refund === true
        },
    },

    computed: {
        newCpfCnpj() {
            return !this.cpfCnpj.person
        },

        isCreating() {
            return !this.form.fields.id
        },

        formTitle() {
            return (!this.isCreating ? 'Editar' : 'Novo') + ' lançamento'
        },

        showModal: {
            get() {
                return this.show
            },

            set(showModal) {
                this.$emit('update:show', showModal)
            },
        },

        cpfCnpjMessage() {
            if (blank(this.cpfCnpj.unformatted)) {
                return ''
            }

            if (!this.cpfCnpj.is_valid) {
                return 'Número inválido'
            }

            if (!this.isCreating) {
                //Editando
                if (!this.newCpfCnpj) {
                    this.$store.commit('entries/mutateSetFormField', {
                        field: 'provider_name',
                        value: this.cpfCnpj.person.name,
                    })
                }
            } else {
                //Criando
                if (!this.newCpfCnpj && blank(this.form.fields.to)) {
                    this.$store.commit('entries/mutateSetFormField', {
                        field: 'to',
                        value: this.cpfCnpj.person.name,
                    })
                }
            }

            return (
                (!this.newCpfCnpj ? this.cpfCnpj.person.name + ' - ' : '') +
                this.cpfCnpj.type.toLowerCase() +
                ': ' +
                this.cpfCnpj.formatted
            )
        },
    },
}
</script>
