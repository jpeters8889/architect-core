export type ScreenSize = 'mobile' | 'sm' | 'md' | 'lg' | 'xl' | '2xl';

export type ScreenBreakpoints = {
  [K in ScreenSize]: Breakpoint
};

export type Breakpoint = { from: number, to: number };

export type Navigation = {
  dashboards: NavigationChild[];
};

export type NavigationChild = {
  label: string;
  slug: string;
  icon: 'chart';
};
