<template>
    <div>
        <app-table-panel
            :title="
                'Lançamentos' +
                    (tableLoading ? '' : '   (' + pagination.total + ')')
            "
            titleCollapsed="Lançamento"
            :subTitle="
                congressmen.selected.name +
                    ' - ' +
                    congressmanBudgetsSummaryLabel
            "
            :per-page="perPage"
            :filter-text="filterText"
            @input-filter-text="filterText = $event.target.value"
            @set-per-page="perPage = $event"
            :collapsedLabel="currentSummaryLabel"
            :is-selected="selected.id !== null"
            :isLoading="tableLoading"
        >
            <template slot="widgets" v-if="can('entries:show')">
                <div class="mr-2" v-if="!tableLoading">
                    <span
                        class="btn btn-sm"
                        :class="{
                            'btn-outline-success':
                                congressmanBudgets.selected.balance >= 0,
                            'btn-outline-danger':
                                congressmanBudgets.selected.balance < 0
                        }"
                    >
                        saldo acumulado |
                        {{ congressmanBudgets.selected.balance_formatted }}
                    </span>
                </div>
            </template>

            <template slot="buttons">
                <button
                    v-if="can('entries:buttons') || can('entries:store')"
                    :disabled="
                        !can('entries:store') || congressmanBudgetsClosedAt
                    "
                    class="btn btn-primary btn-sm pull-right"
                    @click="createEntry()"
                    title="Novo lançamento"
                    dusk="newentry"
                >
                    <i class="fa fa-plus"></i>
                </button>
            </template>

            <div class="d-lg-none">
                <app-table
                    :pagination="pagination"
                    @goto-page="gotoPage($event)"
                    statusSize="1"
                    actionsSize="2"
                >
                    <div
                        @click="selectEntry(entry)"
                        v-for="entry in entries.data.rows"
                        :class="{
                            'cursor-pointer': true,
                            'bg-primary-lighter text-white': isCurrent(
                                entry,
                                selected
                            )
                        }"
                        :dusk="'entrie'"
                    >
                        <div class="accordion">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <button
                                        class="btn collapsed"
                                        data-toggle="collapse"
                                        :data-target="'#x' + entry.id"
                                        aria-expanded="false"
                                        :aria-controls="'#x' + entry.id"
                                    >
                                        <thead>
                                            <tr>
                                                <th
                                                    v-if="can('tables:view-ids')"
                                                    class="text-center"
                                                    style="width:300px;"
                                                >
                                                    <span>#</span>
                                                </th>
                                                <th
                                                    class="text-center"
                                                    style="width:300px;"
                                                >
                                                    <span> Data </span>
                                                </th>
                                                <th
                                                    class="text-center"
                                                    style="width:300px;"
                                                >
                                                    <span> Objeto </span>
                                                </th>
                                                <th
                                                    class="text-center"
                                                    style="width:300px;"
                                                >
                                                    <span> Favorecido </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td
                                                v-if="can('tables:view-ids')"
                                                class="align-middle text-center"
                                            >
                                                {{ entry.id }}
                                            </td>
                                            <td
                                                class="align-middle text-center"
                                            >
                                                {{ entry.date_formatted }}
                                            </td>

                                            <td
                                                class="align-middle text-center"
                                            >
                                                {{ entry.object }}<br />
                                                <span>
                                                    <small class="text-primary">
                                                        {{
                                                            entry.cost_center_code
                                                        }}
                                                        -
                                                        {{
                                                            entry.cost_center_name_formatted
                                                        }}
                                                    </small>
                                                </span>
                                            </td>

                                            <td
                                                class="align-middle text-center"
                                            >
                                                {{ entry.name }}
                                                <span v-if="entry.cpf_cnpj">
                                                    <br />
                                                    <small class="text-primary">
                                                        {{ entry.cpf_cnpj }}
                                                        <b class="text-danger">
                                                            {{
                                                                can(
                                                                    "entries:show"
                                                                ) &&
                                                                entry.provider_is_blocked
                                                                    ? "- Bloqueado pela DOCIGP"
                                                                    : ""
                                                            }}
                                                        </b>
                                                    </small>
                                                </span>
                                            </td>
                                        </tbody>
                                    </button>
                                </div>
                                <div
                                    :id="'x' + entry.id"
                                    class="collapse"
                                    aria-labelledby="headingOne"
                                    data-parent="#accordion"
                                >
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Valor</h5>
                                        <p class="card-text">
                                            {{ entry.value_formatted }}
                                        </p>
                                        <hr />

                                        <h5 v-if="can('entries:show')" class="card-title">Tipo</h5>
                                        <p v-if="can('entries:show')" class="card-text">
                                            <span
                                                :class="
                                                    getEntryType(entry).class
                                                "
                                            >
                                                {{ getEntryType(entry).name }}
                                            </span>
                                            <hr />
                                        </p>
                                        
                                        

                                        <h5 v-if="can('entries:show')" class="card-title">Meio</h5>
                                        <p v-if="can('entries:show')" class="card-text">
                                            <span
                                                class="badge badge-primary text-uppercase"
                                            >
                                                {{
                                                    entry.entry_type_name +
                                                        (entry.document_number
                                                            ? ": " +
                                                              entry.document_number
                                                            : "")
                                                }}
                                            </span>
                                              <hr />
                                        </p>
                                      


                                        <h5 v-if="can('congressman-budgets:show')" class="card-title">Pendências</h5>
                                        <p v-if="can('congressman-budgets:show')" class="card-text">
                                            <app-badge
                                                v-if="
                                                    entry.pendencies.length ===
                                                        0
                                                "
                                                caption="não"
                                                color="#38c172,#fff"
                                                padding="1"
                                            ></app-badge>

                                            <app-badge
                                                v-if="
                                                    entry.pendencies.length > 0
                                                "
                                                color="#e3342f,#FFFFFF"
                                                padding="1"
                                            >
                                                <div
                                                    class="text-uppercase"
                                                    v-for="pendency in entry.pendencies"
                                                >
                                                    &bull; {{ pendency }}
                                                </div>
                                            </app-badge>
                                             <hr />
                                        </p>
                                       


                                        <h5 v-if="can('entries:show')" class="card-title">Status</h5>
                                        <p v-if="can('entries:show')" class="card-text d-flex justify-content-center">
                                            <app-status-badge
                                                class="text-uppercase w-25"
                                                :rows="[
                                                    {
                                                        value:
                                                            entry.verified_at,
                                                        title: 'Verificado: ',
                                                        labels: ['sim', 'não']
                                                    },
                                                    {
                                                        value:
                                                            entry.analysed_at,
                                                        title: 'Analisado: ',
                                                        labels: ['sim', 'não']
                                                    },
                                                    {
                                                        value:
                                                            entry.published_at &&
                                                            !entry.is_transport_or_credit,
                                                        title: 'Publicidade: ',
                                                        labels: [
                                                            'público',
                                                            'privado'
                                                        ]
                                                    }
                                                ]"
                                            ></app-status-badge>
                                             <hr />
                                        </p>
                                       

                                        <h5 class="card-title">Documentos</h5>
                                        <p class="card-text">
                                            {{ entry.documents_count }}
                                        </p>
                                        <hr />

                                        <h5 class="card-title">Comentários</h5>
                                        <p class="card-text">
                                            {{ entry.comments_count }}
                                        </p>
                                        <hr />

                                        <div
                                            class="form-row justify-content-center pb-2"
                                        >
                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .verify.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .verify.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-primary"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .verify.title
                                                "
                                                :model="entry"
                                                swal-title="Verificar este lançamento?"
                                                label="verificar"
                                                icon="fa fa-check"
                                                store="entries"
                                                method="verify"
                                                dusk="verify_entry_button"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .unverify.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .unverify.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-warning"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .unverify.title
                                                "
                                                :model="entry"
                                                swal-title="Remover verificação deste lançamento?"
                                                label="verificado"
                                                icon="fa fa-ban"
                                                store="entries"
                                                method="unverify"
                                                :spinner-config="{
                                                    color: 'black'
                                                }"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .analyse.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .analyse.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-success"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .analyse.title
                                                "
                                                :model="entry"
                                                swal-title="Analisar este lançamento?"
                                                label="analisar"
                                                icon="fa fa-check"
                                                store="entries"
                                                method="analyse"
                                                dusk="analize_entry_button"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .unanalyse.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .unanalyse.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-danger"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .unanalyse.title
                                                "
                                                :model="entry"
                                                swal-title="Remover análise deste lançamento?"
                                                label="analisado"
                                                icon="fa fa-ban"
                                                store="entries"
                                                method="unanalyse"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .publish.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .publish.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-danger"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .publish.title
                                                "
                                                :model="entry"
                                                swal-title="Publicar este lançamento?"
                                                label="publicar"
                                                icon="fa fa-check"
                                                store="entries"
                                                method="publish"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .unpublish.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .unpublish.disabled
                                                "
                                                classes="col-xl-3 col-lg-5 col-sm-2 col-2 btn btn-micro btn-danger"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .unpublish.title
                                                "
                                                :model="entry"
                                                swal-title="Despublicar este lançamento?"
                                                label="despublicar"
                                                icon="fa fa-ban"
                                                store="entries"
                                                method="unpublish"
                                            >
                                            </app-action-button>

                                            <app-action-button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .delete.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .delete.disabled
                                                "
                                                classes="btn btn-micro col-xl-3 col-lg-5 col-sm-2 col-2 btn-danger"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .delete.title
                                                "
                                                :model="entry"
                                                swal-title="Deseja realmente deletar este lançamento?"
                                                label=""
                                                icon="fa fa-trash"
                                                store="entries"
                                                method="delete"
                                                :spinner-config="{
                                                    size: '0.02em'
                                                }"
                                                :swal-message="{
                                                    r200: 'Deletado com sucesso'
                                                }"
                                                :is-delete-entry="true"
                                            >
                                            </app-action-button>

                                            <button
                                                v-if="
                                                    getEntryState(entry).buttons
                                                        .edit.visible
                                                "
                                                :disabled="
                                                    getEntryState(entry).buttons
                                                        .edit.disabled
                                                "
                                                class="btn btn-micro col-xl-3 col-lg-5 col-sm-2 col-2 btn-primary button"
                                                @click="editEntry(entry)"
                                                :title="
                                                    getEntryState(entry).buttons
                                                        .edit.title
                                                "
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <app-audits-button
                                                class="col-xl-3 col-lg-5 col-sm-2 col-2"
                                                model="entries"
                                                :row="entry"
                                            ></app-audits-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </app-table>
            </div>

            <div class="d-none d-lg-block">
                <app-table
                    :pagination="pagination"
                    @goto-page="gotoPage($event)"
                    :columns="getTableColumns()"
                    statusSize="1"
                    actionsSize="2"
                >
                    <tr
                        @click="selectEntry(entry)"
                        v-for="entry in entries.data.rows"
                        :class="{
                            'cursor-pointer': true,
                            'bg-primary-lighter text-white': isCurrent(
                                entry,
                                selected
                            )
                        }"
                        :dusk="'entrie'"
                    >
                        <td v-if="can('tables:view-ids')" class="align-middle">
                            {{ entry.id }}
                        </td>

                        <td class="align-middle">{{ entry.date_formatted }}</td>

                        <td class="align-middle">
                            {{ entry.object }}<br />
                            <span>
                                <small class="text-primary">
                                    {{ entry.cost_center_code }} -
                                    {{ entry.cost_center_name_formatted }}
                                </small>
                            </span>
                        </td>

                        <td class="align-middle">
                            {{ entry.name }}
                            <span v-if="entry.cpf_cnpj">
                                <br />
                                <small class="text-primary">
                                    {{ entry.cpf_cnpj }}
                                    <b class="text-danger">
                                        {{
                                            can("entries:show") &&
                                            entry.provider_is_blocked
                                                ? "- Bloqueado pela DOCIGP"
                                                : ""
                                        }}
                                    </b>
                                </small>
                            </span>
                        </td>

                        <td class="align-middle text-right">
                            {{ entry.documents_count }}
                        </td>

                        <td
                            v-if="can('entry-comments:show')"
                            class="align-middle text-right"
                        >
                            {{ entry.comments_count }}
                        </td>

                        <td class="align-middle text-right">
                            {{ entry.value_formatted }}
                        </td>

                        <td
                            v-if="can('entries:show')"
                            class="align-middle text-center"
                        >
                            <span :class="getEntryType(entry).class">
                                {{ getEntryType(entry).name }}
                            </span>
                        </td>

                        <td
                            v-if="can('entries:show')"
                            class="align-middle text-center"
                        >
                            <span class="badge badge-primary text-uppercase">
                                {{
                                    entry.entry_type_name +
                                        (entry.document_number
                                            ? ": " + entry.document_number
                                            : "")
                                }}
                            </span>
                        </td>

                        <td
                            v-if="can('congressman-budgets:show')"
                            class="align-middle text-center"
                        >
                            <app-badge
                                v-if="entry.pendencies.length === 0"
                                caption="não"
                                color="#38c172,#fff"
                                padding="1"
                            ></app-badge>

                            <app-badge
                                v-if="entry.pendencies.length > 0"
                                color="#e3342f,#FFFFFF"
                                padding="1"
                            >
                                <div
                                    class="text-uppercase"
                                    v-for="pendency in entry.pendencies"
                                >
                                    &bull; {{ pendency }}
                                </div>
                            </app-badge>
                        </td>

                        <td
                            v-if="can('entries:show')"
                            class="align-middle text-center"
                        >
                            <app-status-badge
                                class="text-uppercase"
                                :rows="[
                                    {
                                        value: entry.verified_at,
                                        title: 'Verificado: ',
                                        labels: ['sim', 'não']
                                    },
                                    {
                                        value: entry.analysed_at,
                                        title: 'Analisado: ',
                                        labels: ['sim', 'não']
                                    },
                                    {
                                        value:
                                            entry.published_at &&
                                            !entry.is_transport_or_credit,
                                        title: 'Publicidade: ',
                                        labels: ['público', 'privado']
                                    }
                                ]"
                            ></app-status-badge>
                        </td>
                        <td class="align-middle">
                            <div class="form-row justify-content-center">
                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.verify
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.verify
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-primary"
                                    :title="
                                        getEntryState(entry).buttons.verify
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Verificar este lançamento?"
                                    label="verificar"
                                    icon="fa fa-check"
                                    store="entries"
                                    method="verify"
                                    dusk="verify_entry_button"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.unverify
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.unverify
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-warning"
                                    :title="
                                        getEntryState(entry).buttons.unverify
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Remover verificação deste lançamento?"
                                    label="verificado"
                                    icon="fa fa-ban"
                                    store="entries"
                                    method="unverify"
                                    :spinner-config="{ color: 'black' }"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.analyse
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.analyse
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-success"
                                    :title="
                                        getEntryState(entry).buttons.analyse
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Analisar este lançamento?"
                                    label="analisar"
                                    icon="fa fa-check"
                                    store="entries"
                                    method="analyse"
                                    dusk="analize_entry_button"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.unanalyse
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.unanalyse
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-danger"
                                    :title="
                                        getEntryState(entry).buttons.unanalyse
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Remover análise deste lançamento?"
                                    label="analisado"
                                    icon="fa fa-ban"
                                    store="entries"
                                    method="unanalyse"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.publish
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.publish
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-danger"
                                    :title="
                                        getEntryState(entry).buttons.publish
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Publicar este lançamento?"
                                    label="publicar"
                                    icon="fa fa-check"
                                    store="entries"
                                    method="publish"
                                >
                                </app-action-button>

                                <app-action-button
                                    v-if="
                                        getEntryState(entry).buttons.unpublish
                                            .visible
                                    "
                                    :disabled="
                                        getEntryState(entry).buttons.unpublish
                                            .disabled
                                    "
                                    classes="col-xl-3 col-lg-5 btn btn-micro btn-danger"
                                    :title="
                                        getEntryState(entry).buttons.unpublish
                                            .title
                                    "
                                    :model="entry"
                                    swal-title="Despublicar este lançamento?"
                                    label="despublicar"
                                    icon="fa fa-ban"
                                    store="entries"
                                    method="unpublish"
                                >
                                </app-action-button>
                                <div class="col-md-12 text-center">
                                    <app-action-button
                                        v-if="
                                            getEntryState(entry).buttons.delete
                                                .visible
                                        "
                                        :disabled="
                                            getEntryState(entry).buttons.delete
                                                .disabled
                                        "
                                        classes="btn btn-micro  btn-danger smallButton"
                                        :title="
                                            getEntryState(entry).buttons.delete
                                                .title
                                        "
                                        :model="entry"
                                        swal-title="Deseja realmente deletar este lançamento?"
                                        label=""
                                        icon="fa fa-trash"
                                        store="entries"
                                        method="delete"
                                        :spinner-config="{ size: '0.02em' }"
                                        :swal-message="{
                                            r200: 'Deletado com sucesso'
                                        }"
                                        :is-delete-entry="true"
                                    >
                                    </app-action-button>

                                    <button
                                        v-if="
                                            getEntryState(entry).buttons.edit
                                                .visible
                                        "
                                        :disabled="
                                            getEntryState(entry).buttons.edit
                                                .disabled
                                        "
                                        class="btn btn-micro btn-primary button smallButton"
                                        @click="editEntry(entry)"
                                        :title="
                                            getEntryState(entry).buttons.edit
                                                .title
                                        "
                                    >
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <app-audits-button
                                        class="smallButton"
                                        model="entries"
                                        :row="entry"
                                    ></app-audits-button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </app-table>
            </div>
            <app-entry-form :show.sync="showModal"></app-entry-form>
        </app-table-panel>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from "vuex";
import crud from "../../views/mixins/crud";
import entries from "../../views/mixins/entries";
import congressmen from "../../views/mixins/congressmen";
import permissions from "../../views/mixins/permissions";
import congressmanBudgets from "../../views/mixins/congressmanBudgets";
const service = {
    name: "entries",
    uri:
        "congressmen/{congressmen.selected.id}/budgets/{congressmanBudgets.selected.id}/entries"
};
export default {
    mixins: [crud, entries, permissions, congressmanBudgets, congressmen],
    data() {
        return {
            service: service,
            showModal: false
        };
    },
    methods: {
        ...mapActions(service.name, [
            "selectEntry",
            "clearForm",
            "clearErrors"
        ]),

        getEntryType(entry) {
            if (entry.cost_center_code == 2) {
                return {
                    name: "transporte",
                    class:
                        entry.value > 0
                            ? "badge badge-danger"
                            : "badge badge-success"
                };
            } else if (entry.cost_center_code == 3) {
                return {
                    name: "transporte",
                    class:
                        entry.value >= 0
                            ? "badge badge-success"
                            : "badge badge-danger"
                };
            } else if (entry.cost_center_code == 4) {
                return {
                    name: "devolução",
                    class: "badge badge-warning text-uppercase"
                };
            } else {
                if (entry.value > 0) {
                    return {
                        name: "crédito",
                        class: "badge badge-success text-uppercase"
                    };
                } else {
                    return {
                        name: "débito",
                        class: "badge badge-dark text-uppercase"
                    };
                }
            }
        },
        getTableColumns() {
            let columns = [];
            if (can("tables:view-ids")) {
                columns.push({
                    type: "label",
                    title: "#",
                    trClass: "text-center"
                });
            }
            columns.push("Data");
            columns.push("Objeto");
            columns.push("Favorecido");
            columns.push({
                type: "label",
                title: "Documentos",
                trClass: "text-right"
            });
            if (can("entry-comments:show")) {
                columns.push({
                    type: "label",
                    title: "Comentários",
                    trClass: "text-right"
                });
            }
            columns.push({
                type: "label",
                title: "Valor",
                trClass: "text-right"
            });
            if (can("entries:show")) {
                columns.push({
                    type: "label",
                    title: "Tipo",
                    trClass: "text-center"
                });
                columns.push({
                    type: "label",
                    title: "Meio",
                    trClass: "text-center"
                });
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
        createEntry() {
            if (filled(this.form.id)) {
                this.clearForm();
            }
            this.showModal = true;
        },
        editEntry(entry) {
            this.showModal = true;
        }
    },
    computed: {
        ...mapGetters({
            congressmanBudgetsSummaryLabel:
                "congressmanBudgets/currentSummaryLabel",
            congressmanBudgetsClosedAt: "congressmanBudgets/selectedClosedAt",
            getEntryState: "entries/getEntryState",
            selectedCongressmanBudgetState:
                "congressmanBudgets/getSelectedState",
            currentSummaryLabel: "entries/currentSummaryLabel",
            getActivityLog: "entries/activityLog"
        }),
        ...mapState(service.name, ["tableLoading"])
    }
};
</script>
