// src/main.ts
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './style.css'

const app = createApp(App)

// Install Pinia
const pinia = createPinia()
app.use(pinia)

// Install Router
app.use(router)

app.mount('#app')