<template>
  <div
    class="border rounded transition duration-300 overflow-hidden"
    :class="error ? 'border-red-500' : 'border-gray-300 focus-within:border-gray-500'"
  >
    <input
      :id="id"
      v-model="newValue"
      type="password"
      class="p-2 xl:p-4 placeholder-gray-300 text-gray-800 outline-0 ring-0 w-full"
      autocomplete="new-password"
    >
  </div>

  <span
    v-if="error"
    class="text-sm font-semibold text-red-500"
    v-text="error"
  />
</template>

<script>
import BlueprintFormFieldComponent from '../../Common/BlueprintFormFieldComponent';

export default {
  extends: BlueprintFormFieldComponent,
};
</script>
