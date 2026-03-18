import { createRouter, createWebHistory } from 'vue-router'
import AccueilView from './views/AccueilView.vue'
import AProposView from './views/AProposView.vue'
import MissionView from './views/MissionView.vue'
import ValeursView from './views/ValeursView.vue'
import ThematiquesView from './views/ThematiquesView.vue'   

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: AccueilView },
    { path: '/a-propos', name: 'about', component: AProposView },
    { path: '/mission', name: 'mission', component: MissionView },
    { path: '/valeurs', name: 'valeurs', component: ValeursView },
    { path: '/thematiques', name: 'thematiques', component: ThematiquesView }
  ]
})

export default router