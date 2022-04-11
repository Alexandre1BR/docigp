<template>
    <span>
        <b-button
            v-if="can('audits:show')"
            class="btn btn-sm btn-primary"
            @click="activityLog(row)"
            title="Logs"
            ><i class="fas fa-clipboard-list"></i
        ></b-button>

        <b-modal size="xl" v-model="showAuditsModal" ok-only title="Logs">
            <div class="table-responsive">
                <table
                    id="auditsTable"
                    class="table table-striped table-bordered"
                    cellspacing="0"
                    width="100%"
                >
                    <thead>
                        <tr>
                            <th>Data da ação</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Valores antigos</th>
                            <th>Valores novos</th>
                        </tr>
                    </thead>

                    <tr v-for="audit in audits">
                        <td>
                            {{ audit.formatted_created_at }}
                        </td>
                        <td>
                            {{ audit.user.name }}
                            <br />
                            {{ audit.user.email }}
                        </td>
                        <td>
                            {{ audit.activity }}
                        </td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li
                                    v-for="(value, key) in audit.old_values"
                                    class="list-group-item"
                                >
                                    {{ t(key) }} => {{ value }}
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-group list-group-flush">
                                <li
                                    v-for="(value, key) in audit.new_values"
                                    class="list-group-item"
                                >
                                    {{ t(key) }} => {{ value }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </b-modal>
    </span>
</template>

<script>
import permissions from '../../views/mixins/permissions'

export default {
    mixins: [permissions],

    name: 'AuditsButton',
    props: {
        model: String,
        row: Object,
    },

    data() {
        return {
            showAuditsModal: false,
            audits: [],
        }
    },

    methods: {
        activityLog(entity) {
            const $this = this
            this.showAuditsModal = true

            this.$store.getters[this.model + '/activityLog'](entity).then((response) => {
                $this.audits = response.data
            })
        },
    },
}
</script>
