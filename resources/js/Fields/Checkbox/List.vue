<template>
  <div
    class="px-3 py-1 rounded-full inline-block font-semibold text-xs mx-auto"
    :class="value === 'Yes' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900'"
  >
    {{ value }}
  </div>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
      type: [String],
    },
  },
};
</script>
