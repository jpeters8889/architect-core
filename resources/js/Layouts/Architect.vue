<template>
  <div class="w-full min-h-screen flex bg-gray-300">
    <Sidebar :navigation="navigation" />

    <div class="w-full flex-1 flex flex-col">
      <Header />

      <div class="p-2 flex-1">
        <CardSkeleton>
          <slot />
        </CardSkeleton>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Header from './Components/Header.vue';
import Sidebar from './Components/Sidebar.vue';
import CardSkeleton from '../Components/CardSkeleton.vue';
import { Navigation } from '../types';

export default defineComponent({
  components: {
    CardSkeleton,
    Sidebar,
    Header,
  },

  props: {
    navigation: {
      required: true,
      type: Object as () => Navigation,
    },
  },
});
</script>
