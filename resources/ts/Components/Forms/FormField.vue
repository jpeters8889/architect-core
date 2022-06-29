<template>
  <div class="flex flex-col space-y-2 py-2 lg:flex-row lg:space-y-0">
    <div class="flex flex-col space-y-2 lg:w-1/4 lg:max-width-[300px]">
      <label
        v-if="label"
        :for="id"
        class="text-gray-500 font-light tracking-wide text-sm lg:text-base"
        v-text="label"
      />

      <small
        v-if="helpText"
        class="text-xs font-gray-300 tracking-wide"
        v-text="helpText"
      />
    </div>

    <div class="lg:flex-1 lg:max-w-[900px]">
      <slot />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  props: {
    id: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: false,
      default: null,
    },
    helpText: {
      type: String,
      required: false,
      default: null,
    },
  },
});
</script>
