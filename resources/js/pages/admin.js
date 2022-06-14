import router from '../routes'
import { mapActions, mapState } from 'vuex'

const appName = 'vue-admin'

if (document.getElementById(appName) !== null) {
    new Vue({
        el: '#' + appName,

        store: window.Store,

        router,

        data: {},

        methods: {
            ...mapActions('environment', ['load', 'selectEntity', 'selectLanguage']),
        },

        computed: {
            ...mapState({
                environment: (state) => state.environment,
            }),
        },
    })
}
