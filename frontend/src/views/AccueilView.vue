<script setup>
import { ref, onMounted } from 'vue'

// Ces variables (refs) vont stocker la réponse du backend
const apiData = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

// Fonction pour appeler le backend Symfony
const fetchPingFromBackend = async () => {
  try {
    // On appelle l'URL de ton conteneur backend (port 8001)
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/ping`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    // On transforme la réponse en objet JavaScript
    const data = await response.json()
    apiData.value = data // On stocke les données dans notre variable réactive
  } catch (error) {
    console.error("Erreur de connexion au backend :", error)
    errorMessage.value = "Impossible de joindre le serveur Symfony."
  } finally {
    isLoading.value = false // Le chargement est terminé, succès ou échec
  }
}

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
  fetchPingFromBackend()
})
</script>

<template>
  <v-container class="py-10 text-center" max-width="1400">
    <v-row justify="center">
      <v-col cols="12" md="8">
        
        <v-icon icon="mdi-home-outline" size="64" color="primary" class="mb-6"></v-icon>

        <h2 class="text-h3 font-weight-bold mb-6">
          Accueil
        </h2>

        <p class="text-body-1 mb-8">
          Bienvenue sur la page d'accueil ! Cette application utilise maintenant toute la puissance de Vuetify 3 pour son design.
        </p>

        <v-card class="mb-8 pa-4" variant="outlined" color="primary">
          <v-card-title>Test de connexion Backend</v-card-title>
          
          <v-card-text>
            <v-progress-circular v-if="isLoading" indeterminate color="primary"></v-progress-circular>
            
            <v-alert v-else-if="errorMessage" type="error" variant="tonal">
              {{ errorMessage }}
            </v-alert>
            
            <div v-else-if="apiData">
              <p class="text-success font-weight-bold mb-2">Connecté avec succès !</p>
              <p><strong>Message :</strong> {{ apiData.message }}</p>
              <p><strong>Date serveur :</strong> {{ apiData.date }}</p>
            </div>
          </v-card-text>
        </v-card>

      </v-col>
    </v-row>
  </v-container>
</template>