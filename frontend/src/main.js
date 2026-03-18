import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css' // Import des icônes

// Création de l'instance Vuetify
const vuetify = createVuetify({
  components,
  directives,
})

// Initialisation de l'appli
const app = createApp(App)

app.use(router)
app.use(vuetify) // injecter Vuetify dans Vue
app.mount('#app')