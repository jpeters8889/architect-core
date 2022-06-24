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

  emits: ['change'],

  data: (): { newValue: any } => ({
    newValue: null,
  }),

  watch: {
    newValue() {
      this.$emit('change', this.newValue);
    },

    value() {
      if (this.value !== this.newValue) {
        this.newValue = this.value;
      }
    },
  },

  created() {
    this.newValue = this.value;
  },
});
