<template>
  <TransitionRoot
    :show="show"
    appear
    as="div"
    class="absolute top-0 left-0 w-full h-full z-[999]"
  >
    <template v-if="!hideBackgroundAt || (hideBackgroundAt && isLt(hideBackgroundAt))">
      <TransitionChild
        as="template"
        enter="transition-opacity ease-linear duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-linear duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div
          class="bg-white bg-opacity-80 absolute w-full h-full top-0 left-0"
          @click.self="closeSidebar()"
        />
      </TransitionChild>
    </template>

    <TransitionChild
      as="template"
      enter="transition ease-in-out duration-300 transform"
      :enter-from="side === 'left' ? '-translate-x-full' : 'translate-x-full'"
      enter-to="translate-x-0"
      leave="transition ease-in-out duration-300 transform"
      leave-from="translate-x-0"
      :leave-to="side === 'left' ? '-translate-x-full' : 'translate-x-full'"
    >
      <div
        class="absolute top-0 bg-slate-900 h-full w-10/12 max-w-[300px] z-[999] xl:relative xl:w-full"
        :class="side === 'left' ? 'left-0' : 'right-0'"
      >
        <slot />
      </div>
    </TransitionChild>
  </TransitionRoot>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { TransitionChild, TransitionRoot } from '@headlessui/vue';
import ResponsiveOptions from '../Mixins/ResponsiveOptions';

export default defineComponent({
  components: {
    TransitionRoot,
    TransitionChild,
  },

  mixins: [ResponsiveOptions],

  props: {
    show: {
      required: true,
      type: Boolean,
    },
    side: {
      required: false,
      type: String,
      default: 'left',
      validator: (value: string) => ['left', 'right'].includes(value),
    },
    hideBackgroundAt: {
      required: false,
      type: String,
      default: null,
      validator: (value: string) => ['mobile', 'sm', 'md', 'lg', 'xl', '2xl'].includes(value),
    },
  },

  emits: ['close'],

  watch: {
    show() {
      if (this.show) {
        document.querySelector('body')?.classList.add('overflow-hidden');
        return;
      }

      document.querySelector('body')?.classList.remove('overflow-hidden');
    },
  },

  methods: {
    closeSidebar() {
      this.$emit('close');
    },
  },
});
</script>
