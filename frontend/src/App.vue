<script setup>
// Importation de nos composants enfants
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
</script>

<template>
  <div class="layout">
    <AppHeader />

    <header class="navigation">
      <nav>
        <ul class="nav-links">
          <li><RouterLink to="/">Accueil</RouterLink></li>
          <li><RouterLink to="/a-propos">À propos</RouterLink></li>
        </ul>
      </nav>
    </header>

    <main class="content">
      <router-view />
    </main>

    <AppFooter />
  </div>
</template>

<style>
/* Styles globaux pour toute la page */
body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #f9f9f9;
  color: #333;
  min-width: 100%;
}

/* Le layout en Flexbox permet de garder le footer toujours en bas de l'écran */
.layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.content {
  padding: 3rem 2rem;
  max-width: 800px;
  margin: 0 auto;
  flex: 1; /* Remplit l'espace vide entre le header et le footer */
}
</style>