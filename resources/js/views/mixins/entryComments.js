import { mapState } from 'vuex'

export default {
    computed: {
        ...mapState({
            entryComments: state => state.entryComments,
        }),
    },
}
