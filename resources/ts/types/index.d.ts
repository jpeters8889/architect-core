export type ScreenSize = 'mobile' | 'sm' | 'md' | 'lg' | 'xl' | '2xl';

export type ScreenBreakpoints = {
  [K in ScreenSize]: Breakpoint
};

export type Breakpoint = { from: number, to: number };

export type Navigation = {
  dashboards: NavigationChild[];
  blueprints: { label: string, blueprints: NavigationChild[] }[];
};

export type Flash = {
  id: string;
  type: 'success' | 'error';
  message: string;
  show?: boolean;
};

export type NavigationChild = {
  label: string;
  slug: string;
};

export type BlueprintMetaSet = {
  title: string;
  singularTitle: string;
  availableFilters?: BlueprintFilterSetting[];
};

export type BlueprintFilterSetting = {
  key: string,
  label: string,
  options: string[],
};

export type BlueprintTableMetaSet = BlueprintMetaSet & {
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
  start: number,
  end: number,
  totalItems: number,
  items: {
    $meta: BlueprintTableButtonSettings;
    [K: string]: any
  }[];
};

export type BlueprintTableButtonSettings = {
  canDelete: boolean;
  isDeleted: boolean;
  canDuplicate: boolean;
  canEdit: boolean;
  publicUrl?: string | null;
  id: string | number;
};

export type BlueprintTableSortableDataSet = {
  column: string,
  direction: 'asc' | 'desc',
};

export type BlueprintTableQueryStringParameters = {
  page: number,
  sortItem: string,
  sortDirection: 'asc' | 'desc',
  filter?: BlueprintFilter[],
};

export type BlueprintTableButtonEvent = 'delete' | 'restore' | 'edit' | 'open' | 'duplicate';

export type BlueprintFormField = {
  id: string;
  label: string;
  component: string;
  rules: string[];
  helpText?: string;
  meta?: { [K: string]: any },
  value?: string | number | boolean,
};

export type BlueprintFilter = { key: string, filters: string[] };

export type PaginationData = { label: string, goTo: number }[];

export type PaginationSelectOptions = { page: number, label: string };

export type SelectBoxOption = { key: string | number | boolean, value: string };
