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
        <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          @keyup.esc="closeModal"
        />
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
                  <div
                    v-if="$slots.icon"
                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                  >
                    <slot name="icon" />
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <DialogTitle
                      v-if="title"
                      as="h3"
                      class="text-lg leading-6 font-medium text-gray-900 mb-2"
                      v-html="title"
                    />
                    <div>
                      <slot />
                    </div>
                  </div>
                </div>
              </div>
              <div
                v-if="$slots.footer"
                class="bg-gray-50 px-4 py-3 sm:px-6"
              >
                <slot name="footer" />
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
  Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot,
} from '@headlessui/vue';

export default defineComponent({
  components: {
    DialogTitle,
    DialogPanel,
    Dialog,
    TransitionRoot,
    TransitionChild,
  },

  props: {
    show: {
      required: true,
      type: Boolean,
    },
    title: {
      required: false,
      type: String,
      default: undefined,
    },
    closable: {
      required: false,
      type: Boolean,
      default: true,
    },
  },

  emits: ['close', 'closeAttempt'],

  methods: {
    closeModal() {
      this.$emit(this.closable ? 'close' : 'closeAttempt');
    },
  },
});
</script>
