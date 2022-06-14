<template>
  <div class="flex h-full items-center">
    <Switch
      v-model="newValue"
      :class="newValue ? 'bg-gray-500 focus:ring-gray-900' : 'bg-gray-300 focus:ring-gray-500'"
      class="relative inline-flex h-8 w-16 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2"
    >
      <span
        :class="newValue ? 'translate-x-8' : 'translate-x-0'"
        class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
      />
    </Switch>
  </div>
</template>

<script>
import { Switch } from '@headlessui/vue';
import BlueprintFormFieldComponent from '../../Common/BlueprintFormFieldComponent';

export default {
  components: { Switch },

  extends: BlueprintFormFieldComponent,

  props: {
    value: {
      required: false,
      type: Boolean,
      default: null,
    },
  },
};
</script>
