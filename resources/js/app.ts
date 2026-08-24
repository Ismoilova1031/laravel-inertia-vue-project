import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

import { createVuetify } from "vuetify";
import AppLayout from "./layouts/AppLayout.vue";

const vuetify = createVuetify();

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const pages = Object.fromEntries(
    Object.entries(
        import.meta.glob("/resources/js/pages/**/*.vue", { eager: true }),
    ).map(([path, page]) => [path.replace("/resources/js", "."), page]),
);

console.log("PAGES:", Object.keys(pages));

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: async (name) => {
        console.log("INERTIA PAGE:", name);
        console.log("LOOKING FOR:", `./pages/${name}.vue`);

        const page = await resolvePageComponent(`./pages/${name}.vue`, pages);

        page.default.layout = page.default.layout || AppLayout;

        return page;
    },

    setup({ el, App, props, plugin }) {
        return createApp({
            render: () => h(App, props),
        })
            .use(vuetify)
            .use(plugin)
            .mount(el);
    },

    progress: {
        color: "#4B5563",
    },
});
