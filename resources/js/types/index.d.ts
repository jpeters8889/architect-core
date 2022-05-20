export type Navigation = {
  dashboards: NavigationChild[];
};

export type NavigationChild = {
  label: string;
  slug: string;
  icon: 'chart';
};
