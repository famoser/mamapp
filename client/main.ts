import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.scss'
import { createRouter, createWebHistory } from 'vue-router'
import HomePage from '@/pages/HomePage.vue'
import SpeciesPage from '@/pages/SpeciesPage.vue'
import { createTranslator, setGlobalTranslator } from '@/locales/translator'
import de from './locales/de.json'
import FavoritesPage from '@/pages/FavoritesPage.vue'
import TextPage from '@/pages/TextPage.vue'

const app = createApp(App)

const translator = createTranslator('de', 'de', { de })
setGlobalTranslator(translator)

const routes = [
  { path: '/', component: HomePage },
  { path: '/favorites/:index', component: FavoritesPage },
  { path: '/species/:id', component: SpeciesPage },
  { path: '/:key', component: TextPage }
]
const router = createRouter({ history: createWebHistory(), routes })
app.use(router)

app.mount('#app')
