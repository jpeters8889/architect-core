import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

createInertiaApp({
  // eslint-disable-next-line global-require,import/no-dynamic-require
  resolve: (name) => require(`./Pages/${name}`),
  title: () => 'Foo',
  setup({
    el, app, props, plugin,
  }) {
    createApp({ render: () => h(app, props) })
      .use(plugin)
      .mount(el);
  },
});

InertiaProgress.init();
