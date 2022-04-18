<template>
    <div>
        <div
            v-for="(item, key) in rows"
            :class="getColor(item) + ' rounded-' + setRounded(key)"
            style="font-size: 9.6px; font-weight: 700"
        >
            &bull;
            {{ item.title }}
            {{ item.labels[!item.value ? 1 : 0] }}
        </div>
    </div>
</template>

<script>
export default {
    props: ['rows'],

    methods: {
        getColor(row) {
            if (!row.color) {
                return !row.value ? 'badge-danger' : 'badge-success'
            }

            return 'badge-' + row.color
        },
        setRounded(key) {
            if (key == 0) {
                return 'top'
            } else if (key == this.rows.length - 1) {
                return 'bottom'
            } else {
                return '0'
            }
        },
    },
}
</script>
