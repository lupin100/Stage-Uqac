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
        Groupe de recherches informatique (GRI)
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
      bg-color="#6b8915"
      color="white"
      grow
    >
      <v-tab to="/">Accueil</v-tab>

      <v-tab>
        Laboratoires
        <v-icon size="16" class="ml-1">mdi-chevron-down</v-icon>
        <v-menu activator="parent">
          <v-list>
            <v-list-item title="A propos" to="a-propos" />
            <v-list-item title="Mission" to="mission" />
            <v-list-item title="Valeurs" to="/valeurs" />
            <v-list-item title="Comités scientifique et exécutif" to="/laboratoires/4" />
            <v-list-item title="Conseil stratégique" to="/laboratoires/5" />
          </v-list>
        </v-menu>
      </v-tab>

      <v-tab>
        Membres
        <v-icon size="16" class="ml-1">mdi-chevron-down</v-icon>
        <v-menu activator="parent">
          <v-list>
            <v-list-item title="Membres réguliers" to="/membres/1" />
            <v-list-item title="Membres associé.e.s" to="/membres/2" />
            <v-list-item title="Membres émérites" to="/membres/3" />
            <v-list-item title="Collaborateurs" to="/membres/4" />
            <v-list-item title="Étudiant.e.s" to="/membres/5" />
            <v-list-item title="Ancien.ne.s étudiant.e.s" to="/membres/6" />
          </v-list>
        </v-menu>
      </v-tab>

      <v-tab to="/thematiques">Thématiques</v-tab>
      <v-tab to="/publications">Publications</v-tab>
      <v-tab to="/projets">Projets</v-tab>

      <v-tab>
        Évènements
        <v-icon size="16" class="ml-1">mdi-chevron-down</v-icon>
        <v-menu activator="parent">
          <v-list>
            <v-list-item title="Séminaires" to="/evenements/seminaires" />
            <v-list-item title="Congrès et ateliers" to="/evenements/congresetateliers" />
          </v-list>
        </v-menu>
      </v-tab>

      <v-tab to="/nouvelles">Nouvelles</v-tab>
      <v-tab to="/nous-joindre">Nous joindre</v-tab>
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

:deep(.v-tab) {
  font-size: 17px;
  font-weight: 500;
}
</style>