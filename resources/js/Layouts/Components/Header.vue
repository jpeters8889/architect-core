<template>
  <header class="bg-white w-full shadow-lg h-14 flex shadow-2xl">
    <div
      class="hidden xl:flex w-14 h-14 bg-gray-300 text-white justify-center items-center cursor-pointer text-gray-700"
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
