<template>
  <component
    :is="as"
    class="px-4 py-2 rounded transition duration-200 flex leading-none"
    :class="[
      theme === 'normal' ? 'bg-gray-900 text-gray-100 hover:bg-sky-700' : '',
      theme === 'minor' ? 'bg-transparent text-gray-500 text-sm hover:text-gray-700 pb-1 pt-3 px-0' : '',
    ]"
    v-bind="{...(to ? {href: to} : null)}"
    @click="handleClick()"
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
    theme: {
      type: String,
      required: false,
      validator: (value: string) => ['normal', 'minor'].includes(value),
      default: () => 'normal',
    },
  },

  emits: ['click'],

  methods: {
    handleClick() {
      if (this.to) {
        return;
      }

      this.$emit('click');
    },
  },
});
</script>
