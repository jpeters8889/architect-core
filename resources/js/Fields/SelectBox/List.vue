<template>
  <div>{{ value }}</div>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
      type: [String, Number],
    },
  },
};
</script>
