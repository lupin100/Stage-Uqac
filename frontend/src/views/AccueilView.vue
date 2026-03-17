<template>
  <v-container class="py-10 text-center" max-width="1400">
    <v-row justify="center">
      <v-col cols="12">
        
        <v-icon icon="mdi-home-outline" size="64" color="primary" class="mb-6"></v-icon>

        <h2 class="text-h3 font-weight-bold mb-6">
          Accueil
        </h2>

        <p class="text-body-1 mb-8">
          Bienvenue sur la page d'accueil ! Cette application utilise maintenant toute la puissance de Vuetify 3 pour son design.
        </p>

        <v-btn color="primary" variant="tonal" size="large" to="/a-propos">
          A Propos
        </v-btn>

      </v-col>
    </v-row>
  </v-container>
</template>