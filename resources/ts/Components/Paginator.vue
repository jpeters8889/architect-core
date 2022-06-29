<template>
  <ul
    v-if="numberOfPages > 1"
    class="flex flex-wrap font-semibold leading-none justify-center gap-1 text-xs"
  >
    <li
      v-if="canGoBack"
      class="border border-gray-300 bg-gray-300 text-gray-900 rounded cursor-pointer transition hover:bg-white"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(currentPage - 1)"
      >
        Previous
      </a>
    </li>

    <li
      v-for="page in pageArray"
      :key="page.label"
      class="border border-gray-300 rounded cursor-pointer transition"
      :class="page.goTo !== currentPage ? 'bg-gray-300 text-gray-900 hover:bg-white' : 'bg-white'"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(page.goTo)"
      >
        {{ page.label }}
      </a>
    </li>

    <li
      v-if="canGoForward"
      class="border border-gray-300 bg-gray-300 text-gray-900 rounded cursor-pointer transition hover:bg-white"
    >
      <a
        class="p-2 block"
        @click.prevent="goTo(currentPage + 1)"
      >
        Next
      </a>
    </li>
  </ul>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { PaginationData } from '../types';

export default defineComponent({
  props: {
    currentPage: {
      required: true,
      type: Number,
    },
    numberOfPages: {
      required: true,
      type: Number,
    },
  },

  emits: ['goToPage'],

  computed: {
    canGoBack(): boolean {
      return this.currentPage > 1;
    },

    canGoForward(): boolean {
      return this.currentPage < this.numberOfPages;
    },

    paginationGroups(): number[][] {
      const numberOfGroups = [...Array(Math.ceil(this.numberOfPages / 5))];
      const pagesInGroup = [...Array(5)];

      return numberOfGroups.map((gItem, group) => pagesInGroup.map((pItem, page) => page + (group * 5) + 1));
    },

    pageArray(): PaginationData {
      const data: PaginationData = [{ label: '1', goTo: 1 }];

      const groups: number[][] = this.paginationGroups;

      if (this.currentPage > 5) {
        data.push({ label: '...', goTo: this.currentPage - 5 });
      }

      const currentGroup = groups.findIndex((page) => page.indexOf(this.currentPage) !== -1);

      groups[currentGroup].forEach((page) => {
        if (page > 1 && page < this.numberOfPages) {
          data.push({ label: page.toString(), goTo: page });
        }
      });

      if (currentGroup + 1 < groups.length) {
        data.push({ label: '...', goTo: groups[currentGroup + 1][0] });
      }

      data.push({ label: this.numberOfPages.toString(), goTo: this.numberOfPages });

      return data;
    },
  },

  methods: {
    goTo(page: number) {
      this.$emit('goToPage', page);
    },
  },
});
</script>
