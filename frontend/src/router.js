import { createRouter, createWebHistory } from 'vue-router'
import AccueilView from './views/AccueilView.vue'
import AProposView from './views/AProposView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: AccueilView },
    { path: '/a-propos', name: 'about', component: AProposView }
  ]
})

export default router