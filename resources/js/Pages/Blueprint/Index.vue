<template>
  <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
    <div class="w-full flex justify-between items-center p-2">
      <div>
        <h1 class="text-xl font-semibold">
          {{ metas.title }}
        </h1>
      </div>
    </div>

    <div class="w-full">
      <div
        class="m-2 overflow-x-scroll border border-gray-300 rounded shadow"
        scroll-region
      >
        <table>
          <thead>
            <tr class="divide-x divide-gray-300 leading-none text-sm">
              <th
                v-for="header in metas.headers"
                :key="header.column"
                class="p-2 bg-gray-200 text-gray-600 whitespace-nowrap text-left"
              >
                <div class="flex justify-between items-center">
                  <span>{{ header.label }}</span>

                  <Sorter
                    v-if="header.sortable"
                    :column="header.column"
                    :current-sort="currentSort"
                    class="ml-4"
                    @sort-change="sortChange"
                  />
                </div>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-300">
            <tr
              v-for="row in data.items"
              :key="row.id"
              class="divide-x divide-gray-300 leading-none text-sm even:bg-gray-100 hover:bg-blue-100 transition"
            >
              <td
                v-for="header in metas.headers"
                :key="header.column"
                class="px-2 py-1 text-gray-600"
              >
                <FieldListComponent
                  :value="row[header.column]"
                  :component="header.component"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="w-full pb-3">
      <Paginator
        :current-page="data.currentPage"
        :number-of-pages="data.numberOfPages"
        @go-to-page="goToPage"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Architect from '../../Layouts/Architect.vue';
import {
  BlueprintTableDataSet,
  BlueprintTableMetaSet,
  BlueprintTableQueryStringParameters,
  BlueprintTableSortableDataSet,
} from '../../types';
import FieldListComponent from '../../Fields/FieldListComponent.vue';
import Paginator from '../../Components/Paginator.vue';
import Sorter from '../../Components/Sorter.vue';

export default defineComponent({
  components: {
    Sorter,
    Paginator,
    FieldListComponent,
  },

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
    currentSort: {
      required: true,
      type: Object as () => BlueprintTableSortableDataSet,
    },
  },

  data: (): { queryStringParameters: BlueprintTableQueryStringParameters } => ({
    queryStringParameters: {} as BlueprintTableQueryStringParameters,
  }),

  mounted() {
    this.queryStringParameters = {
      page: this.data.currentPage,
      sortItem: this.currentSort.column,
      sortDirection: this.currentSort.direction,
    };
  },

  methods: {
    goToPage(page: number) {
      this.queryStringParameters.page = page;

      Inertia.get(window.location.pathname, this.queryStringParameters, { only: ['data', 'currentSort'], preserveScroll: true });
    },

    sortChange(sortable: BlueprintTableSortableDataSet) {
      this.queryStringParameters.sortItem = sortable.column;
      this.queryStringParameters.sortDirection = sortable.direction;

      Inertia.get(window.location.pathname, this.queryStringParameters, { only: ['data', 'currentSort'], preserveScroll: true });
    },
  },
});
</script>
