<template>
    <app-table-panel
        :title="'Deputados (' + pagination.total + ')'"
        titleCollapsed="Deputado / Deputada"
        :per-page="perPage"
        :filter-text="filterText"
        @input-filter-text="filterText = $event.target.value"
        @set-per-page="perPage = $event"
        :collapsedLabel="selected.name"
        :is-selected="selected.id !== null"
    >
        <template slot="checkboxes">
            <div class="row">
                <div v-if="can('congressman:show')" class="col">
                    <app-input name="joined" label="aderiu" type="checkbox" v-model="joined" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="notJoined" label="não aderiu" type="checkbox" v-model="notJoined" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="withMandate" label="com mandato" type="checkbox" v-model="withMandate" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="withoutMandate" label="sem mandato" type="checkbox" v-model="withoutMandate" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="withPendency" label="com pendências" type="checkbox" v-model="withPendency" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="withoutPendency" label="sem pendências" type="checkbox" v-model="withoutPendency" :required="true" :form="form" inline="true"></app-input>
                </div>

                <div v-if="can('congressman:show')" class="col">
                    <app-input name="unread" label="não lidos" type="checkbox" v-model="unread" :required="true" :form="form" inline="true"></app-input>
                </div>
            </div>
        </template>

        <app-table :pagination="pagination" @goto-page="gotoPage($event)" :columns="getTableColumns()">
            <tr
                @click="selectCongressman(congressman)"
                v-for="congressman in congressmen.data.rows"
                :class="{
                    'cursor-pointer': true,
                    'bg-primary-lighter text-white': isCurrent(congressman, selected),
                }"
                :dusk="'congressman-' + congressman.id"
            >
                <td v-if="can('congressman:see-unread')" class="align-middle text-center">
                    <span class="text-danger">
                        <i v-if="congressman.unread" class="fa fa-dot-circle"></i>
                    </span>

                    <span class="text-gray-light">
                        <i v-if="!congressman.unread" class="fa fa-circle"></i>
                    </span>
                </td>

                <td class="align-middle">
                    <div>
                        {{ congressman.nickname }}
                    </div>

                    <div class="mb-2" style="line-height: 3px; " v-if="congressman.name !== congressman.nickname">
                        <small class="text-primary" style="font-size: 0.6em;">({{ congressman.name }})</small>
                    </div>
                </td>

                <td class="align-middle text-center">
                    <app-active-badge :value="congressman.is_published" :labels="['sim', 'não']"></app-active-badge>
                </td>

                <td v-if="can('congressman:show')" class="align-middle text-center">
                    <app-active-badge :value="!congressman.has_pendency" :labels="['não', 'sim']"></app-active-badge>
                </td>

                <td v-if="can('congressman:show')" class="align-middle text-center">
                    <app-active-badge :value="congressman.has_mandate" :labels="['com mandato', 'sem mandato ']"></app-active-badge>
                </td>
            </tr>
        </app-table>
    </app-table-panel>
</template>

<script>
import crud from '../../views/mixins/crud'
import congressmen from '../../views/mixins/congressmen'
import permissions from '../../views/mixins/permissions'
import { mapActions } from 'vuex'

const service = { name: 'congressmen', uri: 'congressmen' }

export default {
    mixins: [crud, congressmen, permissions],

    data() {
        return {
            service: { name: 'congressmen', uri: 'congressmen' },
        }
    },

    methods: {
        ...mapActions(service.name, ['selectCongressman']),

        getTableColumns() {
            let columns = []

            if (can('congressman:see-unread')) {
                columns.push('')
            }

            columns.push('Nome do Parlamentar')

            columns.push({
                type: 'label',
                title: 'Aderiu?',
                trClass: 'text-center',
            })

            if (can('congressman:show')) {
                columns.push({
                    type: 'label',
                    title: 'Pendências',
                    trClass: 'text-center',
                })

                columns.push({
                    type: 'label',
                    title: 'Situação',
                    trClass: 'text-center',
                })
            }

            return columns
        },
    },

    computed: {
        withMandate: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.withMandate
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withMandate',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withoutMandate',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        withoutMandate: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.withoutMandate
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withoutMandate',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withMandate',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        withPendency: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.withPendency
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withPendency',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withoutPendency',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        withoutPendency: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.withoutPendency
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withoutPendency',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'withPendency',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        unread: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.unread
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'unread',
                    value: filter,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        joined: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.joined
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'joined',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'notJoined',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        notJoined: {
            get() {
                return this.$store.state['congressmen'].data.filter.checkboxes.notJoined
            },

            set(filter) {
                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'notJoined',
                    value: filter,
                })

                this.$store.commit('congressmen/mutateFilterCheckbox', {
                    field: 'joined',
                    value: false,
                })

                this.$store.dispatch('congressmen/load')
            },
        },

        onBoot() {
            subscribePublicChannel('congressmen', '.App\\Events\\' + 'CongressmenChanged', event => {
                // console.log(event)
                //
                // console.log(
                //     'Received event and need to update congressmen table',
                // )

                this.$store.dispatch('congressmen/load')
            })
        },
    },
}
</script>
