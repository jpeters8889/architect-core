<template>
  <div
    :class="wrapperClasses"
    @click="toggle = !toggle"
    @mouseleave="toggle = false"
  >
    <slot name="icon" />

    <div
      v-if="toggle"
      class="absolute right-[-200%] w-20 bg-gray-700"
    >
      <span>Hello</span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  data: () => ({
    toggle: false,
  }),

  computed: {
    wrapperClasses(): string[] {
      return [
        'bg-gray-800',
        'rounded',
        'w-10',
        'h-10',
        'border',
        'border-gray-800',
        'hover:bg-gray-700',
        'flex',
        'items-center',
        'justify-center',
        'text-grey-400',
        'hover:text-gray-200',
        'transition',
        'relative',
      ];
    },
  },
});
</script>
