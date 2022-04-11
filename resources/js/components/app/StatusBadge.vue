<template>
    <div>
        <span class="badge d-block rounded-0" :class="getColor()">
            {{ labels[!value ? 1 : 0] }}
        </span>
    </div>
</template>

<script>
export default {
    props: ['value', 'labels', 'color'],

    methods: {
        getColor() {
            if (!this.color) {
                return !this.value ? 'badge-danger' : 'badge-success'
            }

            return 'badge-' + this.color
        },
    },
}
</script>
