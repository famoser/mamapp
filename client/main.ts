import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.scss'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import './build/fontawesome'
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
  { path: '/mammal/:id', component: MammalPage }
]
const router = createRouter({ history: createWebHistory(), routes })
app.use(router)

app.component('FontAwesomeIcon', FontAwesomeIcon as any)

app.mount('#app')
