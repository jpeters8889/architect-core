<template>
  <div class="w-full min-h-screen flex bg-gray-300">
    <Sidebar />

    <div class="w-full flex-1 flex flex-col">
      <Header />

      <div class="p-2 flex-1">
        <CardSkelton>
          <slot />
        </CardSkelton>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Header from './Components/Header.vue';
import Sidebar from './Components/Sidebar.vue';
import CardSkelton from '../Components/CardSkelton.vue';

export default defineComponent({
  components: {
    CardSkelton,
    Sidebar,
    Header,
  },
});
</script>
