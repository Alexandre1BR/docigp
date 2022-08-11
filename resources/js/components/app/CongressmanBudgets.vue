<template>
    <div>
        <app-table-panel
            id="scrollCongressmanBudgets"
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




            teste 2

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="id-onlyadmin offset-4 col-4 col-lg-2 text-center">
                            <div class="font-weight-bold mb-3 id-number">
                                # 66822
                            </div>
                        </div>

                        <div class="col-6 col-lg-2 text-center">
                            <p class="font-weight-bold mb-3">
                                2021 / 12
                            </p>

                        </div>
                        <div class="col-6 col-lg-3 text-center">
                            <p class="font-weight-bold mb-3">
                                R$ 26.819,98 - (100.00%)
                            </p>
                        </div>

                        <div class="col-6 col-lg-2 text-center">
                            <p class="font-weight-bold mb-3">
                            Pendências:
                            <span class="badge  p-1 m-1" style="text-transform: none; background-color: rgb(56, 193, 114); color: rgb(255, 255, 255);">
                                não
                            </span>
                            </p>
                        </div>

                        <div class="col-6 col-lg-2 text-center">
                            <p class="font-weight-bold mb-3">
                                Lançamentos: 1
                            </p>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 mb-3 col-lg-6 mb-lg-0">
                            <div id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active">
                                        Verificado</li>
                                    <li>Analisado</li>
                                    <li>Publicidade</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="form-row justify-content-center"><button title="Depositar R$ 26.819,98 na conta de Alexandre Freitas" dusk="deeposit_budget_button" class="button btn btn-micro btn-success col-lg-5 col-xl-3"><span class="fa fa-dollar-sign"> depositar</span></button> <button title="Alterar percentual solicitado" dusk="percentageButton" class="col-xl-3 button btn btn-micro btn-primary col-lg-5 col-xl-3"><span class="fa fa-edit"> percentual</span></button> <button title="Fechar este orçamento para a análise final" dusk="close_budget_button" class="button btn btn-micro btn-danger col-lg-5 col-xl-3"><span class="fa fa-ban"> fechar</span></button> <button disabled="disabled" title="Não é possível reabrir o orçamento sem que ele esteja fechado" class="button btn btn-micro btn-danger col-lg-5 col-xl-3"><span class="fa fa-check"> reabrir</span></button> <button disabled="disabled" title="Não é possível analisar o orçamento sem que ele esteja fechado" dusk="analize_budget_button" class="button btn btn-micro btn-warning col-lg-5 col-xl-3"><span class="fa fa-check"> analisar</span></button> <!----> <button disabled="disabled" title="Não é possível publicar o orçamento sem que ele esteja fechado e analisado" dusk="publish_budget_button" class="button btn btn-micro btn-danger col-lg-5 col-xl-3"><span class="fa fa-check"> publicar</span></button> <!----> <button title="Logs" type="button" class="btn justify-content-center btn btn-micro btn-primary button col-lg-5 col-xl-3 btn-secondary"><i class="fas fa-clipboard-list"></i> <!----></button></div>
                        </div>
                    </div>
                </div>
            </div>





    <div class="d-none d-lg-block">
        <app-table
            :pagination="pagination"
            @goto-page="gotoPage($event)"
            :columns="getTableColumns()"
            statusSize="2"
        >
            <tr
                @click="selectCongressmanBudget(congressmanBudget)"
                v-for="congressmanBudget in congressmanBudgets.data.rows"
                :class="{
                            'cursor-pointer': true,
                            'bg-primary-lighter': isCurrent(congressmanBudget, selected),
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

                <td v-if="can('congressman-budgets:show')" class="align-middle text-center">
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

                <td v-if="can('congressman-budgets:show')" class="align-middle text-center">
                    <app-status-badge
                        class="text-uppercase"
                        :rows="[
                                    {
                                        value: congressmanBudget.closed_at,
                                        title: 'Verificado: ',
                                        labels: ['sim', 'não'],
                                    },
                                    {
                                        value: congressmanBudget.analysed_at,
                                        title: 'Analisado: ',
                                        labels: ['sim', 'não'],
                                    },
                                    {
                                        value: congressmanBudget.published_at,
                                        title: 'Publicidade: ',
                                        labels: ['público', 'privado'],
                                    },
                                ]"
                    ></app-status-badge>
                </td>

                <td v-if="can('congressman-budgets:show')" class="align-middle">
                    <div class="form-row justify-content-center">
                        <app-action-button
                            v-if="
                                        getCongressmanBudgetState(congressmanBudget).buttons.deposit
                                            .visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons.deposit
                                            .disabled
                                    "
                            classes="btn btn-micro btn-success col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.deposit
                                            .title
                                    "
                            :model="congressmanBudget"
                            :swal-title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.deposit
                                            .title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .editPercentage.visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .editPercentage.disabled
                                    "
                            classes="btn btn-micro btn-primary col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .editPercentage.title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons.close
                                            .visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons.close
                                            .disabled
                                    "
                            classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.close
                                            .title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons.reopen
                                            .visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons.reopen
                                            .disabled
                                    "
                            classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.reopen
                                            .title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons.analyse
                                            .visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons.analyse
                                            .disabled
                                    "
                            classes="btn btn-micro btn-warning col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.analyse
                                            .title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unanalyse.visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unanalyse.disabled
                                    "
                            classes="btn btn-micro btn-warning col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unanalyse.title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons.publish
                                            .visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons.publish
                                            .disabled
                                    "
                            classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons.publish
                                            .title
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
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unpublish.visible
                                    "
                            :disabled="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unpublish.disabled
                                    "
                            classes="btn btn-micro btn-danger col-lg-5 col-xl-3"
                            :title="
                                        getCongressmanBudgetState(congressmanBudget).buttons
                                            .unpublish.title
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

        <app-entry-form :show.sync="showModal" :refund="true"></app-entry-form>
    </div>

    <!-- Mobile Version -->

    <div class="d-lg-none">
        <app-table :pagination="pagination" @goto-page="gotoPage($event)" statusSize="2">
            <div
                @click="selectCongressmanBudget(congressmanBudget)"
                v-for="congressmanBudget in congressmanBudgets.data.rows"
                class="mb-1 border rounded"
            >
                <b-button
                    class="w-100 p-3"
                    v-b-toggle="'congressmanBudget' + congressmanBudget.id"
                    variant="light"
                >
                    <div class="row card-header-custom">
                        <div class="col-8 text-left">
                            <h5 class="card-title">
                                {{ makeDate(congressmanBudget) }}
                            </h5>
                            <div v-if="can('tables:view-ids')" class="text-muted">
                                Id: {{ congressmanBudget.id }}
                            </div>
                        </div>

                        <div class="col-4 text-right">
                                    <span class="badge badge-success">
                                        {{
                                            congressmanBudget.closed_at &&
                                            congressmanBudget.analysed_at &&
                                            congressmanBudget.published_at
                                                ? 'Público'
                                                : congressmanBudget.closed_at &&
                                                congressmanBudget.analysed_at
                                                ? 'Analisado'
                                                : congressmanBudget.closed_at
                                                    ? 'Fechado'
                                                    : 'Aberto'
                                        }}
                                    </span>
                        </div>
                    </div>
                </b-button>

                <b-collapse
                    :id="'congressmanBudget' + congressmanBudget.id"
                    accordion="congressmanBudget"
                    role="tabpanel"
                    v-model="congressmanBudget.visible"
                    class="card mb-0 border-0"
                >
                    <b-card-body>
                        <p class="card-text">
                            Solicitado: <strong>R$ 26.819,98 - (100.00%)</strong>
                        </p>
                        <p class="card-text">
                            Lançamentos:
                            <strong>{{ congressmanBudget.entries_count }}</strong>
                        </p>

                        <p v-if="can('congressman-budgets:show')" class="card-text">
                            Status:<br />
                            <span class="card-text" v-if="congressmanBudget.closed_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#38c172,#FFFFFF"
                                            padding="1"
                                        >Fechado</app-badge
                                        >
                                    </span>

                            <span class="card-text" v-if="!congressmanBudget.closed_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#e3342f,#FFFFFF"
                                            padding="1"
                                        >Fechado</app-badge
                                        >
                                    </span>

                            <span class="card-text" v-if="congressmanBudget.analysed_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#38c172,#FFFFFF"
                                            padding="1"
                                        >Analisado</app-badge
                                        >
                                    </span>

                            <span class="card-text" v-if="!congressmanBudget.analysed_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#e3342f,#FFFFFF"
                                            padding="1"
                                        >Analisado</app-badge
                                        >
                                    </span>

                            <span class="card-text" v-if="congressmanBudget.published_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#38c172,#FFFFFF"
                                            padding="1"
                                        >Público</app-badge
                                        >
                                    </span>

                            <span class="card-text" v-if="!congressmanBudget.published_at">
                                        <app-badge
                                            class="text-uppercase w-25"
                                            color="#e3342f,#FFFFFF"
                                            padding="1"
                                        >Privado</app-badge
                                        >
                                    </span>
                        </p>

                        <div v-if="can('congressman-budgets:show')" class="card-text">
                            Pendências:<br />
                            <div class="card-text pb-3">
                                <app-badge
                                    v-if="congressmanBudget.pendencies.length === 0"
                                    caption="não"
                                    color="#38c172,#FFFFFF"
                                    padding="1"
                                ></app-badge>

                                <span
                                    v-if="congressmanBudget.pendencies.length > 0"
                                    class="text-uppercase mr-1"
                                    v-for="pendency in congressmanBudget.pendencies"
                                ><app-badge color="#e3342f,#FFFFFF" padding="1">
                                                {{ pendency }}<br
                                /></app-badge>
                                        </span>
                            </div>
                        </div>

                        <div v-if="can('congressman-budgets:show')">
                            <app-action-button
                                v-if="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .deposit.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .deposit.disabled
                                        "
                                classes="btn btn-micro btn-success col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .deposit.title
                                        "
                                :model="congressmanBudget"
                                :swal-title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .deposit.title
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
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .editPercentage.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .editPercentage.disabled
                                        "
                                classes="btn btn-micro btn-primary col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .editPercentage.title
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
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .close.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .close.disabled
                                        "
                                classes="btn btn-micro btn-danger col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .close.title
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
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .reopen.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .reopen.disabled
                                        "
                                classes="btn btn-micro btn-danger col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .reopen.title
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
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .analyse.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .analyse.disabled
                                        "
                                classes="btn btn-micro btn-warning col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .analyse.title
                                        "
                                :model="congressmanBudget"
                                swal-title="Esse Orçamento mensal foi ANALISADO?"
                                label="analisar"
                                icon="fa fa-check"
                                store="congressmanBudgets"
                                method="analyse"
                                :spinner-config="{
                                            color: 'black',
                                        }"
                                dusk="analize_budget_button"
                            >
                            </app-action-button>

                            <app-action-button
                                v-if="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unanalyse.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unanalyse.disabled
                                        "
                                classes="btn btn-micro btn-warning col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unanalyse.title
                                        "
                                :model="congressmanBudget"
                                swal-title="Deseja remover o status ANALISADO deste lançamento?"
                                label="analisado"
                                icon="fa fa-ban"
                                store="congressmanBudgets"
                                method="unanalyse"
                                :spinner-config="{
                                            color: 'black',
                                        }"
                            >
                            </app-action-button>

                            <app-action-button
                                v-if="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .publish.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .publish.disabled
                                        "
                                classes="btn btn-micro btn-danger col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .publish.title
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
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unpublish.visible
                                        "
                                :disabled="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unpublish.disabled
                                        "
                                classes="btn btn-micro btn-danger col-sm-4"
                                :title="
                                            getCongressmanBudgetState(congressmanBudget).buttons
                                                .unpublish.title
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
                    </b-card-body>
                </b-collapse>
            </div>
        </app-table>
    </div>

    <!-- Mobile Version -->
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
        ...mapState(service.name, ['tableLoading']),
    },
}
</script>
