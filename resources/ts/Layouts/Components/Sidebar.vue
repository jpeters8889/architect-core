<template>
  <div class="flex-1 flex-grow-0 w-full h-14 bg-gray-900 shadow-xl xl:max-w-[300px] xl:h-full xl:min-h-screen xl:flex-auto">
    <NavWrapper>
      <NavGroup
        :links="navigation.dashboards"
        :base-path="basePath"
        label="Dashboards"
        child-icon="chart"
      />

      <NavGroup
        v-for="group in navigation.blueprints"
        :key="group.label"
        :links="group.blueprints"
        :label="group.label"
        :base-path="`${basePath}/blueprint`"
        child-icon="collection"
      />
    </NavWrapper>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import NavGroup from '../Navigation/NavGroup.vue';
import { Navigation } from '../../types';
import NavWrapper from '../Navigation/NavWrapper.vue';

export default defineComponent({
  components: {
    NavWrapper,
    NavGroup,
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
  },
});
</script>
