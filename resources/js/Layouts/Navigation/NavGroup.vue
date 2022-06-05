<template>
  <Menu
    as="div"
    :class="wrapperClasses"
  >
    <MenuButton
      class="w-full flex items-center justify-center"
      :class="isExpanded ? 'xl:border-b xl:border-gray-400 xl:justify-start' : ''"
      :as="isExpanded ? 'div' : 'button'"
    >
      <span :class="isExpanded ? 'xl:p-2' : ''">
        <slot name="icon" />
      </span>

      <span
        v-if="isExpanded && label"
        class="text-left text-xl xl:block xl:ml-2 xl:flex-1"
      >
        {{ label }}
      </span>
    </MenuButton>

    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <MenuItems
        class="absolute left-full top-0 min-h-10 w-auto"
        :class="isExpanded ? 'xl:relative xl:left-auto xl:w-full' : ''"
        :static="isExpanded"
      >
        <ul
          class="border border-gray-400 divide-y divide-gray-400 bg-gray-300 max-w-[300px] min-w-[200px] ml-2"
          :class="isExpanded ? 'xl:w-full xl:ml-0 xl:border-0 xl:divide-y-0' : ''"
        >
          <MenuItem
            v-for="link in links"
            :key="link.label"
          >
            <li
              class="transition"
              :class="currentPage === absoluteLink(link.slug) ? 'bg-slate-300' : 'hover:bg-slate-300'"
            >
              <Link
                class="block p-2 flex items-center xl:text-gray-600"
                :class="isExpanded ? 'xl:pl-2' : ''"
                :href="absoluteLink(link.slug)"
              >
                <div
                  class="mr-2"
                  :class="isExpanded ? 'xl:mr-6' : ''"
                >
                  <component
                    :is="linkIcon()"
                    class="w-6 h-6"
                  />
                </div>
                <span class="block flex-1">{{ link.label }}</span>
              </Link>
            </li>
          </MenuItem>
        </ul>
      </MenuItems>
    </transition>
  </Menu>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { ChartSquareBarIcon, OfficeBuildingIcon, CollectionIcon } from '@heroicons/vue/outline';
import {
  Menu, MenuButton, MenuItems, MenuItem,
} from '@headlessui/vue';
import { NavigationChild } from '../../types';
import ResponsiveOptions from '../../Mixins/ResponsiveOptions';

export default defineComponent({
  components: {
    Link,
    ChartSquareBarIcon,
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
    OfficeBuildingIcon,
    CollectionIcon,
  },

  mixins: [ResponsiveOptions],

  props: {
    expanded: {
      required: true,
      type: Boolean,
    },
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

  computed: {
    currentPage(): string {
      return window.location.pathname;
    },

    isExpanded(): boolean {
      if (this.isLt('xl')) {
        return false;
      }

      return this.expanded;
    },

    wrapperClasses(): string[] {
      const classes: string[] = [
        'rounded',
        'w-10',
        'h-10',
        'border',
        'border-gray-400',
        'flex',
        'items-center',
        'justify-center',
        'text-gray-700',
        'hover:text-gray-900',
        'transition',
        'relative',
      ];

      if (this.isExpanded) {
        classes.push(
          'xl:w-full',
          'xl:flex-col',
          'xl:h-auto',
          'xl:border-0',
        );
      }

      return classes;
    },
  },

  methods: {
    linkIcon(): string {
      switch (this.childIcon) {
        case 'chart':
          return 'ChartSquareBarIcon';
        case 'building':
          return 'OfficeBuildingIcon';
        case 'collection':
          return 'CollectionIcon';
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
  },
});
</script>
