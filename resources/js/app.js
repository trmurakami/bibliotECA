import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Editor from './components/Editor.vue';

const app = new Vue({
    el: '#editor',
    components: {
        Editor
    }
});

