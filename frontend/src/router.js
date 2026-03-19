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
import CongresEtAteliersView from './views/CongresEtAteliers.vue'
import CongresEtAtelierView from './views/CongresEtAtelier.vue'
import PublicationsView from './views/PublicationsView.vue'
import EvenementsView from './views/Evenements.vue'

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
          { title: 'Accueil', to: '/' }, { title: 'Nouvelles', to: '/nouvelles' }, { title: 'Nouvelle/:id' } ] 
        }
    },
    { path: '/seminaires', name: 'seminaires', component: SeminairesView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Évènements' }, { title: 'Séminaires', to: '/seminaires' } ] 
        }
    },
    { path: '/seminaires/:id', name: 'seminaire', component: SeminaireView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements'}, { title: 'Séminaires', to: '/seminaires' }, { title: 'Nouvelle/:id' } ] // utiliser la même fonction que pour les nouvelles
    } },
    { path: '/congres-et-ateliers', name: 'congresetateliers', component: CongresEtAteliersView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements' }, { title: 'Congrès et Ateliers', to: '/congres-et-ateliers' } ] }
    },
    { path: '/congres-et-ateliers/:id', name: 'congres-et-atelier', component: CongresEtAtelierView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements'}, { title: 'Congrès et Ateliers', to: '/congres-et-ateliers' }, { title: 'Nouvelle/:id' } ]
    } },
    { path: '/publications', name: 'publications', component: PublicationsView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Publications', to: '/publications' } ] } 
    },
    { path: '/evenements', name: 'evenements', component: EvenementsView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Événements', to: '/evenements' } ] }
    },

  ]
})

export default router