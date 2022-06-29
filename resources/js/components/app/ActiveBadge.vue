<template>
  <div :class="'p-1' + classes">
    <span class="badge text-uppercase" :class="getColor()">
      {{ title }}
      {{ labels[!value ? 1 : 0] }}
    </span>
  </div>
</template>

<script>
export default {
  props: ['value', 'labels', 'color', 'title', 'classes'],

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
