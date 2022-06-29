<template>
  <div :class="value ? 'text-green-500' : 'text-red-500'">
    <component
      :is="value ? 'CheckCircleIcon' : 'XCircleIcon'"
      class="h-5 w-5"
    />
  </div>
</template>

<script>
import { CheckCircleIcon, XCircleIcon } from '@heroicons/vue/outline';

export default {
  components: { CheckCircleIcon, XCircleIcon },

  props: {
    value: {
      required: true,
      type: Boolean,
    },
  },
};
</script>
