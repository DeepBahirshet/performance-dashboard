import './bootstrap';
import 'vue3-toastify/dist/index.css';
import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import Toast, { toast } from 'vue3-toastify'; 

const appName = document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    resolve: name => {
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

        vueApp.use(Toast);

        // for toast message
        router.on('success', (event) => {
            const flash = event.detail.page.props.flash;

            if (flash?.success) {
                toast.success(flash.success, {
                    autoClose: 2000,
                    position: "top-right",
                });
            }

            if (flash?.error) {
                toast.error(flash.error, {
                    autoClose: 2000,
                    position: "top-right",
                });
            }
        });


        vueApp.mount(el);
    },
    title: title => `${title} - ${appName}`,
});
