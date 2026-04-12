<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'

const route = useRoute()

// On masque le header et footer si le nom de la route est 'login'
const isLoginPage = computed(() => route.name === 'admin-connexion')
</script>

<template>
  <v-app>
    <AppHeader v-if="!isLoginPage" />

    <v-main :class="isLoginPage">
      <router-view />
    </v-main>

    <AppFooter v-if="!isLoginPage" />
  </v-app>
</template>
