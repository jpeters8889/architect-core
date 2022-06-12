<template>
  <div class="w-full flex justify-between items-center mb-2 xl:mb-4">
    <div>
      <h1 class="text-xl font-semibold">
        Create {{ metas.singularTitle }}
      </h1>
    </div>
  </div>

  <CardSkeleton>
    <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
      <div class="w-full">
        Create
      </div>
    </div>
  </CardSkeleton>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Architect from '../../Layouts/Architect.vue';
import CardSkeleton from '../../Components/CardSkeleton.vue';
import { BlueprintFormField, BlueprintTableMetaSet } from '../../types';

export default defineComponent({
  components: {
    CardSkeleton,
  },

  layout: Architect,

  props: {
    metas: {
      required: true,
      type: Object as () => BlueprintTableMetaSet,
    },
    fields: {
      required: true,
      type: Object as () => BlueprintFormField[],
    },
  },

  methods: {
    //
  },
});
</script>
