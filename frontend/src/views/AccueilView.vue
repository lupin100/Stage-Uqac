<script setup>
import { ref, onMounted, computed  } from 'vue'
import AccueilNouvelles from '../components/AccueilNouvelles.vue'
import AccueilEvenements from '../components/AccueilEvenements.vue'
import AccueilPublications from '../components/AccueilPublications.vue'

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

        <v-carousel
          height="420"
          hide-delimiter-background
          show-arrows="hover"
        >
        <!-- cycle de  v-caroussel, auto focus qd ça change d'image - régler ça-->

          <v-carousel-item transition="false"  reverse-transition="false">
            <v-img
              src="https://loremflickr.com/800/600/computer,technology?lock=12"
              cover
              height="420"
              gradient="to top, rgba(0,0,0,.6), transparent"
            >
              <div class="d-flex flex-column align-center justify-center fill-height text-white">
              </div>
            </v-img>
          </v-carousel-item>

          <v-carousel-item transition="false"  reverse-transition="false">
            <v-img
              src="https://loremflickr.com/800/600/computer,technology?lock=27"
              cover
              height="420"
              gradient="to top, rgba(0,0,0,.6), transparent"
            >
              <div class="d-flex flex-column align-center justify-center fill-height text-white">
              </div>
            </v-img>
          </v-carousel-item>

          <v-carousel-item transition="false"  reverse-transition="false">
            <v-img
              src="https://loremflickr.com/800/600/computer,technology?lock=16"
              cover
              height="420"
              gradient="to top, rgba(0,0,0,.6), transparent"
            >
              <div class="d-flex flex-column align-center justify-center fill-height text-white">
              </div>
            </v-img>
          </v-carousel-item>
        </v-carousel>
        
        <h3 class="text-h3 font-weight-bold mb-6">
          COLLABORATION ET CO-CONSTRUCTION
        </h3>

         <p>
          L’industrie s’attaque à des enjeux majeurs d’innovation. Les ressources de pointe leur sont incontournables. 
          C’est par une collaboration avec les chercheurs et les étudiants du LATECE que leurs défis deviennent des 
          opportunités qui donnent lieu aux percées transformatrices qui, à leur tour, permettent à l’innovation 
          de prendre son essor
         </p>

         <v-row class="mt-10" justify="space-between">
            <v-col cols="12" md="6">
              <AccueilNouvelles />
            </v-col>
            <v-col cols="12" md="6">
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

<style scoped>
.section-header {
  display: inline-block;
  background-color: #6b8e00;
  color: white;
  font-weight: 700;
  font-size: 1.2rem;
  padding: 14px 28px;
  margin-bottom: 0;
}

.all-link {
  color: #2b8ccf;
  font-weight: 500;
  text-decoration: none;
}

.all-link:hover {
  text-decoration: underline;
}
</style>