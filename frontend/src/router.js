import { createRouter, createWebHistory } from 'vue-router'
import AccueilView from './views/AccueilView.vue'
import AProposView from './views/AProposView.vue'
import MissionView from './views/MissionView.vue'
import ValeursView from './views/ValeursView.vue'
import ThematiquesView from './views/ThematiquesView.vue'
import NousJoindreView from './views/NousJoindreView.vue'
import NouvellesView from './views/NouvellesView.vue'
import NouvelleView from './views/NouvelleView.vue'
import SeminairesView from './views/SeminairesView.vue'
import SeminaireView from './views/SeminaireView.vue'

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
    { path: '/nouvelles', name: 'nouvelles', component: NouvellesView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Nouvelles', to: '/nouvelles' } ] 
        }
    },
    { path: '/nouvelles/:id', name: 'nouvelle', component: NouvelleView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Nouvelles', to: '/nouvelles' }, { title: 'Nouvelle/:id', to: '' } ] 
        }
    },
    { path: '/seminaires', name: 'seminaires', component: SeminairesView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Evénements' }, { title: 'Séminaires', to: '/seminaires' } ] 
        }
    },
    { path: '/seminaires/:id', name: 'seminaire', component: SeminaireView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Séminaires', to: '/seminaires' }, { title: 'Nouvelle/:id', to: '' } ] // utiliser le même composant que pour les nouvelles, mais avec une route différente pour différencier les types d'événements
    } }

  ]
})

export default router