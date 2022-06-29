<template>
  <div
    v-if="messages.length"
    class="absolute bottom-0 w-full flex flex-col gap-2 sm:items-end sm:p-4"
  >
    <FlashMessage
      v-for="message in messages"
      :key="message.id"
      :message="message"
    />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Flash } from '../../types';
import FlashMessage from './FlashMessage.vue';

export default defineComponent({
  components: { FlashMessage },

  props: {
    flash: {
      required: false,
      type: Object as () => Flash,
      default: null,
    },
  },

  data: (): { messages: Flash[] } => ({
    messages: [],
  }),

  watch: {
    flash(flash) {
      if (!flash) {
        return;
      }

      this.pushFlashMessage(flash);
    },
  },

  methods: {
    pushFlashMessage(message: Flash): void {
      this.messages.push(message);

      const index = this.messages.indexOf(message);

      setTimeout(() => {
        this.messages[index].show = true;
      }, 100);

      setTimeout(() => {
        this.messages[index].show = false;
      }, 3000);

      setTimeout(() => {
        this.messages = this.messages.filter((flash) => flash.id !== message.id);
      }, 3100);
    },
  },
});
</script>
