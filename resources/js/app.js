import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

const appName = document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    resolve: name => {
        // This will find ANY .vue file under ./Pages/** (Admin/Offers/Index.vue etc.)
        const pages = import.meta.glob('./Pages/**/*.vue');

        const importPage = pages[`./Pages/${name}.vue`];

        if (!importPage) {
            throw new Error(`Unknown Inertia page: ./Pages/${name}.vue`);
        }

        return importPage().then(module => module.default);
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.use(plugin);

        vueApp.mount(el);
    },
    title: title => `${title} - ${appName}`,
});
