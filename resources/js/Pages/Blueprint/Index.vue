<template>
  <div class="w-full flex justify-between items-center mb-2 xl:mb-4">
    <div>
      <h1 class="text-xl font-semibold">
        {{ metas.title }}
      </h1>
    </div>

    <div>
      <FormButton
        as="Link"
        :to="createRoute"
        :label="`Create ${metas.singularTitle}`"
      />
    </div>
  </div>

  <CardSkeleton>
    <div class="flex flex-col space-y-5 justify-between items-center overflow-hidden">
      <div class="w-full">
        <TableWrapper>
          <template #header>
            <TableHeaderRow>
              <TableHeaderCell
                v-for="header in metas.headers"
                :key="header.column"
              >
                <div class="flex justify-between items-center">
                  <span>{{ header.label }}</span>

                  <Sorter
                    v-if="header.sortable"
                    :column="header.column"
                    :current-sort="currentSort"
                    class="ml-2"
                    @sort-change="sortChange"
                  />
                </div>
              </TableHeaderCell>

              <TableHeaderCell />
            </TableHeaderRow>
          </template>

          <TableRow
            v-for="row in data.items"
            :key="row.id"
            class="divide-x divide-gray-300 leading-none text-sm even:bg-gray-100 hover:bg-blue-100 transition"
          >
            <TableCell
              v-for="header in metas.headers"
              :key="header.column"
            >
              <FieldListComponent
                :value="row[header.column]"
                :component="header.component"
              />
            </TableCell>

            <TableCell>
              <TableButtons
                :settings="row.$meta"
                @button-press="buttonPressed"
              />
            </TableCell>
          </TableRow>
        </TableWrapper>
      </div>

      <div class="w-full pb-3">
        <Paginator
          :current-page="data.currentPage"
          :number-of-pages="data.numberOfPages"
          @go-to-page="goToPage"
        />
      </div>
    </div>
  </CardSkeleton>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Architect from '../../Layouts/Architect.vue';
import {
  BlueprintTableButtonEvent,
  BlueprintTableDataSet,
  BlueprintTableMetaSet,
  BlueprintTableQueryStringParameters,
  BlueprintTableSortableDataSet,
} from '../../types';
import FieldListComponent from '../../Fields/FieldListComponent.vue';
import Paginator from '../../Components/Paginator.vue';
import Sorter from '../../Components/Sorter.vue';
import TableWrapper from '../../Components/Table/TableWrapper.vue';
import TableRow from '../../Components/Table/TableRow.vue';
import TableHeaderRow from '../../Components/Table/TableHeaderRow.vue';
import TableHeaderCell from '../../Components/Table/TableHeaderCell.vue';
import TableCell from '../../Components/Table/TableCell.vue';
import TableButtons from '../../Components/Blueprints/TableButtons.vue';
import CardSkeleton from '../../Components/CardSkeleton.vue';
import FormButton from '../../Components/Forms/FormButton.vue';

export default defineComponent({
  components: {
    CardSkeleton,
    TableButtons,
    TableCell,
    TableHeaderCell,
    TableHeaderRow,
    TableRow,
    TableWrapper,
    Sorter,
    Paginator,
    FieldListComponent,
    FormButton,
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

  computed: {
    createRoute: () => `${window.location.pathname}/create`,
  },

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

    buttonPressed(button: BlueprintTableButtonEvent, id: number | string) {
      let safeId: string | number = id;

      if (typeof id === 'number') {
        safeId = id.toString(10);
      }

      switch (button) {
        case 'delete':
          return Inertia.delete(`${window.location.pathname}/${safeId}`, { preserveScroll: true });
        case 'restore':
          return Inertia.put(`${window.location.pathname}/${safeId}`, {}, { preserveScroll: true });
      }

      return false;
    },
  },
});
</script>
