<template>
  <div class="w-full flex justify-between items-center mb-2 xl:mb-4">
    <div>
      <h1 class="text-xl font-semibold">
        Edit {{ metas.singularTitle }}
      </h1>
    </div>
  </div>

  <CardSkeleton>
    <form
      @submit.prevent="submitForm"
      @reset.prevent="resetForm"
      @cancel.prevent="cancelForm"
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
                @click.prevent="cancelForm"
              />

              <FormButton
                :label="'Update '+ metas.singularTitle"
                type="submit"
              />
            </div>
          </div>
        </div>
      </div>
    </form>
  </CardSkeleton>

  <component
    :is="Teleport"
    to="body"
  >
    <ConfirmModal
      :show="showResetConfirmationModal"
      confirm-text="Yes, reset"
      @cancel="showResetConfirmationModal = false"
      @confirm="confirmReset"
    >
      <p class="text-sm text-gray-500">
        Are you sure you want to reset this {{ metas.singularTitle }}? Your data will not be saved.
      </p>
    </ConfirmModal>

    <ConfirmModal
      :show="showCancelConfirmationModal"
      confirm-text="Yes, cancel"
      @cancel="showCancelConfirmationModal = false"
      @confirm="confirmCancel"
    >
      <p class="text-sm text-gray-500">
        Are you sure you want to cancel editing this {{ metas.singularTitle }}? Your data will not be saved.
      </p>
    </ConfirmModal>
  </component>
</template>

<script lang="ts">
import {
  defineComponent,
  Teleport as teleport_,
  TeleportProps,
  VNodeProps,
} from 'vue';
import { collect } from 'collect.js';
import { InertiaFormProps } from '@inertiajs/inertia-vue3';
import Architect from '../../Layouts/Architect.vue';
import CardSkeleton from '../../Components/CardSkeleton.vue';
import { BlueprintFormField, BlueprintTableMetaSet } from '../../types';
import FormField from '../../Components/Forms/FormField.vue';
import FieldFormComponent from '../../Fields/FieldFormComponent.vue';
import FormButton from '../../Components/Forms/FormButton.vue';
import ConfirmModal from '../../Components/Modals/ConfirmModal.vue';

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
    showResetConfirmationModal: false,
    showCancelConfirmationModal: false,
    Teleport: undefined,
  }),

  computed: {
    submitRoute: () => window.location.pathname,

    cancelRoute: () => window.location.pathname.split('/').slice(0, -1).join('/'),
  },

  created() {
    this.Teleport = teleport_ as {
      new (): {
        $props: VNodeProps & TeleportProps
      }
    };

    this.buildForm();
  },

  methods: {
    buildForm() {
      const mappedFormFields = collect(this.fields).mapWithKeys((field: BlueprintFormField) => [field.id, field.value]);

      // @ts-ignore
      this.form = this.$inertia.form(`edit-${this.metas.singularTitle}`, mappedFormFields.all());
    },

    submitForm() {
      this.form?.patch(this.submitRoute);
    },

    resetForm() {
      this.showResetConfirmationModal = true;
    },

    confirmReset() {
      this.form?.reset();
      this.showResetConfirmationModal = false;
    },

    cancelForm() {
      if (!this.form?.isDirty) {
        this.confirmCancel();

        return;
      }

      this.showCancelConfirmationModal = true;
    },

    confirmCancel() {
      this.$inertia.get(this.cancelRoute);
    },
  },
});
</script>
