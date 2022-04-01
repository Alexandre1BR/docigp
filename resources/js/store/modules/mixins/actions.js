let debouncedByUrl = {}

export function load(context) {
    const url = makeDataUrl(context)

    if (url) {
        let urlHash = hash(url + JSON.stringify(context.getters.getQueryFilter))

        if (typeof debouncedByUrl[urlHash] === 'undefined') {
            debouncedByUrl[urlHash] = _.debounce((targetUrl, targetContext) => {
                //console.log(targetUrl)
                get(targetUrl, {
                    params: { query: targetContext.getters.getQueryFilter },
                }).then((response) => {
                    context.commit('mutateTableLoading', false)
                    context.dispatch('setDataAfterLoad', response.data)
                    //console.log(targetContext.getters.getQueryFilter)
                })
            }, 450)
        }

        return debouncedByUrl[urlHash](url, context)
    }
}

export function setShowComponent(context, payload) {
    console.log(context.state.data.links.pagination.current_page)
    console.log(context.state.data.links.pagination.per_page)
    console.log(context.state.data.links.pagination.total)

    context.commit('entryDocuments/mutateForcedUpdate', false, { root: true })
    context.commit('entryComments/mutateForcedUpdate', false, { root: true })
}
export function setDataAfterDelete(context, payload) {
    if (
        context.state.data.links.pagination.total % context.state.data.links.pagination.per_page ==
        1
    ) {
        context.state.data.links.pagination.current_page -= 1
    }
}
export function setDataAfterLoad(context, payload) {
    payload.filter.text = context.state.data.filter.text
    context.commit('mutateSetData', payload)
}

export function save(context, payload) {
    const url =
        payload === 'create'
            ? makeStoreUrl(context)
            : makeUpdateUrl(context) + '/' + context.state.form.fields.id

    return context.state.form.post(url, context.state.form.fields)
}

export function clearForm(context) {
    context.state.form.fields = clone(context.state.emptyForm)
    context.state.form.errors.clear()
}

export function clearErrors(context) {
    context.state.form.errors.clear()
}

export function mutateSetQueryFilterText(context, payload) {
    let data = context.state.data

    data.filter.text = payload

    data.links.pagination.current_page = 1

    context.commit('mutateSetData', data)

    loadDebounced(context)
}

export function setCurrentPage(context, payload) {
    let data = context.state.data

    data.links.pagination.current_page = payload

    context.commit('mutateSetData', data)

    context.dispatch('load')
}

export function setPerPage(context, payload) {
    context.commit('mutateSetPerPage', payload)

    context.state.data.links.pagination.current_page = 1

    context.dispatch('load')

    context.dispatch('updateUserPerPage', payload)
}

export function updateUserPerPage(context, payload) {
    post('/api/v1/users/per-page/' + payload)
}

export function select(context, payload) {
    context.dispatch('subscribeToModelEvents', payload)

    context.commit('mutateSetSelected', payload)

    context.commit('mutateFormData', payload)
}

export function mutateFilterCheckbox(context, payload) {
    context.commit('mutateFilterCheckbox', payload)

    loadDebounced(context)
}

export function mutateFilterSelect(context, payload) {
    context.commit('mutateFilterSelect', payload)

    loadDebounced(context)
}

export function leaveModelChannel(context, payload) {
    if (context.state.selected.id) {
        leavePublicChannel(context.state.model.table + '.' + context.state.selected.id)
    }
}

export function subscribeExtraChannels(context, payload = null) {}
