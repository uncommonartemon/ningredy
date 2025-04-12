import { createApp, h } from 'vue'
import { Link, createInertiaApp } from '@inertiajs/vue3'


createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    
    app.config.warnHandler = () => {}

    app.use(plugin)
      .component('Link', Link)
      .mount(el)
  },
})