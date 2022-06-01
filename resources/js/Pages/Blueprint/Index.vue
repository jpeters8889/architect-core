<template>
  <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
    <div>
      <h1>{{ metas.title }}</h1>
    </div>

    <div class="w-full">
      <div class="m-2 overflow-x-scroll border border-gray-300 rounded shadow">
        <table>
          <thead>
            <tr class="divide-x divide-gray-300 leading-none text-sm">
              <template
                v-for="header in metas.headers"
                :key="header.column"
              >
                <th class="px-2 py-1 bg-gray-200 text-gray-600 whitespace-nowrap text-left">
                  {{ header.label }}
                </th>
              </template>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-300">
            <template
              v-for="row in data.items"
              :key="row.id"
            >
              <tr class="divide-x divide-gray-300 leading-none text-sm even:bg-gray-100 hover:bg-blue-100 transition">
                <template
                  v-for="header in metas.headers"
                  :key="header.column"
                >
                  <td class="px-2 py-1 text-gray-600 flex-shrink-0">
                    <FieldListComponent
                      :value="row[header.column]"
                      :component="header.component"
                    />
                  </td>
                </template>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Architect from '../../Layouts/Architect.vue';
import { BlueprintTableDataSet, BlueprintTableMetaSet } from '../../types';
import FieldListComponent from '../../Fields/FieldListComponent.vue';

export default defineComponent({
  layout: Architect,

  props: {
    metas: {
      required: true,
      type: Object as () => BlueprintTableMetaSet,
    },
    data: {
      required: true,
      type: Object as () => BlueprintTableDataSet,
    },
  },

  components: { FieldListComponent },
});
</script>
