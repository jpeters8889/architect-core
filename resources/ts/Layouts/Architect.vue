<template>
  <div class="w-full min-h-screen flex flex-col bg-gray-300 xl:flex-row">
    <Sidebar
      :navigation="navigation"
      :base-path="basePath"
    />

    <div class="flex w-full flex-1 flex-col h-full">
      <Header />

      <div class="w-full overflow-hidden">
        <div class="p-2 xl:p-4 flex-1">
          <slot />
        </div>
      </div>
    </div>
  </div>

  <FlashMessageContainer :flash="flash" />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Header from './Components/Header.vue';
import Sidebar from './Components/Sidebar.vue';
import CardSkeleton from '../Components/CardSkeleton.vue';
import { Flash, Navigation } from '../types';
import FlashMessageContainer from './Components/FlashMessageContainer.vue';

export default defineComponent({
  components: {
    FlashMessageContainer,
    CardSkeleton,
    Sidebar,
    Header,
  },

  props: {
    navigation: {
      required: true,
      type: Object as () => Navigation,
    },
    basePath: {
      required: true,
      type: String,
    },
    flash: {
      required: false,
      type: Object as () => Flash,
      default: null,
    },
  },

  data: () => ({
    displayNav: true,
  }),
});
</script>
