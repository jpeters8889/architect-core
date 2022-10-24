<template>
  <div class="flex flex-col items-center space-y-[-6px]">
    <ChevronUpIcon
      class="w-4 h-4"
      :class="column === currentSort.column && currentSort.direction === 'asc' ? 'fill-blue-500' : ''"
      @click="sort('asc')"
    />
    <ChevronDownIcon
      class="w-4 h-4"
      :class="column === currentSort.column && currentSort.direction === 'desc' ? 'fill-blue-500' : 'cursor-pointer'"
      @click="sort('desc')"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/outline';
import { BlueprintTableSortableDataSet } from '../types';

export default defineComponent({
  components: { ChevronUpIcon, ChevronDownIcon },

  props: {
    currentSort: {
      required: true,
      type: Object as () => BlueprintTableSortableDataSet,
    },
    column: {
      required: true,
      type: String,
    },
  },

  emits: ['sortChange'],

  methods: {
    sort(direction: 'asc' | 'desc') {
      this.$emit('sortChange', { column: this.column, direction });
    },
  },
});
</script>
