<template>
  <header class="bg-white w-full shadow-lg h10 flex">
    <div
      class="hidden xl:flex w-10 h-10 bg-gray-900 text-white justify-center items-center cursor-pointer"
      @click="toggleNavigation()"
    >
      <component
        :is="icon"
        class="w-8 h-8"
      />
    </div>

    <div class="p-2">
      Header
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid';

export default defineComponent({
  components: {
    ChevronLeftIcon, ChevronRightIcon,
  },

  props: {
    navDisplayed: {
      type: Boolean,
      required: true,
    },
  },

  emits: ['toggle-nav'],

  computed: {
    icon(): string {
      return this.navDisplayed ? 'ChevronLeftIcon' : 'ChevronRightIcon';
    },
  },

  methods: {
    toggleNavigation() {
      this.$emit('toggle-nav');
    },
  },
});
</script>
