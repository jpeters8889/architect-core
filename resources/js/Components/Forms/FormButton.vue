<template>
  <component
    :is="as"
    class="bg-gray-600 px-4 py-1 rounded text-gray-100 transition duration-200 hover:bg-gray-700 flex"
    v-bind="{...(to ? {href: to} : null)}"
  >
    {{ label }}
  </component>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
  components: {
    Link,
  },

  props: {
    label: {
      required: true,
      type: String,
    },
    as: {
      type: String,
      required: false,
      validator: (value: string) => ['button', 'Link'].includes(value),
      default: () => 'button',
    },
    to: {
      type: String,
      required: false,
      default: undefined,
    },
  },
});
</script>
