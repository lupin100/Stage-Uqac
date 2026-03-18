import { createRouter, createWebHistory } from 'vue-router'
import AccueilView from './views/AccueilView.vue'
import AProposView from './views/AProposView.vue'
import MissionView from './views/MissionView.vue'
import ValeursView from './views/ValeursView.vue'
import ThematiquesView from './views/ThematiquesView.vue'
import NousJoindreView from './views/NousJoindreView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: AccueilView, meta: {breadcrumb: [ { title: 'Accueil', to: '/' } ] }},
    { path: '/a-propos', name: 'about', component: AProposView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Laboratoires' }, { title: 'À propos', to: '/a-propos' } ] } 
    },
    { path: '/mission', name: 'mission', component: MissionView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Laboratoires' }, { title: 'Mission', to: '/missions' } ] } 
    },
    { path: '/valeurs', name: 'valeurs', component: ValeursView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Laboratoires' }, { title: 'Valeurs', to: '/valeurs' } ] } 
    },
    { path: '/thematiques', name: 'thematiques', component: ThematiquesView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Thématiques', to: '/thematiques' } ]} 
      },
    { path: '/nous-joindre', name: 'contact', component: NousJoindreView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Nous joindre', to: '/nous-joindre' } ] 
        } 
    },
  ]
})

export default router