<template>
  <div class="flex flex-col">
    <label
      v-if="label"
      :for="id"
      class="text-gray-500 font-light tracking-wide text-sm"
      v-text="label"
    />

    <small
      v-if="helpText"
      class="text-xs font-gray-300 tracking-wide"
      v-text="helpText"
    />

    <slot />
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
