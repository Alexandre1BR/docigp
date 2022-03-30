export default {
    data: () => {
        return {
            loading: {
                environment: false,

                table: false,
            },
        };
    },

    computed: {
        filterText: {
            get() {
                return this.$store.state[this.service.name].data.filter.text;
            },

            set(payload) {
                this.$store.commit(this.service.name + '/mutateTableLoading', true);
                return this.$store.dispatch(
                    this.service.name + '/mutateSetQueryFilterText',
                    payload,
                );
            },
        },

        emptyForm() {
            return this.$store.state[this.service.name].emptyForm;
        },

        form() {
            return this.$store.state[this.service.name].form;
        },

        selected() {
            return this.$store.state[this.service.name].selected;
        },

        environment() {
            return this.$store.state['environment'];
        },

        pagination() {
            return this.$store.state[this.service.name].data.links
                ? this.$store.state[this.service.name].data.links.pagination
                : {};
        },

        perPage: {
            get() {
                return this.pagination.per_page;
            },

            set(value) {
                this.$store.commit(this.service.name + '/mutateTableLoading', true);
                this.$store.dispatch(this.service.name + '/setPerPage', value);
            },
        },
    },

    methods: {
        getPerPage() {
            return this.environment.user ? this.environment.user.per_page : 10;
        },

        load() {
            this.$store.commit(this.service.name + '/mutateSetPerPage', this.getPerPage());

            return this.$store.dispatch(this.service.name + '/load');
        },

        select(model) {
            return this.$store.dispatch(this.service.name + '/select', model);
        },

        save(mode) {
            return this.$store.dispatch(this.service.name + '/save', mode);
        },

        mutateSetErrors(errors) {
            this.$store.commit(this.service.name + '/mutateSetErrors', errors);
        },

        mutateFormData(data) {
            this.$store.commit(this.service.name + '/mutateFormData', data);
        },

        mutateSetFormField(data) {
            this.$store.commit(this.service.name + '/mutateSetFormField', data);
        },

        mutateSetService(data) {
            this.$store.commit(this.service.name + '/mutateSetService', data);
        },

        isLoading() {
            return this.loading.environment || this.loading.table;
        },

        boot() {
            this.mutateSetService(this.service);

            if (!this.service.hasOwnProperty('performLoad') || this.service.performLoad) {
                this.load().then(() => {
                    this.fillFormWhenEditing();
                });
            } else {
                this.fillFormWhenEditing();
            }

            //this.$store.dispatch(this.service.name + '/subscribeToTableEvents')

            if (this.onBoot) {
                this.onBoot();
            }
        },

        fillFormWhenEditing() {
            if (this.mode === 'update' || this.mode === 'create') {
                const model =
                    this.mode === 'update'
                        ? _.find(this.rows, (model) => {
                              return model.id === this.$route.params.id;
                          })
                        : this.form
                        ? clone(this.emptyForm)
                        : {};

                this.mutateFormData(model);

                this.mutateSetErrors({});

                this.fillAdditionalFormFields();
            }
        },

        fillAdditionalFormFields() {},

        back() {
            this.$router.back();
        },

        saveModel(onResolved = null) {
            this.clearErrors();

            return this.save(
                this.mode ? this.mode : this.form.fields.id === null ? 'create' : 'update',
            )
                .then(() => {
                    // this.back()

                    this.clearForm();
                    this.clearErrors();

                    onResolved && onResolved();
                })
                .catch((response) => {
                    dd('catch response', response);
                });
        },

        gotoPage(page, namespace = null, pagination = null) {
            pagination = pagination ? pagination : this.pagination;

            if (pagination.current_page === page) {
                return;
            }

            if (page < 1) {
                return;
            }

            if (page > pagination.last_page) {
                return;
            }

            this.$store.commit(this.service.name + '/mutateTableLoading', true);

            this.$store.dispatch(
                (namespace ? namespace : this.service.name) + '/setCurrentPage',
                page,
            );
        },

        isCurrent(model, selected) {
            return model.id === selected.id;
        },

        setPerPage() {
            this.$store.commit(
                this.service.name + '/mutateSetPerPage',
                this.environment.user.per_page,
            );
        },

        getDataUrl() {
            return buildApiUrl(this.service.uri, this.$store.state);
        },

        getStoreUrl() {
            return buildApiUrl(this.service.uri, this.$store.state);
        },

        getUpdateStoreUrl() {
            return buildApiUrl(this.service.uri, this.$store.state);
        },

        getColor(color, position) {
            return get_color(color, position);
        },
    },

    mounted() {
        this.boot();
    },
};
