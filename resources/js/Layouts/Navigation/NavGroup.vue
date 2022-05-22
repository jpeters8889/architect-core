<template>
  <Menu
    as="div"
    :class="wrapperClasses"
  >
    <MenuButton
      class="w-full flex items-center justify-center"
      :class="isExpanded ? 'xl:bg-gray-800 xl:justify-start' : ''"
      :as="isExpanded ? 'div' : 'button'"
    >
      <span :class="isExpanded ? 'xl:p-2' : ''">
        <slot name="icon" />
      </span>

      <span
        v-if="isExpanded"
        class="text-left text-xl xl:block xl:ml-2 xl:flex-1"
      >
        Dashboards
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
          class="divide-y divide-gray-400 bg-gray-700 max-w-[300px] min-w-[200px] ml-2"
          :class="isExpanded ? 'xl:w-full xl:ml-0' : ''"
        >
          <MenuItem
            v-for="link in links"
            :key="link.label"
          >
            <li class="transition hover:bg-gray-600">
              <Link
                class="block p-2 flex items-center"
                :href="link.slug"
              >
                <div
                  class="mr-2"
                  :class="isExpanded ? 'xl:mr-6' : ''"
                >
                  <component
                    :is="linkIcon(link.icon)"
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
import { ChartSquareBarIcon } from '@heroicons/vue/outline';
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
  },

  computed: {
    isExpanded(): boolean {
      if (this.isLt('xl')) {
        return false;
      }

      return this.expanded;
    },

    wrapperClasses(): string[] {
      const classes: string[] = [
        'bg-gray-800',
        'rounded',
        'w-10',
        'h-10',
        'border',
        'border-gray-800',
        'hover:bg-gray-700',
        'flex',
        'items-center',
        'justify-center',
        'text-gray-400',
        'hover:text-gray-200',
        'transition',
        'relative',
      ];

      if (this.isExpanded) {
        classes.push(
          'xl:w-full',
          'xl:text-gray-200',
          'xl:flex-col',
          'xl:h-auto',
        );
      }

      return classes;
    },
  },

  methods: {
    linkIcon(icon: string): string {
      switch (icon) {
        case 'chart':
          return 'ChartSquareBarIcon';
        default:
              //
      }

      throw new Error('Unknown Icon');
    },
  },
});
</script>
