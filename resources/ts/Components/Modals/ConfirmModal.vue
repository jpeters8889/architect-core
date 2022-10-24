<template>
  <Modal
    :title="title"
    :show="show"
    @close="cancel"
  >
    <template #icon>
      <ExclamationTriangleIcon class="h-6 w-6 text-red-600" />
    </template>

    <slot />

    <template #footer>
      <div class=" sm:flex sm:flex-row-reverse gap-2">
        <FormButton
          :label="cancelText"
          @click.prevent="cancel"
        />

        <FormButton
          :label="confirmText"
          @click.prevent="confirm"
        />
      </div>
    </template>
  </Modal>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
import FormButton from '../Forms/FormButton.vue';
import Modal from '../Modal.vue';

export default defineComponent({
  components: { Modal, FormButton, ExclamationTriangleIcon },

  props: {
    title: {
      required: false,
      type: String,
      default: undefined,
    },
    show: {
      required: true,
      type: Boolean,
    },
    cancelText: {
      required: false,
      type: String,
      default: 'No',
    },
    confirmText: {
      required: false,
      type: String,
      default: 'Yes',
    },
  },

  emits: ['cancel', 'confirm'],

  methods: {
    cancel() {
      this.$emit('cancel');
    },

    confirm() {
      this.$emit('confirm');
    },
  },
});
</script>
