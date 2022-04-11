<template>
    <div>
    
    
    <app-table-panel 
        :title="'Orçamento mensal' + (tableLoading ? '' : '  (' + pagination.total + ')')"
        titleCollapsed="Orçamento"
        :subTitle="congressmen.selected.name"
        :per-page="perPage"
        :filter-text="filterText"
        @input-filter-text="filterText = $event.target.value"
        @set-per-page="perPage = $event"
        :collapsedLabel="currentSummaryLabel"
        :is-selected="selected.id !== null"
        :isLoading="tableLoading"    
    >

        <app-table 
            :pagination="pagination"
            @goto-page="gotoPage($event)"
            :columns="getTableColumns()"
            
        > 
        
            
            <tr @click="selectCongressmanBudget(congressmanBudget)"
                v-for="congressmanBudget in congressmanBudgets.data.rows"
                :class="{
                    'cursor-pointer': true,
                    'bg-primary-lighter text-white': isCurrent(congressmanBudget, selected),
                }">  
               
            
                <!--                State DEBUG-->
                <!--                <td class="align-middle">-->
                <!--                    {{ getCongressmanBudgetState(congressmanBudget).name }}-->
                <!--                </td>-->
                

                <td v-if="can('tables:view-ids')" class="align-middle">
                    {{ congressmanBudget.id }}
                </td>

                <td class="align-middle">{{ makeDate(congressmanBudget) }}</td>

                <!-- <td class="align-middle text-right">
                    {{ congressmanBudget.state_value_formatted }}
                </td> -->

                <td class="align-middle text-right">
                    {{ congressmanBudget.percentage_formatted }}
                </td>

                <td class="align-middle text-right">
                    {{ congressmanBudget.value_formatted }}
                </td>

                <td class="align-middle text-right">
                    {{ congressmanBudget.entries_count }}
                </td>

                <td v-if="can('congressman-budgets:show')" class="align-middle text-center">
                    <app-badge
                        v-if="congressmanBudget.pendencies.length === 0"
                        color="#38c172,#FFFFFF"
                        padding="1"
                        font-size='12px'
                        
                        
                    ></app-badge>

                    <app-badge
                        v-if="congressmanBudget.pendencies.length > 0"
                        color="ccc, ccc"
                        padding="1"
                        font-size='12px'
                    >
                    <div v-for="pendency in congressmanBudget.pendencies">
                    <app-active-badge 
                      :title="pendency"
                       :labels="['', '']"   
                    >
                        <div class="text-uppercase mb-1 mt-1" v-for="pendency in congressmanBudget.pendencies">
                            &bull; {{ pendency }}<br />
                        </div>
                    </app-badge>
                </td>

                <td
                    v-if="can('congressman-budgets:show')"
                    class="align-middle text-center text-uppercase"
                >
                
                    <app-status-badge
                        :value="congressmanBudget.closed_at"
                        title='Fechado'
                        :labels="['sim', 'não']"
                        class="text-uppercase"
                    ></app-status-badge>
                    
                     
                    <app-status-badge
                        :value="congressmanBudget.analysed_at"
                        title='Analisado'
                        :labels="['sim', 'não']"
                        class="text-uppercase"
                    ></app-status-badge>
                    
                    <app-status-badge
                        :value="congressmanBudget.published_at"
                        title='Publicidade'
                        :labels="['público', 'privado']"
                        class="text-uppercase"
                        style="border-radius=0.1px"
                    ></app-status-badge>
                
                </td>

                <td  v-if="can('congressman-budgets:show')" class="align-middle text-right">
                    <app-action-button
                        v-if="getCongressmanBudgetState(congressmanBudget).buttons.deposit.visible"
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.deposit.disabled
                        "
                        classes="btn btn-sm btn-success"
                        :title="getCongressmanBudgetState(congressmanBudget).buttons.deposit.title" 
                        :model="congressmanBudget"
                        :swal-title="getCongressmanBudgetState(congressmanBudget).buttons.deposit.title" 
                        label="depositar"
                        icon="fa fa-dollar-sign"
                        store="congressmanBudgets"
                        method="deposit"
                        dusk="deeposit_budget_button"
                    >
                    </app-action-button>
                    

                    <app-percentage-button
                        v-if="
                            getCongressmanBudgetState(congressmanBudget).buttons.editPercentage
                                .visible
                        "
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.editPercentage
                                .disabled
                        "
                        classes="btn btn-sm btn-primary"
                        :title="
                            getCongressmanBudgetState(congressmanBudget).buttons.editPercentage
                                .title
                        "
                        :model="congressmanBudget"
                        label="percentual"
                        icon="fa fa-edit"
                        store="congressmanBudgets"
                        method="editPercentage"
                        dusk="percentageButton"
                    >
                    </app-percentage-button>

                    <app-action-button
                        v-if="getCongressmanBudgetState(congressmanBudget).buttons.close.visible"
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.close.disabled
                        "
                        classes="btn btn-sm btn-danger"
                        :title="getCongressmanBudgetState(congressmanBudget).buttons.close.title"
                        :model="congressmanBudget"
                        swal-title="Deseja realmente FECHAR esse Orçamento Mensal?"
                        label="fechar"
                        icon="fa fa-ban"
                        store="congressmanBudgets"
                        method="close"
                        dusk="close_budget_button"
                    >
                    </app-action-button>

                    <app-action-button
                        v-if="getCongressmanBudgetState(congressmanBudget).buttons.reopen.visible"
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.reopen.disabled
                        "
                        
                        classes="btn btn-sm btn-danger"
                        :title="getCongressmanBudgetState(congressmanBudget).buttons.reopen.title"
                        :model="congressmanBudget"
                        swal-title="Deseja REABRIR esse Orçamento Mensal?"
                        label="reabrir"
                        icon="fa fa-check"
                        store="congressmanBudgets"
                        method="reopen"
                    >
                    </app-action-button>

                    <app-action-button
                        v-if="getCongressmanBudgetState(congressmanBudget).buttons.analyse.visible"
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.analyse.disabled
                        "
                        classes="btn btn-sm btn-warning"
                        :title="getCongressmanBudgetState(congressmanBudget).buttons.analyse.title"
                        :model="congressmanBudget"
                        swal-title="Esse Orçamento mensal foi ANALISADO?"
                        label="analisar"
                        icon="fa fa-check"
                        store="congressmanBudgets"
                        method="analyse"
                        :spinner-config="{ color: 'black' }"
                        dusk="analize_budget_button"
                    >
                    </app-action-button>

                    <app-action-button
                        v-if="
                            getCongressmanBudgetState(congressmanBudget).buttons.unanalyse.visible
                        "
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.unanalyse.disabled
                        "
                        classes="btn btn-sm btn-warning"
                        :title="
                            getCongressmanBudgetState(congressmanBudget).buttons.unanalyse.title
                        "
                        :model="congressmanBudget"
                        swal-title="Deseja remover o status ANALISADO deste lançamento?"
                        label="analisado"
                        icon="fa fa-ban"
                        store="congressmanBudgets"
                        method="unanalyse"
                        :spinner-config="{ color: 'black' }"
                    >
                    </app-action-button>

                    <app-action-button
                        v-if="getCongressmanBudgetState(congressmanBudget).buttons.publish.visible"
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.publish.disabled
                        "
                        classes="btn btn-sm btn-danger"
                        :title="getCongressmanBudgetState(congressmanBudget).buttons.publish.title"
                        :model="congressmanBudget"
                        swal-title="Confirma a PUBLICAÇÃO deste Orçamento Mensal?"
                        label="publicar"
                        icon="fa fa-check"
                        store="congressmanBudgets"
                        method="publish"
                        dusk="publish_budget_button"
                    >
                    </app-action-button>

                    <app-action-button
                        v-if="
                            getCongressmanBudgetState(congressmanBudget).buttons.unpublish.visible
                        "
                        :disabled="
                            getCongressmanBudgetState(congressmanBudget).buttons.unpublish.disabled
                        "
                        classes="btn btn-sm btn-danger"
                        :title="
                            getCongressmanBudgetState(congressmanBudget).buttons.unpublish.title
                        "
                        :model="congressmanBudget"
                        swal-title="Confirma a DESPUBLICAÇÃO deste Orçamento Mensal?"
                        label="despublicar"
                        icon="fa fa-ban"
                        store="congressmanBudgets"
                        method="unpublish"
                    >
                    </app-action-button>

                    <app-audits-button
                        model="congressmanBudgets"
                        :row="congressmanBudget"
                    ></app-audits-button>
                </td>
           

            
           </tr>

            
        </app-table>

        <app-entry-form :show.sync="showModal" :refund="true"></app-entry-form>
    </app-table-panel>


    </div>

    
</template>

<script>
import crud from '../../views/mixins/crud'
import { mapActions, mapGetters, mapState } from 'vuex'
import congressmen from '../../views/mixins/congressmen'
import permissions from '../../views/mixins/permissions'
import congressmanBudgets from '../../views/mixins/congressmanBudgets'

const service = {
    name: 'congressmanBudgets',
    uri: 'congressmen/{congressmen.selected.id}/budgets',
}

export default {
    mixins: [crud, congressmen, congressmanBudgets, permissions],

    data() {
        return {
            service: service,
            showModal: false,
            
        }
    },


    
    
    methods: {

        ...mapActions(service.name, ['selectCongressmanBudget']),

        getTableColumns() {
            let columns = []

            if (can('tables:view-ids')) {
                columns.push({
                    type: 'label',
                    title: '#',
                    trClass: 'text-center',
                })
            }

            columns.push('Ano / Mês')

            /* columns.push({
                type: 'label',
                title: 'Referência',
                trClass: 'text-right',
            }) */

            columns.push({
                type: 'label',
                title: '%',
                trClass: 'text-right',
            })

            columns.push({
                type: 'label',
                title: 'Solicitado',
                trClass: 'text-right',
            })

            columns.push({
                type: 'label',
                title: 'Lançamentos',
                trClass: 'text-right',
            })

            if (can('congressman-budgets:show')) {
                columns.push({
                    type: 'label',
                    title: 'Pendências',
                    trClass: 'text-center',
                })

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

        makeDate(congressmanBudget) {
            return congressmanBudget.year + ' / ' + congressmanBudget.month
        },

        deposit(congressmanBudget) {
            this.$swal({
                title:
                    'Confirma o depósito de ' +
                    congressmanBudget.value_formatted +
                    ' na conta de ' +
                    this.congressmen.selected.name +
                    '?',
                icon: 'warning',
            }).then((result) => {
                if (result.value) {
                    this.$store.dispatch('congressmanBudgets/deposit', congressmanBudget)
                }
            })
        },

        // createRefund(congressmanBudget) {
        //     this.$store
        //         .dispatch(
        //             'congressmanBudgets/selectCongressmanBudget',
        //             congressmanBudget,
        //         )
        //         .then(
        //             this.$store
        //                 .dispatch('entries/fillFormForRefund')
        //                 .then(() => (this.showModal = true)),
        //         )
        // },
    },

    computed: {
        ...mapGetters(service.name, [
            'currentSummaryLabel',
            'getCongressmanBudgetState',
            'getSelectedState',     
        ]),
        ...mapState(service.name, ['tableLoading'])
    }
    
}
</script>

