<script setup>
import { ref, onMounted, computed  } from 'vue'
import AccueilNouvelles from '../components/AccueilNouvelles.vue'

// Ces variables (refs) vont stocker la réponse du backend
const apiData = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

// Évènements
const eventsData = ref([])
const isLoadingEvents = ref(true)
const eventsError = ref(null)

// Publications
const publicationsData = ref([])
const isLoadingPublications = ref(true)
const publicationsError = ref(null)

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

const fetchPublications = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/publications`, {
      headers: {
        Accept: 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    const data = await response.json()
    publicationsData.value = data
  } catch (error) {
    console.error('Erreur chargement publications :', error)
    publicationsError.value = 'Impossible de charger les publications.'
  } finally {
    isLoadingPublications.value = false
  }
}

const latestEvents = computed(() =>
  [...eventsData.value]
    .sort((a, b) => new Date(b.startDate) - new Date(a.startDate))
    .slice(0, 5)
)

const latestPublications = computed(() =>
  [...publicationsData.value]
    .sort((a, b) => b.year - a.year)
    .slice(0, 3)
)

const formatEventDay = (date) => {
  const d = new Date(date)
  return d.getDate()
}

const formatEventMonth = (date) => {
  const d = new Date(date)
  return d.toLocaleDateString('fr-CA', { month: 'short' }).replace('.', '').toUpperCase()
}

const formatEventYear = (date) => {
  const d = new Date(date)
  return d.getFullYear()
}

const formatEventWeekday = (date) => {
  const d = new Date(date)
  return d.toLocaleDateString('fr-CA', { weekday: 'short' })
}

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
  fetchPingFromBackend()
  fetchEvents()
  fetchPublications()
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

          <!-- ÉVÈNEMENTS -->
          <v-col cols="12" md="6">
            <div class="section-header">Évènements</div>

            <v-progress-circular
              v-if="isLoadingEvents"
              indeterminate
              color="primary"
              class="mt-4"
            />

            <v-alert
              v-else-if="eventsError"
              type="error"
              variant="tonal"
              class="mt-4"
            >
              {{ eventsError }}
            </v-alert>

            <div v-else class="events-list">
              <div
                v-for="event in latestEvents"
                :key="event.id"
                class="event-item"
              >
                <div class="event-date-box">
                  <div class="event-weekday">{{ formatEventWeekday(event.startDate) }}</div>
                  <div class="event-day">{{ formatEventDay(event.startDate) }}</div>
                  <div class="event-month">{{ formatEventMonth(event.startDate) }}</div>
                  <div class="event-year">{{ formatEventYear(event.startDate) }}</div>
                </div>

                <div class="event-content">
                  <p class="event-type">{{ event.eventType }}</p>
                  <!-- <p class="event-title">{{ event.title }}</p> -->
                   <router-link
                      v-if="event.eventType?.toLowerCase() === 'séminaire'"
                      :to="{ name: 'seminaire', params: { id: event.id } }"
                      class="event-title-link"
                    >
                      {{ event.title }}
                    </router-link>

                    <router-link
                      v-else-if="
                        event.eventType?.toLowerCase() === 'congrès' ||
                        event.eventType?.toLowerCase() === 'atelier'
                      "
                      :to="{ name: 'congres-et-atelier', params: { id: event.id } }"
                      class="event-title-link"
                    >
                      {{ event.title }}
                    </router-link>

                    <p v-else class="event-title">
                      {{ event.title }}
                    </p>
                </div>
              </div>
              <div class="publications-header">
                <router-link to="/evenements" class="all-link">
                  Tous les évènements +
                </router-link>
              </div>
            </div>
          </v-col>
        </v-row>

        <!-- PUBLICATIONS -->
        <v-row class="mt-10">
          <v-col cols="12">
            <div class="section-header">Publications</div>

            <v-progress-circular
              v-if="isLoadingPublications"
              indeterminate
              color="primary"
              class="mt-4"
            />

            <v-alert
              v-else-if="publicationsError"
              type="error"
              variant="tonal"
              class="mt-4"
            >
              {{ publicationsError }}
            </v-alert>

            <div v-else class="publications-list">
              <div class="publications-grid">
                <!-- <div
                  v-for="publication in latestPublications"
                  :key="publication.id"
                  class="publication-item"
                >
                  <p class="publication-title">
                    {{ publication.title }}
                  </p>
                </div> -->
                <div
                  v-for="publication in latestPublications"
                  :key="publication.id"
                  class="publication-item"
                >
                  <a
                    v-if="publication.externalUrl"
                    :href="publication.externalUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="publication-title-link"
                  >
                    {{ publication.title }}
                  </a>

                  <v-tooltip v-else text="Lien non disponible" location="top">
                    <template #activator="{ props }">
                      <p
                        v-bind="props"
                        class="publication-title no-link"
                      >
                        {{ publication.title }}
                      </p>
                    </template>
                  </v-tooltip>
                </div>
              </div>

              <div class="publications-header">
                <router-link to="/publications" class="all-link">
                  Toutes les publications +
                </router-link>
              </div>
            </div>

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

.events-list {
  border-top: 2px solid #bdbdbd;
}

.event-item {
  display: flex;
  gap: 18px;
  padding: 14px 0;
  border-bottom: 2px solid #c7c7c7;
}

.event-date-box {
  width: 100px;
  min-width: 100px;
  background-color: #3b3b3b;
  color: white;
  text-align: center;
  padding: 8px 6px;
  line-height: 1.1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.event-weekday {
  font-size: 0.95rem;
  font-weight: 500;
  text-transform: capitalize;
}

.event-day {
  font-size: 2rem;
  font-weight: 700;
}

.event-month {
  font-size: 1.5rem;
  font-weight: 700;
}

.event-year {
  font-size: 1.5rem;
  font-weight: 700;
}

.event-type {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 8px;
}

.event-title {
  font-size: 1.05rem;
  color: #2b8ccf;
  font-weight: 500;
  line-height: 1.35;
  margin: 0;
}

.event-title-link {
  color: #2b8ccf;
  text-decoration: none;
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.3;
}

.event-title-link:hover {
  text-decoration: underline;
}


@media (max-width: 960px) {
  .news-item,
  .event-item {
    align-items: flex-start;
  }

  .news-image-wrapper {
    width: 130px;
    min-width: 130px;
  }

  .news-image {
    width: 130px;
    height: 100px;
  }

  .event-date-box {
    width: 85px;
    min-width: 85px;
  }

  .event-day {
    font-size: 1.6rem;
  }

  .event-month,
  .event-year {
    font-size: 1.15rem;
  }
}

.publications-list {
  border-top: 2px solid #bdbdbd;
  padding-top: 14px;
}

.publications-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}

.publication-item {
  text-align: left;
}

.publication-title {
  font-size: 1rem;
  line-height: 1.35;
  color: #333;
  margin: 0;
}

@media (max-width: 960px) {
  .publications-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
}

.publications-header {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  border-bottom: 2px solid #bdbdbd;
}

.all-link {
  color: #2b8ccf;
  font-weight: 500;
  text-decoration: none;
}

.all-link:hover {
  text-decoration: underline;
}

.publication-title-link {
  display: inline-block;
  color: #2b8ccf;
  text-decoration: none;
  font-weight: 600;
  cursor: pointer;
}

.publication-title-link:visited {
  color: #551A8B;
}

.publication-title-link:hover {
  text-decoration: underline;
}

.no-link {
  cursor: not-allowed;
}
</style>