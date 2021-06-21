<template>
    <div>
        <b-modal v-model="showModal" title="Novo documento" @shown="onShow()">
            <template>
                <app-dropzone
                    :url="uploadUrl"
                    :token="environment.token"
                ></app-dropzone>
            </template>

            <template slot="modal-footer">
                <button @click="close()" class="btn btn-success btn-sm" dusk="close">
                    Fechar
                </button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import crud from '../../views/mixins/crud'
import entries from '../../views/mixins/entries'

const service = {
    name: 'entryDocuments',

    uri:
        'congressmen/{congressmen.selected.id}/budgets/{congressmanBudgets.selected.id}/entries/{entries.selected.id}/documents',
}

export default {
    mixins: [crud, entries],

    props: ['show'],

    data() {
        return {
            busy: false,

            service: service,
        }
    },

    methods: {
        close() {
            this.showModal = false
        },

        closeModal() {
            this.showModal = false
        },

        onShow() {
            this.busy = false
        },
    },

    computed: {
        showModal: {
            get() {
                return this.show
            },

            set(showModal) {
                this.$emit('update:show', showModal)

                document.getElementById('dropzone').dropzone.removeAllFiles()
                document.getElementById('dropzone').dropzone.options[
                    'url'
                ] = this.uploadUrl
            },
        },

        uploadUrl() {
            return buildApiUrl(this.service.uri, this.$store.state)
        },
    },
}
</script>
