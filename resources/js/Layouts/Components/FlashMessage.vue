<template>
  <transition
    enter-active-class="transition translate duration-200 ease-out"
    enter-from-class="transform scale-95 opacity-0 translate-y-full sm:translate-x-full sm:translate-y-0"
    enter-to-class="transform scale-100 opacity-100 sm:translate-y-0 sm:translate-x-0"
    leave-active-class="transition duration-100 ease-in"
    leave-from-class="transform scale-100 opacity-100"
    leave-to-class="transform scale-75 opacity-0"
  >
    <div
      v-if="message.show"
      class="bg-green-500 p-4 text-white sm:w-[300px]"
      v-html="message.message"
    />
  </transition>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Flash } from '../../types';

export default defineComponent({
  props: {
    message: {
      required: true,
      type: Object as () => Flash,
    },
  },
});
</script>
