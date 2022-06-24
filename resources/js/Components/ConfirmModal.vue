<template>
  <TransitionRoot
    appear
    :show="show"
    as="template"
  >
    <Dialog
      class="relative z-10"
      :show="show"
      @close="closeModal"
    >
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <ExclamationIcon class="h-6 w-6 text-red-600" />
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <DialogTitle
                      as="h3"
                      class="text-lg leading-6 font-medium text-gray-900"
                    >
                      Are you sure you want too...
                    </DialogTitle>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500">
                        Are you sure you want to blah blah blah
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <FormButton label="No" />

                <FormButton label="Yes, reset" />
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import {
  Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild,
} from '@headlessui/vue';
import { ExclamationIcon } from '@heroicons/vue/outline';
import FormButton from './Forms/FormButton.vue';

export default defineComponent({
  components: {
    FormButton,
    DialogTitle,
    DialogPanel,
    Dialog,
    TransitionRoot,
    TransitionChild,
    ExclamationIcon,
  },

  props: {
    show: {
      required: true,
      type: Boolean,
    },
  },

  emits: ['close'],

  methods: {
    closeModal() {
      this.$emit('close');
    },
  },
});
</script>
