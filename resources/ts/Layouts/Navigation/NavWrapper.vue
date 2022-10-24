<template>
  <nav class="flex flex items-center h-full w-full">
    <div
      :class="hamburgerClasses"
      @click="navOpen = !navOpen"
    >
      <Bars4Icon class="h-7 w-7 text-sky-800" />
    </div>

    <SidebarModal
      :show="isOpen"
      hide-background-at="xl"
      @close="navOpen = false"
    >
      <div class="absolute top-0 left-0 bg-slate-900 h-full w-full max-w-[300px] z-[999] xl:relative">
        <div class="h-14 bg-slate-800 xl:bg-slate-900 w-full">
          Logo
        </div>

        <div class="p-4 flex flex-col space-y-5 w-10/12">
          <slot />
        </div>
      </div>
    </SidebarModal>
  </nav>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Bars4Icon } from '@heroicons/vue/24/outline';
import ResponsiveOptions from '../../Mixins/ResponsiveOptions';
import SidebarModal from '../../Components/SidebarModal.vue';

export default defineComponent({
  components: {
    SidebarModal,
    Bars4Icon,
  },

  mixins: [ResponsiveOptions],

  data: () => ({
    navOpen: false,
  }),

  computed: {
    hamburgerClasses(): string[] {
      return [
        'xl:hidden',
        'rounded-full',
        'hover:bg-white',
        'hover:bg-opacity-5',
        'transition',
        'transition-all',
        'cursor-pointer',
        'w-10',
        'h-10',
        'flex',
        'justify-center',
        'items-center',
        'ml-2',
      ];
    },

    isOpen(): boolean {
      if (this.isGte('xl')) {
        return true;
      }

      return this.navOpen;
    },
  },
});
</script>
