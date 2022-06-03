export type ScreenSize = 'mobile' | 'sm' | 'md' | 'lg' | 'xl' | '2xl';

export type ScreenBreakpoints = {
  [K in ScreenSize]: Breakpoint
};

export type Breakpoint = { from: number, to: number };

export type Navigation = {
  dashboards: NavigationChild[];
  blueprints: { label: string, blueprints: NavigationChild[] }[];
};

export type NavigationChild = {
  label: string;
  slug: string;
};

export type BlueprintTableMetaSet = {
  title: string;
  headers: {
    label: string;
    column: string;
    component: string;
    sortable: boolean;
  }[];
};

export type BlueprintTableDataSet = {
  currentPage: number;
  hasNextPage: boolean;
  hasPreviousPage: boolean;
  numberOfPages: number;
  items: { [K: string]: any }[];
};

export type BlueprintTableSortableDataSet = {
  column: string,
  direction: 'asc' | 'desc',
};

export type BlueprintTableQueryStringParameters = {
  page: number,
  sortItem: string,
  sortDirection: 'asc' | 'desc',
};

export type PaginationData = { label: string, goTo: number }[];
