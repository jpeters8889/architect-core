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

export type BlueprintTableHeaderSet = {
  labels: string[];
  columns: string[];
};

export type BlueprintTableDataSet = {
  currentPage: number;
  hasNextPage: boolean;
  hasPreviousPage: boolean;
  numberOfPages: number;
  items: { [K: string]: any }[];
};
