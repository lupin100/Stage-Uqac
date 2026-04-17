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
import ProjectsView from './views/ProjetsView.vue'
import MembresReguliersView from './views/MembresReguliersView.vue'
import MembreCollaboView from './views/MembreCollaboView.vue'
import MembresEtudiantsView from './views/MembresEtudiantsView.vue'
import MembreAncienView from './views/MembreAncienView.vue'
import ConnexionView from './views/admin/ConnexionView.vue'
import DashboardView from './views/admin/DashboardView.vue'
import ConseilStrategiqueView from './views/ConseilStrategiqueView.vue'
import ComitesSciExecView from './views/ComitesSciExecView.vue'
import MembresAssociesView from './views/MembresAssociesView.vue'
import MembresEmeritesView from './views/MembresEmeritesView.vue'
import MembresCollabosView from './views/MembresCollabosView.vue'

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
    {
      path: '/conseil-strategique', name: 'conseil-strategique', component: ConseilStrategiqueView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Laboratoires' }, { title: 'Conseil stratégique', to: '/conseil-strategique' } ] }  
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
          { title: 'Accueil', to: '/' }, { title: 'Nouvelles', to: '/nouvelles' }, { title: '/:id' } ] 
        }
    },
    { path: '/seminaires', name: 'seminaires', component: SeminairesView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Évènements' }, { title: 'Séminaires', to: '/seminaires' } ] 
        }
    },
    { path: '/seminaires/:id', name: 'seminaire', component: SeminaireView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements'}, { title: 'Séminaires', to: '/seminaires' }, { title: '/:id' } ]
    } },
    { path: '/congres-et-ateliers', name: 'congresetateliers', component: CongresEtAteliersView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements' }, { title: 'Congrès et Ateliers', to: '/congres-et-ateliers' } ] }
    },
    { path: '/congres-et-ateliers/:id', name: 'congres-et-atelier', component: CongresEtAtelierView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Évènements'}, { title: 'Congrès et Ateliers', to: '/congres-et-ateliers' }, { title: '/:id' } ]
    } },
    { path: '/publications', name: 'publications', component: PublicationsView, meta: {
      breadcrumb: [
          { title: 'Accueil', to: '/' }, { title: 'Publications', to: '/publications' } ] } 
    },
    { path: '/evenements', name: 'evenements', component: EvenementsView, meta: {
      breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Événements', to: '/evenements' } ] }
    },
    {
      path: '/projets', name: 'projets', component: ProjectsView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Projets', to: '/projets' } ] }
    },
    {
      path: '/membres-reguliers', name: 'membres-reguliers', component: MembresReguliersView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Membres réguliers', to: '/membres-reguliers' } ] }

    },
    {
      path: '/membres/:id', name: 'membre', component: MembreCollaboView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Membres' }, { title: '/:id' } ] }
    },
    {
      path: '/membres-associes', name: 'membres-associes', component: MembresAssociesView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Membres associés', to: '/membres-associes' } ] } 
    },
    {
      path: '/membres-emerites', name: 'membres-emerites', component: MembresEmeritesView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Membres émérites', to: '/membres-emerites' } ] }
    },
    {
      path: '/membres-collabos', name: 'membres-collabos', component: MembresCollabosView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Collaborateurs', to: '/membres-collabos' } ] }
    },
    {
      path: '/etudiants', name: 'etudiants', component: MembresEtudiantsView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Etudiant.e.s', to: '/etudiants' } ] }
    },
    {
      path: '/ancien-etudiants', name: 'ancien-etudiants', component: MembreAncienView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Ancien.ne.s étudiant.e.s', to: '/ancien-etudiants' } ] }
    },
    {
      path: '/admin-connexion', name: 'admin-connexion', component: ConnexionView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Admin', to: '/admin-connexion' } ] }
    },
    {
      path: '/admin/dashboard', name: 'admin/dashboard', component: DashboardView, meta: {
        requiresAuth: true,
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Admin', to: '/admin/dashboard' } ] }
    },
    {
      path: '/comites-scientifique-executif', name: 'comites-scientifique-executif', component: ComitesSciExecView, meta: {
        breadcrumb: [ { title: 'Accueil', to: '/' }, { title: 'Comités scientifique et exécutif', to: '/comites-scientifique-executif' } ] }
    }
  ]
})

export default router