import { mapState } from 'vuex'

const appName = 'vue-basic'

if (jQuery('#' + appName).length > 0) {
    new Vue({
        el: '#' + appName,

        computed: {
            ...mapState({
                environment: (state) => window.Store.state.environment,
            }),
        },
    })
}
