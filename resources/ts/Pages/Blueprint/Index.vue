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
    <div class="flex flex-col justify-between items-center overflow-hidden">
      <div class="w-full flex justify-end items-center p-2 pb-0 space-x-2 xl:p-4">
        <Searcher
          v-if="metas.searchable"
          :current-search="queryStringParameters?.search || ''"
          @search-executed="handleSearch"
        />
        <FilterSidebar
          v-if="metas.availableFilters"
          :filters="metas.availableFilters"
          :current-filters="currentValues.filters"
          @filters-updated="filtersUpdated"
        />
      </div>

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
                    :current-sort="currentValues.sort"
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

      <Paginator
        :current-page="data.currentPage"
        :number-of-pages="data.numberOfPages"
        :start-item="data.start"
        :end-item="data.end"
        :total-items="data.totalItems"
        @go-to-page="goToPage"
      />
    </div>
  </CardSkeleton>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Architect from '../../Layouts/Architect.vue';
import {
  BlueprintFilter, BlueprintListCurrentValueDataSet,
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
import FilterSidebar from '../../Components/Blueprints/FilterSidebar.vue';
import Searcher from '../../Components/Blueprints/Searcher.vue';

export default defineComponent({
  components: {
    Searcher,
    FilterSidebar,
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
    currentValues: {
      required: true,
      type: Object as () => BlueprintListCurrentValueDataSet,
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
      sortItem: this.currentValues.sort.column,
      sortDirection: this.currentValues.sort.direction,
      search: this.currentValues.search,
      filter: [],
    };

    if (this.currentValues.filters) {
      this.currentValues.filters.forEach((filter: BlueprintFilter) => {
        this.queryStringParameters.filter?.push(filter);
      });
    }

    if (this.currentValues.search) {
      this.queryStringParameters.search = this.currentValues.search;
    }
  },

  methods: {
    processQueryStrings(): { [T: string]: string | number } {
      const parameters: { [T: string]: any } = this.queryStringParameters;

      if (parameters.filter?.length) {
        parameters.filter
          .map((filter: BlueprintFilter) => {
            filter.filters = filter.filters.filter((filterItem) => filterItem !== '');

            return filter;
          })
          .filter((filter: BlueprintFilter) => filter.filters.length > 0)
          .forEach((filter: BlueprintFilter) => {
            parameters[`filter[${filter.key}]`] = filter.filters.join(',');
          });

        delete parameters.filter;
      }

      return parameters;
    },

    rerenderPage() {
      Inertia.reload({ data: this.processQueryStrings(), only: ['data', 'currentValues'], preserveScroll: true });
    },

    goToPage(page: number) {
      this.queryStringParameters.page = page;

      this.rerenderPage();
    },

    sortChange(sortable: BlueprintTableSortableDataSet) {
      this.queryStringParameters.sortItem = sortable.column;
      this.queryStringParameters.sortDirection = sortable.direction;

      this.rerenderPage();
    },

    filtersUpdated(filters: BlueprintFilter[]) {
      this.queryStringParameters.page = 1;
      this.queryStringParameters.filter = [];

      filters.forEach((filter: BlueprintFilter) => {
        this.queryStringParameters.filter?.push(filter);
      });

      this.rerenderPage();
    },

    handleSearch(searchTerm: string) {
      this.queryStringParameters.search = searchTerm;

      this.rerenderPage();
    },

    buttonPressed(button: BlueprintTableButtonEvent, id: number | string) {
      let safeId: string | number = id;

      if (typeof id === 'number') {
        safeId = id.toString(10);
      }

      // eslint-disable-next-line default-case
      switch (button) {
        case 'edit':
          return Inertia.get(`${window.location.pathname}/${safeId}`);
        case 'delete':
          return Inertia.delete(`${window.location.pathname}/${safeId}`, { preserveScroll: true });
        case 'restore':
          return Inertia.put(`${window.location.pathname}/${safeId}`, {}, { preserveScroll: true });
        case 'duplicate':
          return Inertia.get(`${window.location.pathname}/create/?from=${safeId}`);
      }

      return false;
    },
  },
});
</script>
