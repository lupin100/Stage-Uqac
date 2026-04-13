<script setup>
import { ref, onMounted, computed  } from 'vue'
import AccueilNouvelles from '../components/AccueilNouvelles.vue'
import AccueilEvenements from '../components/AccueilEvenements.vue'
import AccueilPublications from '../components/AccueilPublications.vue'
import Caroussel from '../components/Caroussel.vue'

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
  <v-container class="py-10 " max-width="1400">
    <v-row justify="center">
      <v-col cols="12" md="8">

        <h3 class="text-h3 font-weight-bold text-center mb-6">
          Co-construction transdisciplinaire d’écosystèmes et d’applications connectées 
          socialement responsables et centrées sur l’humain, principalement au sein du domaine de la santé connectée.
        </h3>

        <Caroussel />
        
        <h3 class="text-h3 font-weight-bold mb-6">
          COLLABORATION ET CO-CONSTRUCTION
        </h3>

         <p>
          L’industrie s’attaque à des enjeux majeurs d’innovation. Les ressources de pointe leur sont incontournables. 
          C’est par une collaboration avec les chercheurs et les étudiants du LATECE que leurs défis deviennent des 
          opportunités qui donnent lieu aux percées transformatrices qui, à leur tour, permettent à l’innovation 
          de prendre son essor
         </p>

         <v-row class="mt-10 align-stretch" justify="space-between">
            <v-col cols="12" md="6" class="d-flex">
              <AccueilNouvelles />
            </v-col>
            <v-col cols="12" md="6" class="d-flex">
              <AccueilEvenements/>
            </v-col>
          </v-row>

          <v-row class="mt-10">
            <v-col cols="12">
              <AccueilPublications />
            </v-col>
          </v-row>

      </v-col>
    </v-row>

  </v-container>
</template>