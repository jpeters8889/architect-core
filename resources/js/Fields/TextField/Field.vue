<template>
  <div
    class="border border-gray-300 rounded focus-within:border-gray-500 transition duration-300"
  >
    <input
      :id="id"
      v-model="newValue"
      type="text"
      class="p-2 xl:p-4 placeholder-gray-300 text-gray-800 outline-0 ring-0 w-full"
    >
  </div>
</template>

<script>
export default {
  props: {
    id: {
      required: true,
      type: String,
    },
    value: {
      required: false,
      type: String,
      default: null,
    },
  },

  emits: ['change'],

  data: () => ({
    newValue: null,
  }),

  watch: {
    newValue() {
      this.$emit('change', this.newValue);
    },
  },

  created() {
    this.newValue = this.value;
  },
};
</script>
