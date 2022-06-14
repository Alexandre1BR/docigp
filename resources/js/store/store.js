/**
 * Imports
 */
import Vue from 'vue'
import Vuex from 'vuex'

/**
 * Vuex
 */
Vue.use(Vuex)

/**
 * Global state
 */
import * as actions from './actions'
import * as getters from './getters'
import * as mutations from './mutations'

/**
 * Modules
 */
import admin from './modules/admin'
import gate from './modules/gate'
import entries from './modules/entries'
import budgets from './modules/budgets'
import entryTypes from './modules/entryTypes'
import environment from './modules/environment'
import congressmen from './modules/congressmen'
import costCenters from './modules/costCenters'
import entryDocuments from './modules/entryDocuments'
import entryComments from './modules/entryComments'
import congressmanBudgets from './modules/congressmanBudgets'

/**
 * State
 */
const state = {
    mounted: false,
}

/**
 * Store
 */
let store = new Vuex.Store({
    state,
    actions,
    getters,
    mutations,
    modules: {
        admin,
        environment,
        congressmen,
        budgets,
        congressmanBudgets,
        entries,
        entryDocuments,
        entryComments,
        costCenters,
        gate,
        entryTypes,
    },
})

store.dispatch('environment/boot')

window.Store = store
