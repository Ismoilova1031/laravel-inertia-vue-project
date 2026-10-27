
import { createApp, h, type DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

import { createVuetify } from "vuetify";
import AppLayout from "./layouts/AppLayout.vue";

const vuetify = createVuetify();

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

type PageModule = {
    default: DefineComponent & {
        layout?: typeof AppLayout;
    };
};

const pages = import.meta.glob<PageModule>("./pages/**/*.vue");

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: async (name) => {
        const path = `./pages/${name}.vue`;
        const importPage = pages[path];

        if (!importPage) {
            throw new Error(`Page not found: ${path}`);
        }

        const module = await importPage();
        const page = module.default;

        page.layout = page.layout || AppLayout;

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
