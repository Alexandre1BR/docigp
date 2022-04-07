import * as gettersMixin from './mixins/getters.js'

const __emptyTable = {
    filter: {
        text: null,
    },

    links: {
        pagination: {
            per_page: null,

            current_page: null,
        },
    },

    rows: {},
}

const state = {
    loaded: false,
    tables: {
        contact_types: __emptyTable,
    },
}

let getters = merge_objects(gettersMixin, {
    getAbilities(state, getters) {
        return state.user ? state.user.abilities : []
    },

    getCurrentClient(state, getters) {
        return state.session.current_client
    },

    getuser(state, getters) {
        return state.user
    },
})

const actions = {
    load(context) {
        return get('/api/v1/environment').then((response) => {
            context.commit('mutateSetData', response.data)
        })
    },

    loadContactTypes(context) {
        return get('/api/v1/contact-types', {
            params: { query: context.getters.getFullQueryFilter },
        }).then((response) => {
            context.commit('mutateSetContactTypes', response.data)
        })
    },

    boot(context) {
        context.commit('mutateSetData', window.laravel)

        context.dispatch('load')

        if (context.state.user != null) {
            /// load tables
        }

        context.dispatch('subscribeToChannels')
    },

    subscribeToChannels(context) {
        const $this = this

        if (context.state.user != null) {
            subscribePublicChannel(
                'token.'+context.state.token,
                '.App\\Events\\SessionExpired',
                (event) => {
                    Swal.fire({
                        title: 'Sessão expirada',
                        text: "Por conta da inatividade, é necessário realizar login novamente para voltar a usar o sistema.",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#38c172',
                        confirmButtonText: 'OK',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href='/login'
                        }
                    })
                },
            )
        }
    },
}

const mutations = {
    mutateSetData(state, payload) {
        state['loaded'] = false

        _.forIn(payload, (val, key) => {
            state[key] = val
        })

        state['loaded'] = true
    },

    mutateSetContactTypes(state, payload) {
        state['tables']['contact_types'] = payload
    },
}

export default {
    state,
    getters,
    actions,
    mutations,
    namespaced: true,
}
