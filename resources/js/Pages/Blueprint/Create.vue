<template>
  <div class="w-full flex justify-between items-center mb-2 xl:mb-4">
    <div>
      <h1 class="text-xl font-semibold">
        Create {{ metas.singularTitle }}
      </h1>
    </div>
  </div>

  <CardSkeleton>
    <form
      @submit.prevent="submitForm"
      @reset.prevent="resetForm"
    >
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
              :error="form?.errors[field.id]"
            />
          </FormField>

          <div class="flex justify-between items-bottom pt-2">
            <div>
              <FormButton
                label="Reset"
                type="reset"
                theme="minor"
              />
            </div>
            <div class="flex space-x-2">
              <FormButton
                label="Cancel"
                theme="minor"
                class="mr-4"
              />

              <FormButton
                label="Create and add another"
                type="submit"
                @click="redirectBack = true"
              />

              <FormButton
                :label="'Create '+ metas.singularTitle"
                type="submit"
                @click="redirectBack = false"
              />
            </div>
          </div>
        </div>
      </div>
    </form>
  </CardSkeleton>

  <teleport to="body">
    <ConfirmModal
      :show="showConfirmationModal"
    />
  </teleport>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { collect } from 'collect.js';
import { InertiaFormProps } from '@inertiajs/inertia-vue3';
import Architect from '../../Layouts/Architect.vue';
import CardSkeleton from '../../Components/CardSkeleton.vue';
import { BlueprintFormField, BlueprintTableMetaSet } from '../../types';
import FormField from '../../Components/Forms/FormField.vue';
import FieldFormComponent from '../../Fields/FieldFormComponent.vue';
import FormButton from '../../Components/Forms/FormButton.vue';
import ConfirmModal from '../../Components/ConfirmModal.vue';

export default defineComponent({
  components: {
    ConfirmModal,
    FormButton,
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

  data: (): { form?: InertiaFormProps<any>, [T: string]: any } => ({
    form: undefined,
    redirectBack: false,
    showConfirmationModal: false,
  }),

  computed: {
    createRoute: () => window.location.pathname.replace('/create', ''),
  },

  created() {
    this.buildForm();
  },

  methods: {
    buildForm() {
      const mappedFormFields = collect(this.fields).mapWithKeys((field: BlueprintFormField) => [field.id, null]);

      // @ts-ignore
      this.form = this.$inertia.form(`create-${this.metas.singularTitle}`, mappedFormFields.all());
    },

    submitForm() {
      this.form?.post(`${this.createRoute}?redirectBack=${this.redirectBack}`);

      this.redirectBack = false;
    },

    resetForm() {
      this.showConfirmationModal = true;

      // if (!confirm('Are you sure you want to reset this form?')) {
      //   return;
      // }
      //
      // this.form?.reset();
    },
  },
});
</script>
