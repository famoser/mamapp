import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.scss'
import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import MammalPage from '@/pages/MammalPage.vue'
import { createTranslator, setGlobalTranslator } from '@/locales/translator'
import de from './locales/de.json'

const app = createApp(App)

const translator = createTranslator('de', 'de', { de })
setGlobalTranslator(translator)

const routes = [
  { path: '/', component: HomePage },
  { path: '/species/:id', component: MammalPage }
]
const router = createRouter({ history: createWebHistory(), routes })
app.use(router)

app.mount('#app')
