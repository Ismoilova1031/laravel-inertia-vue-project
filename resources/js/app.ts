
import { createApp, h, type Component, type DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";

import { createVuetify } from "vuetify";
import AppLayout from "./layouts/AppLayout.vue";
import BlankLayout from "./layouts/BlankLayout.vue";

const vuetify = createVuetify();
const appName = import.meta.env.VITE_APP_NAME || "Study Platform";

type PageModule = {
    default: DefineComponent & {
        layout?: Component
    }
};

const pages = import.meta.glob<PageModule>("./pages/**/*.vue");

const blankPrefixes = ["Auth/", "Certificates/"];

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),

    resolve: async (name) => {
        const importPage = pages[`./pages/${name}.vue`];

        if (!importPage) {
            throw new Error(`Page not found: ${name}`);
        }

        const { default: page } = await importPage();

        page.layout ??= blankPrefixes.some((prefix) => name.startsWith(prefix)) ? BlankLayout : AppLayout;

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
