<template>
  <div
    :class="wrapperClasses"
    @click="toggle = !toggle"
    @mouseleave="toggle = false"
  >
    <slot name="icon" />

    <div
      v-if="toggle"
      class="absolute left-full top-0 min-h-10 w-auto"
    >
      <ul class="divide-y divide-gray-400 bg-gray-700 max-w-[300px] min-w-[200px] ml-0">
        <li
          v-for="link in links"
          :key="link.label"
          class="transition hover:bg-gray-600"
        >
          <Link
            class="block p-2 flex items-center"
            :href="link.path"
          >
            <div class="mr-2">
              <component
                :is="linkIcon(link.icon)"
                class="w-6 h-6"
              />
            </div>
            <span class="block flex-1">{{ link.label }}</span>
          </Link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { ChartSquareBarIcon } from '@heroicons/vue/outline';
import { NavigationChild } from '../../types';

export default defineComponent({
  components: {
    Link,
    ChartSquareBarIcon,
  },

  props: {
    links: {
      required: true,
      type: Array as () => NavigationChild[],
    },
  },

  data: () => ({
    toggle: false,
  }),

  computed: {
    wrapperClasses(): string[] {
      return [
        'bg-gray-800',
        this.toggle ? 'rounded-l' : 'rounded',
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
