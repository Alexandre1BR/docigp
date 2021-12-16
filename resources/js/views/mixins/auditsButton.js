import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            showAuditsModal: false,
            audits: []
        }
    },

    methods: {
        activityLog(entity){
            dd(this.service.name)

            this.showAuditsModal = true

            this.audits = this.getActivityLog(entity).then(response => {
                $this.audits = response
            })
        }
    },


}
