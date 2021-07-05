/**
 * Vue & Vuex
 */
window.Vue = require('vue').default
window.Vuex = require('vuex')

/**
 * Vue The Mask
 */
Vue.use(() => import('vue-the-mask'))

// ClipLoader
Vue.component('clip-loader', require('vue-spinner/src/ClipLoader.vue').default)

// PulseLoader
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue').default)
/**
 * SweetAlert
 */
import VueSweetalert2 from 'vue-sweetalert2'

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css'

const options = {
    cancelButtonColor: '#E3352E',
    confirmButtonColor: '#38c172',
    confirmButtonText: 'confirmar',
    cancelButtonText: 'cancelar',
    showCancelButton: true,
}

Vue.use(VueSweetalert2, options)
// Loading ClipLoader
Vue.component('clip-loader', require('vue-spinner/src/ClipLoader.vue').default)

// Loading PulseLoader
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue').default)

/**
 * Vue Bootstrap
 */
// import { Modal } from 'bootstrap-vue/es/components'
// import { Button } from 'bootstrap-vue/es/components'
// import { Collapse } from 'bootstrap-vue/es/components'
// import { Form } from 'bootstrap-vue/es/components'
// import { FormGroup } from 'bootstrap-vue/es/components'
// import { FormInput } from 'bootstrap-vue/es/components'
// Vue.use(Modal)
// Vue.use(Button)
// Vue.use(Collapse)
// Vue.use(Form)
// Vue.use(FormGroup)
// Vue.use(FormInput)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

/**
 * Autoload Vue components
 */
const file = require.context('../components/app/', true, /\.vue$/i)
file.keys().map((file) => {
    const name = 'App' + _.last(file.split('/')).split('.')[0]

    return Vue.component(name, () => import('../components/app/' + basename(file)))
})

/**
 * VueSelect
 */
Vue.component('vue-select', () => import('vue-select'))
Vue.use(Vue.component('vue-select', () => import('vue-select')))

/**
 * Vue The Mask
 */
Vue.use(() => import('vue-the-mask'))
