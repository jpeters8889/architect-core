<template>
  <div class="w-full flex justify-between items-center mb-2 xl:mb-4">
    <div>
      <h1 class="text-xl font-semibold">
        Create {{ metas.singularTitle }}
      </h1>
    </div>
  </div>

  <CardSkeleton>
    <form>
      <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
        <div class="w-full p-2 xl:p-4 flex flex-col divide-y divide-gray-300">
          <FormField
            v-for="field in fields"
            :id="field.id"
            :key="field.id"
            :label="field.label"
            :help-text="field.helpText"
          >
            <FieldFormComponent
              :id="field.id"
              v-model="form[field.id]"
              :component="field.component"
              :rules="field.rules"
              :meta="field.meta"
            />
          </FormField>
        </div>
      </div>
    </form>
  </CardSkeleton>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { collect } from 'collect.js';
import Architect from '../../Layouts/Architect.vue';
import CardSkeleton from '../../Components/CardSkeleton.vue';
import { BlueprintFormField, BlueprintTableMetaSet } from '../../types';
import FormField from '../../Components/Forms/FormField.vue';
import FieldFormComponent from '../../Fields/FieldFormComponent.vue';

export default defineComponent({
  components: {
    FieldFormComponent,
    FormField,
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

  data: () => ({
    form: undefined,
  }),

  created() {
    this.buildForm();
  },

  methods: {
    buildForm() {
      const mappedFormFields = collect(this.fields).mapWithKeys((field: BlueprintFormField) => [field.id, null]);

      // @ts-ignore
      this.form = this.$inertia.form(`create-${this.metas.singularTitle}`, mappedFormFields);
    },
  },
});
</script>
