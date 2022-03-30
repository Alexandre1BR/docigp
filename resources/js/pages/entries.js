import router from '../routes'
import { mapActions, mapState } from 'vuex'

const appName = 'vue-entries'

if (document.getElementById(appName) !== null) {
    new Vue({
        el: '#' + appName,

        store: window.Store,

        router,

        data: {},

        methods: {
            ...mapActions('environment', ['load']),
        },

        computed: {
            ...mapState({
                environment: (state) => state.environment,
            }),
        },

        mounted() {
            this.$translate.setLang('pt_BR')
        },
    })
}
