<template>
  <component
    :is="component"
    :value="value"
  />
</template>

<script>
export default {
  props: {
    component: {
      required: true,
      type: String,
    },
    value: {
      required: true,
      type: [String, Number, Array, Object],
    },
  },

  created() {
    // eslint-disable-next-line global-require,import/no-dynamic-require
    this.$options.components = { [this.component]: require(`./${this.component}/List.vue`).default };
  },
};
</script>
