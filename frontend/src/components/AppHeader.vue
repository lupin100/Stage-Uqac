<script setup>
import logo from '../assets/logo_uqac.svg'
</script>

<template>
  <v-app-bar color="black" elevation="4" extended extension-height="56">
    <v-container fluid class="px-8 d-flex align-center">
      <v-img
        :src="logo"
        max-width="120"
        class="mr-4"
        contain
      />

      <v-toolbar-title class="header-title text-center">
        Groupe de recherches en informatique (GRI)
      </v-toolbar-title>

      <v-text-field
        density="compact"
        variant="solo"
        placeholder="Rechercher..."
        hide-details
        prepend-inner-icon="mdi-magnify"
        class="search-field ml-4"
      />
    </v-container>

    <template #extension>
      <div class="nav-bar">
        <v-tabs
          bg-color="green"
          align-tabs="end"
          color="white"
          show-arrows
          grow
        >
          <v-tab>Accueil</v-tab>
          <v-tab>Laboratoires</v-tab>
          <v-tab>Membres</v-tab>
          <v-tab>Thématiques</v-tab>
          <v-tab>Publications</v-tab>
          <v-tab>Projets</v-tab>
          <v-tab>Évènements</v-tab>
          <v-tab>Nouvelles</v-tab>
          <v-tab>Nous joindre</v-tab>
        </v-tabs>
    </div>
    </template>
  </v-app-bar>
</template>

<style scoped>
.header-title {
  flex: 1;
  text-align: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: white;
}

.search-field {
  max-width: 220px;
}

*, *::before, *::after {
  box-sizing: border-box;
}

.nav-bar {
  margin: 0;
  padding: 0;
  width: 100%;
  overflow-x: hidden; /* Empêche physiquement le scroll horizontal */
} 
</style>