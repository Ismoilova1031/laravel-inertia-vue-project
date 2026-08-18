import { createApp, h } from "vue"
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers"

import "@mdi/font/css/materialdesignicons.css"
import "vuetify/styles"

import { createVuetify } from "vuetify"
import * as components from "vuetify/components"
import * as directives from "vuetify/directives"
import AppLayout from "./Layouts/AppLayout.vue"

const vuetify = createVuetify({
  components,
  directives,
})

const appName = import.meta.env.VITE_APP_NAME || "Laravel"

createInertiaApp({
  title: title => `${title} - ${appName}`,

  resolve: async name => {
    const page = await resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob("./Pages/**/*.vue")
    )
    page.default.layout = page.default.layout || AppLayout

    return page
  },

  setup({ el, App, props, plugin }) {
    return createApp({
      render: () => h(App, props),
    })
      .use(vuetify)
      .use(plugin)
      .mount(el)
  },

  progress: {
    color: "#4B5563",
  },
})