<template>
  <div class="flex py-1 px-3 rounded-full border border-gray-300 items-center w-full max-w-[300px]">
    <input
      v-model="searchTerm"
      class="outline-0 ring-0 text-sm flex-1"
      type="search"
      placeholder="Search..."
      @keyup="searchKeypress()"
    >

    <MagnifyingGlassIcon class="w-4 h-4" />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import { } from 'timers';

export default defineComponent({
  components: { MagnifyingGlassIcon },

  props: {
    currentSearch: {
      type: String,
      required: true,
    },
  },

  emits: ['search-executed'],

  data: (): { [T: string]: any, timeout?: ReturnType<typeof setTimeout> } => ({
    searchTerm: '',
    timeout: undefined,
  }),

  watch: {
    currentSearch() {
      this.searchTerm = this.currentSearch;
    },
  },

  mounted() {
    this.searchTerm = this.currentSearch;
  },

  methods: {
    searchKeypress() {
      if (this.timeout) {
        clearTimeout(this.timeout);
      }

      this.timeout = setTimeout(() => {
        this.$emit('search-executed', this.searchTerm);
      }, 300);
    },
  },
});
</script>
