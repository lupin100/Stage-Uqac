<script setup>
// Importation de nos composants enfants
import AppHeader from './components/AppHeader.vue'
import AppFooter from './components/AppFooter.vue'
</script>

<template>
  <div class="layout">
    <AppHeader />

    <main class="content">
      <h2>Bienvenue sur ma page !</h2>
      <p>
        Ceci est une page statique très simple. Le header en haut et le footer en bas 
        sont gérés par des composants Vue.js indépendants, ce qui rend le code propre 
        et facile à maintenir.
      </p>
      <p>
        Vous pouvez ajouter autant de texte ou de composants que vous le souhaitez ici.
      </p>
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