<script setup>
import { ref, onMounted } from 'vue'

// Ces variables (refs) vont stocker la réponse du backend
const apiData = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

// Nouvelles
const newsData = ref([])
const isLoadingNews = ref(true)
const newsError = ref(null)

// Évènements
const eventsData = ref([])
const isLoadingEvents = ref(true)
const eventsError = ref(null)

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

 const fetchNews = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/news`, {
      headers: {
        Accept: 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    const data = await response.json()
    newsData.value = data
  } catch (error) {
    console.error('Erreur chargement nouvelles :', error)
    newsError.value = 'Impossible de charger les nouvelles.'
  } finally {
    isLoadingNews.value = false
  }
}

const fetchEvents = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/events`, {
      headers: {
        Accept: 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    const data = await response.json()
    eventsData.value = data
  } catch (error) {
    console.error('Erreur chargement évènements :', error)
    eventsError.value = 'Impossible de charger les évènements.'
  } finally {
    isLoadingEvents.value = false
  }
}

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
  fetchPingFromBackend()
  fetchNews()
  fetchEvents()
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

         <v-row class="mt-8" justify="space-between">
            <!-- Nouvelles -->
            <v-col cols="12" md="6">
              <h3 class="text-h6 font-weight-bold mb-4">Nouvelles</h3>

              <v-progress-circular
                v-if="isLoadingNews"
                indeterminate
                color="primary"
              />

              <v-alert
                v-else-if="newsError"
                type="error"
                variant="tonal"
              >
                {{ newsError }}
              </v-alert>

              <div v-else>
                <v-card
                  v-for="news in newsData"
                  :key="news.id"
                  class="mb-4 pa-3"
                  variant="outlined"
                >
                  <v-row no-gutters>
                    <v-col cols="4">
                      <v-img
                        :src="news.imagePath"
                        height="120"
                        cover
                      />
                    </v-col>

                    <v-col cols="8" class="pl-4 text-left">
                      <p class="text-caption mb-2">
                        {{ new Date(news.publishedAt).toLocaleDateString() }}
                      </p>
                      <p class="text-body-1 font-weight-bold">
                        {{ news.title }}
                      </p>
                    </v-col>
                  </v-row>
                </v-card>
              </div>
            </v-col>

            <!-- Évènements -->
            <v-col cols="12" md="6">
              <h3 class="text-h6 font-weight-bold mb-4">Évènements</h3>

              <v-progress-circular
                v-if="isLoadingEvents"
                indeterminate
                color="primary"
              />

              <v-alert
                v-else-if="eventsError"
                type="error"
                variant="tonal"
              >
                {{ eventsError }}
              </v-alert>

              <div v-else>
                <v-card
                  v-for="event in eventsData"
                  :key="event.id"
                  class="mb-4 pa-3"
                  variant="outlined"
                >
                  <div class="text-left">
                    <p class="text-caption mb-1">
                      {{ new Date(event.date).toLocaleDateString() }}
                    </p>
                    <p class="text-body-1 font-weight-bold mb-1">
                      {{ event.title }}
                    </p>
                    <p class="text-body-2">
                      {{ event.category }}
                    </p>
                  </div>
                </v-card>
              </div>
            </v-col>
          </v-row>


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