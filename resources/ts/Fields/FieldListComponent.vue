<template>
  <component
    :is="component"
    :value="value"
  />
</template>

<script>
import ListComponentRegistrar from './ListComponentRegistrar';

export default {
  components: ListComponentRegistrar,

  props: {
    component: {
      required: true,
      type: String,
    },
    value: {
      required: true,
      type: [String, Number, Boolean, Array, Object],
    },
  },
};
</script>
