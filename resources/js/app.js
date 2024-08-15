import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// import { ElementPlus } from '../../node_modules/element-plus'
import 'element-plus/dist/index.css'
import 'element-plus/theme-chalk/display.css'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            // .use(ElementPlus, { size: 'small', zIndex: 3000 })
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#48a5b5e6',
        showSpinner: true
    },
});
