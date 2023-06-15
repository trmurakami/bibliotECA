import { createApp } from 'vue'
import Editor from './components/Editor.vue'
const app = createApp({})

app.component('editor', Editor)

app.mount('#editor')