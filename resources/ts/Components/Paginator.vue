<template>
  <div class="w-full flex justify-between items-center pr-2 sm:px-2 xl:pb-3 xl:px-4">
    <div class="hidden sm:block">
      Showing items
      <strong>{{ startItem }}</strong> -
      <strong>{{ endItem }}</strong>
      of <strong>{{ totalItems }}</strong>
    </div>

    <div class="flex space-x-2 items-center justify-between w-full sm:w-auto">
      <div>
        <PaginatorSelectToggle
          :current-page="currentPage"
          :number-of-pages="numberOfPages"
          @page-change="(page: number) => goTo(page)"
        />
      </div>

      <div class="flex space-x-2 items-center">
        <button
          class="rounded-full transition  block flex items-center justify-center w-8 h-8 transition"
          :class="canGoBack ? 'bg-gray-300 text-gray-900 cursor-pointer hover:bg-sky-700 hover:text-gray-100' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
          @click.prevent="goTo(currentPage - 1)"
        >
          <ChevronLeftIcon class="w-4 h-4" />
        </button>

        <button
          class="rounded-full transition  block flex items-center justify-center w-8 h-8 transition"
          :class="canGoForward ? 'bg-gray-300 text-gray-900 cursor-pointer hover:bg-sky-700 hover:text-gray-100' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
          @click.prevent="goTo(currentPage + 1)"
        >
          <ChevronRightIcon class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
import { SelectBoxOption } from '../types';
import PaginatorSelectToggle from './Paginator/PaginatorSelectToggle.vue';

export default defineComponent({
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
    PaginatorSelectToggle,
  },

  props: {
    currentPage: {
      required: true,
      type: Number,
    },
    numberOfPages: {
      required: true,
      type: Number,
    },
    startItem: {
      required: true,
      type: Number,
    },
    endItem: {
      required: true,
      type: Number,
    },
    totalItems: {
      required: true,
      type: Number,
    },
  },

  emits: ['goToPage'],

  computed: {
    pageArray(): SelectBoxOption[] {
      return Array.from(Array(this.numberOfPages)).map((a, index: number) => ({
        key: index + 1,
        value: `Page ${(index + 1).toString()} of ${this.numberOfPages}`,
      }));
    },

    canGoBack(): boolean {
      return this.currentPage > 1;
    },

    canGoForward(): boolean {
      return this.currentPage < this.numberOfPages;
    },
  },

  methods: {
    goTo(page: number) {
      if (page === this.currentPage) {
        return;
      }

      this.$emit('goToPage', page);
    },
  },
});
</script>
