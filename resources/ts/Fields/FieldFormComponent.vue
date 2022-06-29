<template>
  <component
    :is="component"
    :id="id"
    :value="modelValue"
    :error="error"
    :rules="rules"
    :meta="meta"
    @change="handleChange"
  />
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import FormComponentRegistrar from './FormComponentRegistrar';

export default defineComponent({
  components: FormComponentRegistrar,

  props: {
    component: {
      required: true,
      type: String,
    },
    id: {
      required: true,
      type: String,
    },
    modelValue: {
      required: false,
      type: [String, Number, Boolean],
      default: null,
    },
    error: {
      required: false,
      type: String,
      default: null,
    },
    rules: {
      required: false,
      type: Array as () => string[],
      default: () => [],
    },
    meta: {
      required: false,
      type: Object as () => { [K: string]: any },
      default: () => {},
    },
  },

  emits: ['update:modelValue'],

  methods: {
    handleChange(value: any) {
      this.$emit('update:modelValue', value);
    },
  },
});
</script>
