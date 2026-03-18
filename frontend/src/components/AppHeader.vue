<script setup>
import logo from '../assets/logo_uqac.svg'
</script>

<template>
  <header class="site-header">
    <!-- Barre noire -->
    <div class="black-bar">
      <div class="black-bar-content">
        <v-img
          :src="logo"
          max-width="120"
          class="logo"
          contain
        />

        <div class="header-title">
          Groupe de recherches informatique (GRI)
        </div>

        <v-text-field
          density="compact"
          variant="solo"
          placeholder="Rechercher..."
          hide-details
          prepend-inner-icon="mdi-magnify"
          class="search-field"
        />
      </div>
    </div>

    <!-- Barre grise -->
    <div class="gray-bar">
      <div class="gray-bar-content">
        <div class="breadcrumb">
          <RouterLink to="/">UQAC</RouterLink>
          <span>&gt;</span>
          <RouterLink to="/sciences">Sciences</RouterLink>
          <span>&gt;</span>
          <RouterLink to="/informatique">Informatique</RouterLink>
          <span>&gt;</span>
          <span>GRI</span>
        </div>

        <div class="lang-switch">
          <a href="#" class="lang-link">
            <img src="https://flagcdn.com/w20/fr.png" alt="Français" />
            <span>Fr</span>
          </a>
          <a href="#" class="lang-link">
            <img src="https://flagcdn.com/w20/gb.png" alt="English" />
            <span>En</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Barre verte -->
    <div class="green-bar">
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
              <v-list-item title="A propos" to="/a-propos" />
              <v-list-item title="Mission" to="/mission" />
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
  </header>
</template>

<style scoped>
.site-header {
  width: 100%;
}

.black-bar {
  background-color: #000;
  width: 100%;
}

.black-bar-content {
  min-height: 92px;
  display: flex;
  align-items: center;
  gap: 24px;
  padding: 0 32px;
}

.logo {
  flex-shrink: 0;
}

.header-title {
  flex: 1;
  text-align: center;
  font-size: 1.2rem;
  font-weight: 600;
  color: white;
}

.search-field {
  max-width: 280px;
  min-width: 220px;
}

.gray-bar {
  background-color: #4a4a4a;
  width: 100%;
  min-height: 36px;
  display: flex;
  align-items: center;
}

.gray-bar-content {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 32px;
  font-size: 14px;
  color: white;
}

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.breadcrumb a {
  color: white;
  text-decoration: none;
  font-weight: 600;
}

.breadcrumb a:hover {
  text-decoration: underline;
}

.lang-switch {
  display: flex;
  align-items: center;
  gap: 14px;
}

.lang-link {
  display: flex;
  align-items: center;
  gap: 4px;
  color: white;
  text-decoration: none;
}

.lang-link img {
  width: 20px;
  display: block;
}

.green-bar {
  width: 100%;
}

:deep(.v-tab) {
  font-size: 17px;
  font-weight: 500;
}

*,
*::before,
*::after {
  box-sizing: border-box;
}
</style>