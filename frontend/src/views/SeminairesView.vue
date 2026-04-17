<script setup>
import { ref, onMounted, computed, watch } from 'vue'

// --- ÉTATS DES DONNÉES ---
const allEvents = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// --- ÉTATS DES FILTRES ---
const searchQuery = ref('')
const selectedStatus = ref('upcoming') // Par défaut : événements à venir

const statusOptions = [
  { label: 'À venir', value: 'upcoming' },
  { label: 'Passés', value: 'past' },
  { label: 'Tous', value: 'all' }
]

// --- CONFIGURATION PAGINATION ---
const currentPage = ref(1)
const itemsPerPage = 3
const totalItems = ref(0)

const pageCount = computed(() => Math.ceil(totalItems.value / itemsPerPage))

// --- APPEL API ---
const fetchSeminaires = async () => {
  isLoading.value = true
  try {
    // Construction des paramètres : on force le type à 'Séminaire'
    const params = new URLSearchParams({
      page: currentPage.value,
      limit: itemsPerPage,
      type: 'Séminaire', 
      status: selectedStatus.value !== 'all' ? selectedStatus.value : '',
      q: searchQuery.value
    })

    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/events?${params.toString()}`)
    
    if (!response.ok) throw new Error('Erreur lors du chargement des séminaires')
    
    const result = await response.json()
    allEvents.value = result.data
    totalItems.value = result.total
  } catch (error) {
    errorMessage.value = "Impossible de charger les séminaires."
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

// --- SURVEILLANCE DES CHANGEMENTS (WATCHERS) ---

// Si on change la recherche ou le statut, on reset à la page 1 et on recharge
watch([searchQuery, selectedStatus], () => {
  currentPage.value = 1
  fetchSeminaires()
})

// Changement de page
watch(currentPage, () => {
  fetchSeminaires()
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

onMounted(fetchSeminaires)
</script>

<template>
  <v-container class="py-10" max-width="1000">
    <h2 class="text-h2 font-weight-bold mb-10">Séminaires</h2>

    <v-card variant="flat" class="pa-4 mb-8 bg-grey-lighten-4">
      <v-row>
        <v-col cols="12" md="8">
          <v-text-field
            v-model="searchQuery"
            label="Rechercher un séminaire"
            prepend-inner-icon="mdi-magnify"
            variant="solo"
            hide-details
            clearable
          ></v-text-field>
        </v-col>

        <v-col cols="12" md="4">
          <v-select
            v-model="selectedStatus"
            :items="statusOptions"
            item-title="label"
            item-value="value"
            label="Période"
            variant="solo"
            hide-details
          ></v-select>
        </v-col>
      </v-row>
    </v-card>

    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
      {{ errorMessage }}
    </v-alert>

    <div v-else>
      <v-row>
        <v-col v-for="event in allEvents" :key="event.id" cols="12">
          <v-card variant="outlined" class="mb-4 border-sm overflow-hidden hover-card">
            <v-row no-gutters>
              <v-col cols="12" md="3" class="bg-primary-lighten-5 d-flex flex-column align-center justify-center pa-4 text-center date-column">
                <v-icon color="primary" size="32" class="mb-2">mdi-calendar-range</v-icon>
                <div class="text-h4 font-weight-bold text-primary">
                  {{ new Date(event.startDate).getDate() }}
                </div>
                <div class="text-uppercase font-weight-bold">
                  {{ new Date(event.startDate).toLocaleDateString('fr-CA', { month: 'short' }) }}
                </div>
                <div class="text-body-2 text-grey-darken-1">
                  {{ new Date(event.startDate).getFullYear() }}
                </div>
              </v-col>

              <v-col cols="12" md="9" class="pa-6">
                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap leading-tight">
                  {{ event.title }}
                </v-card-title>

                <v-card-text class="px-0 text-body-1 mt-2 text-grey-darken-2">
                  <div class="text-truncate-3">
                    {{ event.content }}
                  </div>
                </v-card-text>

                <v-card-actions class="px-0 pb-0">
                  <v-btn 
                    variant="text" 
                    color="primary" 
                    class="font-weight-bold px-0" 
                    :to="{ name: 'seminaire', params: { id: event.id } }"
                  >
                    Voir les détails
                    <v-icon end>mdi-arrow-right</v-icon>
                  </v-btn>
                </v-card-actions>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>

      <v-row v-if="pageCount > 1" justify="center" class="mt-8">
        <v-col cols="12" md="6">
          <v-pagination v-model="currentPage" :length="pageCount" rounded="circle" color="primary"></v-pagination>
        </v-col>
      </v-row>

      <v-empty-state 
        v-if="allEvents.length === 0" 
        icon="mdi-magnify-remove-outline" 
        title="Aucun séminaire trouvé"
        text="Ajustez vos filtres ou votre recherche pour trouver ce que vous cherchez."
      >
        <template v-slot:actions>
          <v-btn color="primary" variant="text" @click="searchQuery = ''; selectedStatus = 'all'">
            Réinitialiser les filtres
          </v-btn>
        </template>
      </v-empty-state>
    </div>
  </v-container>
</template>

<style scoped>
.leading-tight {
  line-height: 1.2 !important;
}
.text-truncate-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
.bg-primary-lighten-5 {
  background-color: #f0f4f8;
}
</style>