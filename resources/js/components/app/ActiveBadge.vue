<template>
    <div>
        <span class="badge" :class="getColor()">
            {{title}} 
            {{ labels[!value ? 1 : 0] }}
        </span>
    </div>
</template>

<script>
export default {
    props: ['value', 'labels', 'color', 'title'],

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
