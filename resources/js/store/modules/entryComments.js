import Form from '../../classes/Form'

import * as mutationsMixin from './mixins/mutations.js'
import * as actionsMixin from './mixins/actions.js'
import * as statesMixin from './mixins/states.js'
import * as gettersMixin from './mixins/getters.js'

const __emptyModel = {
    id: null,
    text: null,
}

let state = merge_objects(
    {
        selectedRole: __emptyModel,

        form: new Form(clone(__emptyModel)),

        emptyForm: clone(__emptyModel),

        mode: null,

        model: {
            name: 'entryComments',

            table: 'entry_comments',

            class: { singular: 'EntryComnent', plural: 'EntryComnents' },
        },
    },

    statesMixin.common,
)

let actions = merge_objects(actionsMixin, {
    subscribeToModelEvents(context, payload) {
        //Doesn't listen to anything
    },

    selectEntryComment(context, payload) {
        context.dispatch('entryComments/select', payload, { root: true })
    },

    delete(context, payload) {
        return post(makeDataUrl(context) + '/' + payload.id + '/delete')
    },
})

let mutations = mutationsMixin
let getters = gettersMixin

export default {
    state,
    actions,
    mutations,
    getters,
    namespaced: true,
}
