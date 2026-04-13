<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { authStore } from './store/authStore'

// Importation des composants
import AppHeader from './components/AppHeader.vue'
import AppHeaderAdmin from './components/AppHeaderAdmin.vue'
import AppFooter from './components/AppFooter.vue'

const route = useRoute()

const isLoginPage = computed(() => route.name === 'admin-connexion')

// Admin si connecté, sinon Public
const showAdminHeader = computed(() => authStore.isAdmin && !isLoginPage.value)
const showPublicHeader = computed(() => !authStore.isAdmin && !isLoginPage.value)
</script>

<template>
  <v-app>
    <AppHeader v-if="showPublicHeader" />

    <AppHeaderAdmin v-if="showAdminHeader" />

    <v-main :class="isLoginPage">
      <router-view />
    </v-main>

    <AppFooter v-if="!isLoginPage && !authStore.isAdmin" />
  </v-app>
</template>