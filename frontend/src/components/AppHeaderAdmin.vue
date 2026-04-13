<script setup>
import logo from '../assets/logo_uqac.svg'
import { authStore } from '../store/authStore'
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/')
}
</script>

<template>
  <header class="admin-header">
    <div class="black-bar">
      <div class="black-bar-content">
        <router-link to="/" class="d-flex mr-4" style="width: 120px;">
          <v-img :src="logo" width="120" contain />
        </router-link>

        <div class="header-title">
          <router-link to="/admin/dashboard" class="text-decoration-none text-white">
            Panneau d'administration (GRI)
          </router-link>
        </div>

        <v-btn 
          prepend-icon="mdi-logout" 
          color="error" 
          variant="flat" 
          rounded="pill"
          @click="handleLogout"
        >
          Déconnexion
        </v-btn>
      </div>
    </div>

    <div class="admin-nav-bar">
      <v-tabs bg-color="primary" color="white" grow slider-color="white">
        <v-tab to="/admin/nouvelles">Nouvelles</v-tab>
        <v-tab to="/admin/evenements">Événements</v-tab>
        <v-tab to="/admin/publications">Publications</v-tab>
        <v-tab to="/admin/projets">Projets</v-tab>
        <v-tab to="/admin/membres">Membres</v-tab>
        <v-tab to="/admin/etudiants">Étudiants</v-tab>
        <v-tab to="/admin/departements">Départements</v-tab>
        <v-tab to="/admin/institutions">Institutions</v-tab>
      </v-tabs>
    </div>
  </header>
</template>

<style scoped>
.admin-header {
  width: 100%;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.black-bar {
  background-color: #000;
  width: 100%;
}

.black-bar-content {
  min-height: 70px; /* Un peu plus fin que le public */
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
}

.header-title {
  flex: 1;
  text-align: center;
  font-size: 1.2rem;
  font-weight: 600;
}

:deep(.v-tab) {
  text-transform: none;
  font-weight: 600;
  letter-spacing: 0;
  font-size: 0.9rem;
}
</style>