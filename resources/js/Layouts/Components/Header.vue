<template>
  <header class="bg-white w-full shadow-lg h10 flex">
    <div class="w-10 h-10 bg-gray-900 text-white flex justify-center items-center">
      <ChevronRightIcon class="w-8 h-8" />
    </div>

    <div class="p-2">
      Header
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ChevronRightIcon } from '@heroicons/vue/solid';

export default defineComponent({
  components: {
    ChevronRightIcon,
  },
});
</script>
