<template>
  <div class="border border-gray-300 rounded focus-within:border-gray-500 transition duration-300 overflow-hidden">
    <input
      :id="id"
      v-model="newValue"
      type="password"
      class="p-2 xl:p-4 placeholder-gray-300 text-gray-800 outline-0 ring-0 w-full"
      autocomplete="new-password"
    >
  </div>
</template>

<script>
import BlueprintFormFieldComponent from '../../Common/BlueprintFormFieldComponent';

export default {
  extends: BlueprintFormFieldComponent,
};
</script>
