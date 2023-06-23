import { createApp } from 'vue'
import Cutter from './components/Cutter.vue'
const app = createApp({})

app.component('cutter', Cutter)

app.mount('#cutter')