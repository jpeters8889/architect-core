<template>
  <FormButton
    label="Filters"
    @click="sidebarOpen = true"
  />
  <component
    :is="Teleport"
    to="body"
  >
    <SidebarModal
      :show="sidebarOpen"
      side="right"
      @close="sidebarOpen = false"
    >
      <ul class="p-4 flex flex-col space-y-5">
        <li
          v-for="filter in filters"
          :key="filter.key"
        >
          <div
            class="text-gray-100 cursor-pointer select-none transition transition-all"
            :class="filterIsOpen(filter.key) ? 'text-gray-500 text-sm' : 'text-gray-100 text-base'"
            @click="toggleFilter(filter.key)"
          >
            <div class="flex justify-between items-center">
              <span>{{ filter.label }}</span>
              <ChevronDownIcon
                v-if="!filterIsOpen(filter.key)"
                class="w-4 h-4"
              />
              <ChevronUpIcon
                v-if="filterIsOpen(filter.key)"
                class="w-4 h-4"
              />
            </div>

            <div
              v-if="filterHasSelection(filter.key) && !filterIsOpen(filter.key)"
              class="text-xs text-gray-500 text-opacity-80"
            >
              {{ selectedOptionsForFilter(filter.key, []).join(', ') }}
            </div>
          </div>

          <ul
            v-if="filterIsOpen(filter.key)"
            class="flex flex-col space-y-2 mt-2 p-2"
          >
            <li
              v-for="option in filter.options"
              :key="option"
              class="flex items-center p-2 transition rounded-lg cursor-pointer justify-between text-gray-200"
              :class="optionIsSelected(filter.key, option) ? 'bg-sky-900' : 'hover:bg-sky-700'"
              @click="toggleFilterOption(filter.key, option)"
            >
              <span class="block flex-1">
                {{ option }}
              </span>

              <CheckIcon
                v-if="optionIsSelected(filter.key, option)"
                class="w-4 h-4"
              />
            </li>
          </ul>
        </li>
      </ul>
    </SidebarModal>
  </component>
</template>

<script lang="ts">
import {
  defineComponent,
  Teleport as teleport_,
  TeleportProps,
  VNodeProps,
} from 'vue';
import { CheckIcon, ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/outline';
import { BlueprintFilter, BlueprintFilterSetting } from '../../types';
import FormButton from '../Forms/FormButton.vue';
import SidebarModal from '../SidebarModal.vue';

export default defineComponent({
  components: {
    SidebarModal, FormButton, ChevronDownIcon, ChevronUpIcon, CheckIcon,
  },

  props: {
    buttonLabel: {
      required: false,
      type: String,
      default: 'Filters',
    },
    filters: {
      required: true,
      type: Array as () => BlueprintFilterSetting[],
    },
    currentFilters: {
      required: false,
      type: Array as () => BlueprintFilter[],
      default: () => [],
    },
  },

  emits: ['filters-updated'],

  data: (): { [T: string]: any, openFilters: string[], selectedFilters: BlueprintFilter[] } => ({
    sidebarOpen: false,
    Teleport: undefined,
    openFilters: [],
    selectedFilters: [],
  }),

  watch: {
    sidebarOpen() {
      if (!this.sidebarOpen) {
        this.$emit('filters-updated', this.selectedFilters);
      }
    },

    currentFilters() {
      this.selectedFilters = this.currentFilters;
    },
  },

  created() {
    this.Teleport = teleport_ as {
      new (): {
        $props: VNodeProps & TeleportProps
      }
    };
  },

  mounted() {
    if (this.currentFilters?.length) {
      this.selectedFilters = this.currentFilters;

      return;
    }

    this.selectedFilters = this.filters.map((filter) => ({ key: filter.key, filters: [] }));
  },

  methods: {
    toggleFilter(filter: string) {
      if (this.filterIsOpen(filter)) {
        this.openFilters = this.openFilters.filter((openFilter) => openFilter !== filter);

        return;
      }

      this.openFilters.push(filter);
    },

    filterIsOpen(filter: string) {
      return this.openFilters.includes(filter);
    },

    toggleFilterOption(filter: string, option: string) {
      const filterIndex = this.filterIndex(filter);

      if (this.optionIsSelected(filter, option)) {
        // eslint-disable-next-line vue/max-len
        this.selectedFilters[filterIndex].filters = this.selectedFilters[filterIndex].filters.filter((currentFilter) => currentFilter !== option);

        return;
      }

      this.selectedFilters[filterIndex].filters.push(option);
      this.selectedFilters[filterIndex].filters = this.selectedFilters[filterIndex].filters.filter((opt) => opt !== '');
    },

    filterIndex(filter: string): number {
      const filterGroup = this.selectedFilters.filter((selectedFilter) => selectedFilter.key === filter)[0];
      return this.selectedFilters.indexOf(filterGroup);
    },

    optionIsSelected(filter: string, option: string): boolean {
      const filters = this.selectedOptionsForFilter(filter, false);

      if (!filters) {
        return false;
      }

      return filters.includes(option);
    },

    filterHasSelection(filter: string): boolean {
      return this.selectedOptionsForFilter(filter, []).length > 0;
    },

    selectedOptionsForFilter<T>(filter: string, defaultValue: T): string[] | T {
      const filterGroup = this.selectedFilters.filter((selectedFilter) => selectedFilter.key === filter)[0];

      if (!filterGroup) {
        return defaultValue;
      }

      return filterGroup.filters;
    },
  },
});
</script>
