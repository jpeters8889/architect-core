<template>
  <div class="rounded w-full h-auto flex-col items-center justify-center transition relative border-0 ">
    <div
      class="w-full flex items-center justify-between text-gray-500 mb-4 cursor-pointer"
      @click="toggleLinks()"
    >
      <span class="text-left text-sm uppercase block">
        {{ label }}
      </span>

      <ChevronDownIcon
        v-if="!open"
        class="w-5 h-5"
      />
      <ChevronUpIcon
        v-if="open"
        class="w-5 h-5"
      />
    </div>

    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <ul
        v-if="open"
        class="w-full flex flex-col space-y-3 text-gray-200"
      >
        <li
          v-for="link in links"
          :key="link.label"
          class="transition rounded-lg"
          :class="currentPage === absoluteLink(link.slug) ? 'bg-sky-900' : 'hover:bg-sky-700'"
        >
          <Link
            class="block p-2 flex items-center"
            :href="absoluteLink(link.slug)"
          >
            <div class="mr-2">
              <component
                :is="linkIcon()"
                class="w-6 h-6"
              />
            </div>
            <span class="block flex-1">{{ link.label }}</span>
          </Link>
        </li>
      </ul>
    </transition>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import {
  BuildingOfficeIcon,
  ChartBarSquareIcon,
  RectangleStackIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/20/solid';
import { NavigationChild } from '../../types';

export default defineComponent({
  components: {
    Link,
    ChartBarSquareIcon,
    BuildingOfficeIcon,
    RectangleStackIcon,
    ChevronDownIcon,
    ChevronUpIcon,
  },

  props: {
    links: {
      required: true,
      type: Array as () => NavigationChild[],
    },
    childIcon: {
      required: true,
      type: String,
    },
    label: {
      required: false,
      type: String,
      default: null,
    },
    basePath: {
      required: false,
      type: String,
      default: '/',
    },
  },

  data: () => ({
    open: true,
  }),

  computed: {
    currentPage(): string {
      return window.location.pathname;
    },
  },

  methods: {
    linkIcon(): string {
      switch (this.childIcon) {
        case 'chart':
          return 'ChartBarSquareIcon';
        case 'building':
          return 'OfficeBuildingIcon';
        case 'collection':
          return 'RectangleStackIcon';
        default:
                //
      }

      throw new Error('Unknown Icon');
    },

    absoluteLink(uri: string) {
      const basePath = (): string => {
        if (!this.basePath || this.basePath === '/') {
          return '/';
        }

        if (this.basePath.slice(-1) === '/') {
          return this.basePath.substring(0, this.basePath.length - 1);
        }

        if (this.basePath[0] !== '/') {
          return `/${this.basePath}`;
        }

        return this.basePath;
      };

      return `${basePath()}/${uri}`;
    },

    toggleLinks() {
      this.open = !this.open;
    },
  },
});
</script>
