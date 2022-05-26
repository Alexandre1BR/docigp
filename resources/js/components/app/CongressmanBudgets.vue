<template>
    <div>
        <app-table-panel
            :title="
                'Orçamento mensal' +
                    (tableLoading ? '' : '  (' + pagination.total + ')')
            "
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
            <div class="d-none d-lg-block">
                <app-table
                    :pagination="pagination"
                    @goto-page="gotoPage($event)"
                    :columns="getTableColumns()"
                    statusSize="2"
                >
                    <tr
                        @click="selectCongressmanBudget(congressmanBudget)"
                        v-for="congressmanBudget in congressmanBudgets.data
                            .rows"
                        :class="{
                            'cursor-pointer': true,
                            'bg-primary-lighter': isCurrent(
                                congressmanBudget,
                                selected
                            )
                        }"
                    >
                        <!--                State DEBUG-->
                        <!--                <td class="align-middle">-->
                        <!--                    {{ getCongressmanBudgetState(congressmanBudget).name }}-->
                        <!--                </td>-->

                        <td v-if="can('tables:view-ids')" class="align-middle">
                            {{ congressmanBudget.id }}
                        </td>

                        <td class="align-middle">
                            {{ makeDate(congressmanBudget) }}
                        </td>

                        <!-- <td class="align-middle text-right">
                        {{ congressmanBudget.state_value_formatted }}
                        </td> -->

                        <td class="align-middle text-right">
                            {{ congressmanBudget.value_formatted }} - ({{
                                congressmanBudget.percentage_formatted
                            }})
                        </td>

                        <td class="align-middle text-right">
                            {{ congressmanBudget.entries_count }}
                        </td>

                        <td
                            v-if="can('congressman-budgets:show')"
                            class="align-middle text-center"
                        >
                            <app-badge
                                v-if="congressmanBudget.pendencies.length === 0"
                                caption="não"
                                color="#38c172,#FFFFFF"
                                padding="1"
                            ></app-badge>

                            <app-badge
                                v-if="congressmanBudget.pendencies.length > 0"
                                color="#e3342f,#FFFFFF"
                                padding="1"
                            >
                                <div
                                    class="text-uppercase"
                                    v-for="pendency in congressmanBudget.pendencies"
                                >
                                    &bull; {{ pendency }}<br />
                                </div>
                            </app-badge>
                        </td>

                        <td
                            v-if="can('congressman-budgets:show')"
                            class="align-middle text-center"
                        >
                            <app-status-badge
                                class="text-uppercase"
                                :rows="[
                                    {
                                        value: congressmanBudget.closed_at,
                                        title: 'Verificado: ',
                                        labels: ['sim', 'não']
                                    },
                                    {
                                        value: congressmanBudget.analysed_at,
                                        title: 'Analisado: ',
                                        labels: ['sim', 'não']
                                    },
                                    {
                                        value: congressmanBudget.published_at,
                                        title: 'Publicidade: ',
                                        labels: ['público', 'privado']
                                    }
                                ]"
                            ></app-status-badge>
                        </td>

                        <td
                            v-if="can('congressman-budgets:show')"
                            class="align-middle"
                        >
                            <div class="form-row justify-content-center">
                                <app-action-button
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.deposit.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.deposit.disabled
                                    "
                                    classes="btn btn-micro btn-success col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.deposit.title
                                    "
                                    :model="congressmanBudget"
                                    :swal-title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.deposit.title
                                    "
                                    label="depositar"
                                    icon="fa fa-dollar-sign"
                                    store="congressmanBudgets"
                                    method="deposit"
                                    dusk="deeposit_budget_button"
                                >
                                </app-action-button>

                                <app-percentage-button
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.editPercentage.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.editPercentage.disabled
                                    "
                                    classes="btn btn-micro btn-primary col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.editPercentage.title
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
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.close.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.close.disabled
                                    "
                                    classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.close.title
                                    "
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
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.reopen.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.reopen.disabled
                                    "
                                    classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.reopen.title
                                    "
                                    :model="congressmanBudget"
                                    swal-title="Deseja REABRIR esse Orçamento Mensal?"
                                    label="reabrir"
                                    icon="fa fa-check"
                                    store="congressmanBudgets"
                                    method="reopen"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.analyse.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.analyse.disabled
                                    "
                                    classes="btn btn-micro btn-warning col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.analyse.title
                                    "
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
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unanalyse.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unanalyse.disabled
                                    "
                                    classes="btn btn-micro btn-warning col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unanalyse.title
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
                                    v-if="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.publish.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.publish.disabled
                                    "
                                    classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.publish.title
                                    "
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
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unpublish.visible
                                    "
                                    :disabled="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unpublish.disabled
                                    "
                                    classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                                    :title="
                                        getCongressmanBudgetState(
                                            congressmanBudget
                                        ).buttons.unpublish.title
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
                                    class="col-lg-5 col-xl-3"
                                    :row="congressmanBudget"
                                ></app-audits-button>
                            </div>
                        </td>
                    </tr>
                </app-table>

                <app-entry-form
                    :show.sync="showModal"
                    :refund="true"
                ></app-entry-form>
            </div>

            <!-- Mobile Version -->

            <div class="d-lg-none">



                <div class="accordion" role="tablist">



                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="card-title">Data: 2021 / 12 </h5>
                                </div>
                                <div class="col-4">
                                    <span class="badge badge-success">Verificado</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="card-title">Data: 2021 / 12 </h5>
                                    <div class="text-muted">Id: 2391</div>
                                </div>
                                <div class="col-4">
                                    <span class="badge badge-success">Verificado</span>
                                </div>
                            </div>

                        </div>
                    </div>




                    <div class="card mb-5">
                        <div class="card-body">
                            <h5 class="card-title">Data: 2021 / 12 </h5>
                            <div class="text-muted">Id: 2391</div>
                            <p class="card-text">Solicitado: <strong>R$ 26.819,98 - (100.00%)</strong></p>
                            <p class="card-text">Lançamentos: <strong>21</strong></p>
                            <p class="card-text">
                                Status:<br>
                                <span class="badge badge-success">Verificado</span>
                                <span class="badge badge-danger">Analisado</span>
                                <span class="badge badge-danger">Publico</span>
                            </p>
                            <p class="card-text">
                                Pendências:<br>
                                <span class="badge badge-danger">Analisar Lançamentos</span>
                                <span class="badge badge-danger">Analisr Mês</span>
                                <span class="badge badge-danger">Publicar</span>
                                <span class="badge badge-danger">Limite Excedido</span>
                            </p>
                            <button type="button" class="btn btn-success btn-sm mb-2 mr-2"><i class="fa fa-ban"></i> Analisado </button>
                            <button type="button" class="btn btn-danger btn-sm mb-2 mr-2"><i class="fa fa-ban"></i> Despublicar </button>
                            <button type="button" class="btn btn-danger btn-sm mb-2 mr-2"><i class="fa fa-ban"></i> Despublicar </button>
<!--                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ações
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>-->
<!--
                            <a href="#" class="card-link">+ Detalhes</a>
                            <a href="#" class="card-link">Ações</a>
                            -->
                        </div>
                    </div>




                    <div>
                        <div class="card">
                            <!----><!---->
                            <div class="card-header-custom">
                                <button type="button" class="btn w-100 p-0 mb-0 btn-light collapsed" aria-expanded="false" aria-controls="congressmanBudget2391" style="overflow-anchor: none;">
                                    <div class="card border-0">
                                        <!----><!---->
                                        <div class="card-body">
                                            <!----><!---->
                                            <div class="align-middle text-center">
                                                Id: 2391
                                                <hr>
                                            </div>
                                            <div class="align-middle text-center">
                                                Data: 2021 / 12
                                            </div>
                                            <div>
                                                <hr>
                                            </div>
                                            <div class="align-middle text-center">
                                                Solicitado:
                                                R$ 26.819,98
                                                - (100.00%)
                                            </div>
                                            <div>
                                                <hr>
                                            </div>
                                            <div>
                                                <div class="card-text d-flex justify-content-center">
                                                    Status: Privado
                                                </div>
                                            </div>
                                        </div>
                                        <!----><!---->
                                    </div>
                                </button>
                            </div>
                            <!----><!---->
                        </div>
                        <div id="congressmanBudget2391" class="collapse" role="tabpanel" style="display: none;">
                            <div class="card-body text-center">
                                <!----><!---->
                                <p class="card-text">
                                <h5 class="card-title">Lançamentos</h5>
                                <p class="card-text">
                                    1
                                </p>
                                <div>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title">
                                        Pendências
                                    </h5>
                                    <p class="card-text d-flex justify-content-center">
                                        <!---->
                                    <div class="badge  p-1 m-1" style="background-color: rgb(227, 52, 47); color: rgb(255, 255, 255);">
                                        <div style="text-transform: none;">
                                            <div class="text-uppercase">
                                                •verificar lançamentos<br>
                                            </div>
                                            <div class="text-uppercase">
                                                •analisar lançamentos<br>
                                            </div>
                                            <div class="text-uppercase">
                                                •analisar mês<br>
                                            </div>
                                            <div class="text-uppercase">
                                                •saldo positivo<br>
                                            </div>
                                            <div class="text-uppercase">
                                                •publicar<br>
                                            </div>
                                        </div>
                                        <!---->
                                    </div>
                                    </p>
                                    <hr>
                                </div>
                                <div>
                                    <h5 class="card-title">
                                        Status
                                    </h5>
                                    <p class="card-text d-flex justify-content-center">
                                    <div class="text-uppercase w-25">
                                        <div class="badge-danger rounded-top" style="font-size: 9.6px; font-weight: 700;">
                                            •
                                            Fechado:
                                            não
                                        </div>
                                        <div class="badge-danger rounded-0" style="font-size: 9.6px; font-weight: 700;">
                                            •
                                            Analisado:
                                            não
                                        </div>
                                        <div class="badge-danger rounded-bottom" style="font-size: 9.6px; font-weight: 700;">
                                            •
                                            Publicidade:
                                            privado
                                        </div>
                                    </div>
                                    </p>
                                </div>
                                <div>
                                    <hr>
                                </div>
                                <div class="p-2">
                                    <button title="Depositar R$ 26.819,98 na conta de ATILA NUNES" dusk="deeposit_budget_button" class="button btn btn-micro btn-success col-sm-4"><span class="fa fa-dollar-sign"> depositar</span></button> <button title="Alterar percentual solicitado" dusk="percentageButton" class="col-xl-3 button btn btn-micro btn-primary col-sm-4"><span class="fa fa-edit"> percentual</span></button> <button title="Fechar este orçamento para a análise final" dusk="close_budget_button" class="button btn btn-micro btn-danger col-sm-4"><span class="fa fa-ban"> fechar</span></button> <button disabled="disabled" title="Não é possível reabrir o orçamento sem que ele esteja fechado" class="button btn btn-micro btn-danger col-sm-4"><span class="fa fa-check"> reabrir</span></button> <button disabled="disabled" title="Não é possível analisar o orçamento sem que ele esteja fechado" dusk="analize_budget_button" class="button btn btn-micro btn-warning col-sm-4"><span class="fa fa-check"> analisar</span></button> <!----> <button disabled="disabled" title="Não é possível publicar o orçamento sem que ele esteja fechado e analisado" dusk="publish_budget_button" class="button btn btn-micro btn-danger col-sm-4"><span class="fa fa-check"> publicar</span></button> <!---->
                                    <button title="Logs" type="button" class="btn justify-content-center btn btn-micro btn-primary button col-sm-4 btn-secondary">
                                        <i class="fas fa-clipboard-list"></i> <!---->
                                    </button>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>



                    <app-table
                        :pagination="pagination"
                        @goto-page="gotoPage($event)"
                        statusSize="2"
                    >
                        <div
                            @click="selectCongressmanBudget(congressmanBudget)"
                            v-for="congressmanBudget in congressmanBudgets.data
                                .rows"
                        >
                        <b-card no-body>
                        <div class="card-header-custom">
                            <b-button
                                class="w-100 p-0 mb-0"
                                v-b-toggle="
                                    'congressmanBudget' + congressmanBudget.id
                                "
                                variant="light"
                            >
                                <b-card class="border-0">
                                    <div
                                        v-if="can('tables:view-ids')"
                                        class="align-middle text-center"
                                    >
                                        Id: {{ congressmanBudget.id }}
                                         <hr />
                                    </div>


                                    <div class="align-middle text-center">
                                        Data: {{ makeDate(congressmanBudget) }}
                                    </div>
                                    <div v-if="can('congressman-budgets:show')">
                                        <hr />
                                    </div>

                                    <div class="align-middle text-center">
                                        Solicitado:
                                        {{
                                            congressmanBudget.value_formatted
                                        }}
                                        - ({{
                                            congressmanBudget.percentage_formatted
                                        }})
                                    </div>
                                    <div v-if="can('congressman-budgets:show')">
                                        <hr />
                                    </div>

                                    <div
                                            v-if="
                                                can('congressman-budgets:show')
                                            "
                                        >
                                            <div
                                                class="card-text d-flex justify-content-center"
                                            >
                                            Status: {{congressmanBudget.closed_at && congressmanBudget.analysed_at && congressmanBudget.published_at ? 'Público' : congressmanBudget.closed_at && congressmanBudget.analysed_at ? 'Analisado' : congressmanBudget.closed_at ? 'Fechado' : 'Privado'}}

                                            </div>
                                        </div>
                                </b-card>
                            </b-button>
                        </div>
                        </b-card>
                            <b-collapse
                                :id="'congressmanBudget' + congressmanBudget.id"
                                accordion="congressmanBudget"
                                role="tabpanel"
                                v-model="congressmanBudget.visible"
                            >
                                <b-card-body class="text-center">
                                    <b-card-text>
                                        <h5 class="card-title">Lançamentos</h5>
                                        <p class="card-text">
                                            {{
                                                congressmanBudget.entries_count
                                            }}
                                        </p>
                                        <div
                                            v-if="
                                                can('congressman-budgets:show')
                                            "
                                        >
                                            <hr />
                                        </div>

                                        <div
                                            v-if="
                                                can('congressman-budgets:show')
                                            "
                                        >
                                            <h5 class="card-title">
                                                Pendências
                                            </h5>
                                            <p class="card-text d-flex justify-content-center">
                                                <app-badge
                                                    v-if="
                                                        congressmanBudget
                                                            .pendencies
                                                            .length === 0
                                                    "
                                                    caption="não"
                                                    color="#38c172,#FFFFFF"
                                                    padding="1"
                                                ></app-badge>

                                                <app-badge
                                                    v-if="
                                                        congressmanBudget
                                                            .pendencies.length >
                                                            0
                                                    "
                                                    color="#e3342f,#FFFFFF"
                                                    padding="1"
                                                >
                                                    <div
                                                        class="text-uppercase"
                                                        v-for="pendency in congressmanBudget.pendencies"
                                                    >
                                                        &bull;{{ pendency
                                                        }}<br />
                                                    </div>
                                                </app-badge>
                                            </p>
                                            <hr />
                                        </div>

                                        <div
                                            v-if="
                                                can('congressman-budgets:show')
                                            "
                                        >

                                            <h5 class="card-title">
                                                Status
                                            </h5>
                                            <p class="card-text d-flex justify-content-center">
                                                <app-status-badge
                                                    class="text-uppercase w-25"
                                                    :rows="[
                                                        {
                                                            value:
                                                                congressmanBudget.closed_at,
                                                            title: 'Fechado: ',
                                                            labels: [
                                                                'sim',
                                                                'não'
                                                            ]
                                                        },
                                                        {
                                                            value:
                                                                congressmanBudget.analysed_at,
                                                            title:
                                                                'Analisado: ',
                                                            labels: [
                                                                'sim',
                                                                'não'
                                                            ]
                                                        },
                                                        {
                                                            value:
                                                                congressmanBudget.published_at,
                                                            title:
                                                                'Publicidade: ',
                                                            labels: [
                                                                'público',
                                                                'privado'
                                                            ]
                                                        }
                                                    ]"
                                                ></app-status-badge>
                                            </p>
                                        </div>

                                        <div v-if="can('congressman-budgets:show')">
                                        <hr />
                                    </div>

                                        <div
                                            v-if="
                                                can('congressman-budgets:show')
                                            "
                                            class="p-2"
                                        >
                                            <app-action-button
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.deposit.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.deposit.disabled
                                                "
                                                classes="btn btn-micro btn-success col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.deposit.title
                                                "
                                                :model="congressmanBudget"
                                                :swal-title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.deposit.title
                                                "
                                                label="depositar"
                                                icon="fa fa-dollar-sign"
                                                store="congressmanBudgets"
                                                method="deposit"
                                                dusk="deeposit_budget_button"
                                            >
                                            </app-action-button>

                                            <app-percentage-button
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.editPercentage
                                                        .visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.editPercentage
                                                        .disabled
                                                "
                                                classes="btn btn-micro btn-primary col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.editPercentage
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
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.close.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.close.disabled
                                                "
                                                classes="btn btn-micro btn-danger col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.close.title
                                                "
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
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.reopen.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.reopen.disabled
                                                "
                                                classes="btn btn-micro btn-danger col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.reopen.title
                                                "
                                                :model="congressmanBudget"
                                                swal-title="Deseja REABRIR esse Orçamento Mensal?"
                                                label="reabrir"
                                                icon="fa fa-check"
                                                store="congressmanBudgets"
                                                method="reopen"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.analyse.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.analyse.disabled
                                                "
                                                classes="btn btn-micro btn-warning col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.analyse.title
                                                "
                                                :model="congressmanBudget"
                                                swal-title="Esse Orçamento mensal foi ANALISADO?"
                                                label="analisar"
                                                icon="fa fa-check"
                                                store="congressmanBudgets"
                                                method="analyse"
                                                :spinner-config="{
                                                    color: 'black'
                                                }"
                                                dusk="analize_budget_button"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unanalyse.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unanalyse.disabled
                                                "
                                                classes="btn btn-micro btn-warning col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unanalyse.title
                                                "
                                                :model="congressmanBudget"
                                                swal-title="Deseja remover o status ANALISADO deste lançamento?"
                                                label="analisado"
                                                icon="fa fa-ban"
                                                store="congressmanBudgets"
                                                method="unanalyse"
                                                :spinner-config="{
                                                    color: 'black'
                                                }"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.publish.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.publish.disabled
                                                "
                                                classes="btn btn-micro btn-danger col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.publish.title
                                                "
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
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unpublish.visible
                                                "
                                                :disabled="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unpublish.disabled
                                                "
                                                classes="btn btn-micro btn-danger col-sm-4"
                                                :title="
                                                    getCongressmanBudgetState(
                                                        congressmanBudget
                                                    ).buttons.unpublish.title
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
                                                class="col-sm-4"
                                                :row="congressmanBudget"
                                            ></app-audits-button>
                                        </div>
                                    </b-card-text>
                                </b-card-body>
                            </b-collapse>
                        </div>
                    </app-table>
                </div>
            </div>
            <!-- Mobile Version -->
        </app-table-panel>
    </div>
</template>

<script>
import crud from "../../views/mixins/crud";
import { mapActions, mapGetters, mapState } from "vuex";
import congressmen from "../../views/mixins/congressmen";
import permissions from "../../views/mixins/permissions";
import congressmanBudgets from "../../views/mixins/congressmanBudgets";

const service = {
    name: "congressmanBudgets",
    uri: "congressmen/{congressmen.selected.id}/budgets"
};

export default {
    mixins: [crud, congressmen, congressmanBudgets, permissions],

    data() {
        return {
            service: service,
            showModal: false
        };
    },

    methods: {
        ...mapActions(service.name, ["selectCongressmanBudget"]),

        getTableColumns() {
            let columns = [];

            if (can("tables:view-ids")) {
                columns.push({
                    type: "label",
                    title: "#",
                    trClass: "text-center"
                });
            }

            columns.push("Ano / Mês");

            /* columns.push({
                type: 'label',
                title: 'Referência',
                trClass: 'text-right',
            }) */

            columns.push({
                type: "label",
                title: "Solicitado",
                trClass: "text-right"
            });

            columns.push({
                type: "label",
                title: "Lançamentos",
                trClass: "text-right"
            });

            if (can("congressman-budgets:show")) {
                columns.push({
                    type: "label",
                    title: "Pendências",
                    trClass: "text-center"
                });

                columns.push({
                    type: "label",
                    title: "Status",
                    trClass: "text-center"
                });
                columns.push({
                    type: "label",
                    title: "Ações",
                    trClass: "text-center"
                });
            }

            return columns;
        },

        makeDate(congressmanBudget) {
            return congressmanBudget.year + " / " + congressmanBudget.month;
        },

        deposit(congressmanBudget) {
            this.$swal({
                title:
                    "Confirma o depósito de " +
                    congressmanBudget.value_formatted +
                    " na conta de " +
                    this.congressmen.selected.name +
                    "?",
                icon: "warning"
            }).then(result => {
                if (result.value) {
                    this.$store.dispatch(
                        "congressmanBudgets/deposit",
                        congressmanBudget
                    );
                }
            });
        }

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
            "currentSummaryLabel",
            "getCongressmanBudgetState",
            "getSelectedState"
        ]),
        ...mapState(service.name, ["tableLoading"])
    }
};
</script>
