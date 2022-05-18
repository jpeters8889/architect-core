<template>
  <div class="flex-1 flex-grow-0 w-14 bg-gray-900 px-2">
    <div class="h-10 mb-2" />

    <NavGroup :links="homeLinks">
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
import { NavigationChild } from '../../types';

export default defineComponent({
  components: {
    NavGroup,
    HomeIcon,
  },

  computed: {
    homeLinks(): NavigationChild[] {
      return [
        { label: 'My Dashboard', path: '/', icon: 'chart' },
        { label: 'Shop', path: '/', icon: 'chart' },
        { label: 'Mailcoach', path: '/', icon: 'chart' },
      ];
    },
  },
});
</script>
