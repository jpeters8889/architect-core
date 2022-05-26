<template>
  <div
    class="flex-1 flex-grow-0 w-14 bg-gray-900 px-2 transition-[width] space-y-5"
    :class="displayNav ? 'xl:min-w-[300px]' : ''"
  >
    <div class="h-10 !mb-0" />

    <NavGroup
      :links="navigation.dashboards"
      :expanded="displayNav"
      :base-path="basePath"
      label="Dashboards"
      child-icon="chart"
      class="!mt-0"
    >
      <template #icon>
        <HomeIcon class="w-8 h-8" />
      </template>
    </NavGroup>

    <NavGroup
      v-for="group in navigation.blueprints"
      :key="group.label"
      :links="group.blueprints"
      :expanded="displayNav"
      :label="group.label"
      :base-path="`${basePath}/blueprint`"
      child-icon="collection"
    >
      <template #icon>
        <OfficeBuildingIcon class="w-8 h-8" />
      </template>
    </NavGroup>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { HomeIcon, OfficeBuildingIcon } from '@heroicons/vue/outline';
import NavGroup from '../Navigation/NavGroup.vue';
import { Navigation } from '../../types';

export default defineComponent({
  components: {
    NavGroup,
    HomeIcon,
    OfficeBuildingIcon,
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
    basePath: {
      required: true,
      type: String,
    },
  },
});
</script>
