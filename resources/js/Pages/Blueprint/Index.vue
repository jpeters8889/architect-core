<template>
  <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
    <div>
      <h1>Blueprint</h1>
    </div>

    <div class="w-full">
      <div class="m-2 overflow-x-scroll border border-gray-300 rounded shadow">
        <table>
          <thead>
            <tr class="divide-x divide-gray-300 leading-none text-sm">
              <template
                v-for="header in headers"
                :key="header"
              >
                <th class="px-2 py-1 bg-gray-200 text-gray-600 whitespace-nowrap">
                  {{ header }}
                </th>
              </template>
            </tr>
          </thead>
          <tbody>
            <tr class="divide-x divide-gray-300 leading-none text-sm">
              <template
                v-for="header in headers"
                :key="header"
              >
                <td class="px-2 py-1 text-gray-600 flex-shrink-0">
                  test
                </td>
              </template>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Architect from '../../Layouts/Architect.vue';

export default defineComponent({
  layout: Architect,

  props: {
    headers: {
      required: true,
      type: Array as () => string[],
    },
  },
});
</script>
