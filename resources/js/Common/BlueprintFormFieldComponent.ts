import { defineComponent } from 'vue';

export default defineComponent({
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
    rules: {
      required: false,
      type: Array as () => string[],
      default: () => [],
    },
  },

  emits: ['change'],

  data: (): { newValue: any } => ({
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
});
