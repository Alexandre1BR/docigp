import Form from '../../classes/Form';

import * as mutationsMixin from './mixins/mutations.js';
import * as actionsMixin from './mixins/actions.js';
import * as statesMixin from './mixins/states.js';
import * as gettersMixin from './mixins/getters.js';

const __emptyModel = {
    id: null,
    name: null,
    description: null,
};

let state = merge_objects(
    {
        selectedRole: __emptyModel,

        form: new Form(clone(__emptyModel)),

        emptyForm: clone(__emptyModel),

        mode: null,

        data: {
            filter: {
                text: null,

                checkboxes: {
                    withMandate: false,
                    withoutMandate: false,
                    withPendency: false,
                    withoutPendency: false,
                    unread: false,
                    joined: laravel.env.default_joined_checkbox,
                    notJoined: false,
                },

                selects: {
                    filler: false,
                },
            },

            links: {
                pagination: {
                    per_page: 5,

                    current_page: 1,
                },
            },

            order: {},
        },

        model: {
            name: 'congressman',

            table: 'congressmen',

            class: { singular: 'Congressman', plural: 'Congressmen' },
        },
    },

    statesMixin.common,
);

let actions = merge_objects(actionsMixin, {
    subscribeToModelEvents(context, payload) {
        context.dispatch('leaveModelChannel', payload);

        context.dispatch('entries/leaveModelChannel', payload, {
            root: true,
        });

        context.dispatch('congressmanBudgets/leaveModelChannel', payload, {
            root: true,
        });

        if (context.state.model) {
            subscribePublicChannel(
                'congressmen.' + payload.id,
                '.App\\Events\\' + 'CongressmanBudgetsChanged',
                (event) => {
                    context.dispatch('congressmanBudgets/load', payload, {
                        root: true,
                    });
                },
            );
        }
    },

    selectCongressman(context, payload) {
        const performLoad = !context.state.selected || context.state.selected.id != payload.id;

        context.dispatch('congressmen/select', payload, { root: true });

        if (performLoad) {
            context.commit('congressmanBudgets/mutateTableLoading', true, { root: true });

            context.dispatch('congressmanBudgets/setCurrentPage', 1, {
                root: true,
            });

            context.commit('congressmanBudgets/mutateSetSelected', { id: null }, { root: true });

            context.commit('entries/mutateSetSelected', { id: null }, { root: true });

            context.commit('entryDocuments/mutateSetSelected', { id: null }, { root: true });

            context.commit('entryComments/mutateSetSelected', { id: null }, { root: true });
        }
    },

    markAsRead(context) {
        if (!!Store.state.environment.user) {
            post(
                '/api/v1/congressmen/' +
                    context.rootState.congressmen.selected.id +
                    '/mark-as-read',
            );
        }
    },
});

let mutations = mutationsMixin;
let getters = gettersMixin;

export default {
    state,
    actions,
    mutations,
    getters,
    namespaced: true,
};
