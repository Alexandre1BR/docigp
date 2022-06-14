export const common = {
    tableLoading: true,

    service: { performLoad: true },

    data: {
        filter: {
            text: null,
            checkboxes: {
                filler: false,
            },

            selects: {
                filler: false,
            },
        },

        links: {
            pagination: {
                per_page: 5,

                current_page: 1,
            },
        },

        order: {},
    },

    selected: { id: null },
}
