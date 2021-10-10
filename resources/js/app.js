require('./bootstrap');

import { createApp } from 'vue'
import App from './components/App.vue';

const app = createApp({
    components: {
        'app': App
    }
}).mount('#app');
