<template>
  <Listbox
    v-slot="{ open }"
    v-model="selectedPage"
  >
    <div
      class="transition duration-300 relative focus-within:border-0"
      :class="open ? 'rounded-b' : 'rounded'"
    >
      <ListboxButton class="relative w-full cursor-default py-2 pl-3 pr-10 text-left focus:outline-none sm:text-sm">
        <span class="block truncate">{{ selectValue }}</span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon class="h-5 w-5 text-sky-700" />
        </span>
      </ListboxButton>

      <transition
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions class="absolute bottom-0 z-50 max-h-60 w-full overflow-auto rounded-b-md bg-white py-1 text-base shadow-lg focus:outline-none sm:text-sm border border-gray-200">
          <ListboxOption
            v-for="option in pageArray"
            v-slot="{ active, selected }"
            :key="option.key"
            :value="option.page"
            as="template"
          >
            <li
              class="text-gray-800 relative cursor-default select-none py-2 px-4 block"
              :class="selected ? 'font-medium text-sky-700' : 'font-normal hover:bg-sky-700'"
            >
              {{ option.label }}
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {
  Listbox,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/24/outline';
import { PaginationSelectOptions } from '../../types';

export default defineComponent({
  components: {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    CheckIcon,
    ChevronUpDownIcon,
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
  },

  emits: ['pageChange'],

  data: (): { selectedPage: number } => ({
    selectedPage: 0,
  }),

  computed: {
    pageArray(): PaginationSelectOptions[] {
      return Array.from(Array(this.numberOfPages)).map((a, index: number) => ({
        page: index + 1,
        label: `Page ${(index + 1).toString()} of ${this.numberOfPages}`,
      }));
    },

    selectValue(): string {
      const pageObject: PaginationSelectOptions = this.pageArray.filter((page) => page.page === this.selectedPage)[0];

      if (!pageObject) {
        return '';
      }

      return pageObject.label;
    },
  },

  watch: {
    selectedPage(newValue, oldValue) {
      if (oldValue === 0) {
        return;
      }

      this.$emit('pageChange', this.selectedPage);
    },
  },

  mounted() {
    this.selectedPage = this.currentPage;
  },
});
</script>
