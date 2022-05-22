<template>
  <div
    class="flex-1 flex-grow-0 w-14 bg-gray-900 px-2 transition-[width]"
    :class="displayNav ? 'xl:min-w-[300px]' : ''"
  >
    <div class="h-10 mb-2" />

    <NavGroup
      :links="homeLinks"
      :expanded="displayNav"
    >
      <template #icon>
        <HomeIcon class="w-8 h-8" />
      </template>
    </NavGroup>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { HomeIcon } from '@heroicons/vue/outline';
import NavGroup from '../Navigation/NavGroup.vue';
import { Navigation, NavigationChild } from '../../types';

export default defineComponent({
  components: {
    NavGroup,
    HomeIcon,
  },

  props: {
    navigation: {
      required: true,
      type: Object as () => Navigation,
    },
    displayNav: {
      required: true,
      type: Boolean,
    },
  },

  computed: {
    homeLinks(): NavigationChild[] {
      return this.navigation.dashboards;
    },
  },
});
</script>
