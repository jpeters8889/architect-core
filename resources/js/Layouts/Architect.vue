<template>
  <div class="w-full min-h-screen flex flex-col bg-gray-300">
    <Header
      :nav-displayed="displayNav"
      @toggle-nav="displayNav = !displayNav"
    />

    <div class="w-full flex-1 flex">
      <Sidebar
        :navigation="navigation"
        :display-nav="displayNav"
        :base-path="basePath"
      />

      <div class="w-full overflow-hidden">
        <div class="p-4 flex-1">
          <CardSkeleton>
            <slot />
          </CardSkeleton>
        </div>
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

  data: () => ({
    displayNav: true,
  }),

  props: {
    navigation: {
      required: true,
      type: Object as () => Navigation,
    },
    basePath: {
      required: true,
      type: String,
    },
  },
});
</script>
