<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const allEvents = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// --- CONFIGURATION PAGINATION SERVEUR ---
const currentPage = ref(1)
const itemsPerPage = 3
const totalItems = ref(0)

const pageCount = computed(() => {
  return Math.ceil(totalItems.value / itemsPerPage)
})

const fetchSeminaires = async () => {
  isLoading.value = true
  try {
    // On ajoute &type=Séminaire à l'URL pour que le serveur filtre pour nous
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/events?page=${currentPage.value}&limit=${itemsPerPage}&type=Séminaire`
    )
    
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

// Relancer l'appel au changement de page
watch(currentPage, () => {
  fetchSeminaires()
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

const formatTime = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleTimeString('fr-CA', {
    hour: '2-digit', minute: '2-digit'
  })
}

onMounted(fetchSeminaires)
</script>

<template>
  <v-container class="py-10" max-width="1000">
    <h2 class="text-h2 font-weight-bold mb-10">Séminaires</h2>

    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
      {{ errorMessage }}
    </v-alert>

    <div v-else>
      <v-row>
        <v-col v-for="event in allEvents" :key="event.id" cols="12">
          <v-card variant="outlined" class="mb-4 border-sm overflow-hidden">
            <v-row no-gutters>
              <v-col cols="12" md="3" class="bg-grey-lighten-4 d-flex flex-column align-center justify-center pa-4 text-center">
                <v-icon color="primary" size="32" class="mb-2">mdi-calendar-range</v-icon>
                <div class="text-h5 font-weight-bold text-primary">{{ new Date(event.startDate).getDate() }}</div>
                <div class="text-uppercase font-weight-medium">
                  {{ new Date(event.startDate).toLocaleDateString('fr-CA', { month: 'short' }) }}
                </div>
                <div class="text-caption text-grey-darken-1">{{ new Date(event.startDate).getFullYear() }}</div>
              </v-col>

              <v-col cols="12" md="9" class="pa-6">

                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap">{{ event.title }}</v-card-title>
                <v-card-text class="px-0 text-body-1">
                  <div class="text-truncate-3">{{ event.content }}</div>
                </v-card-text>

                <v-card-actions class="px-0 pb-0">
                  <v-btn variant="text" color="primary" class="font-weight-bold px-0" 
                    :to="{ name: 'seminaire', params: { id: event.id } }">
                    Voir les détails <v-icon end>mdi-arrow-right</v-icon>
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
        icon="mdi-calendar-blank" 
        title="Aucun séminaire prévu"
        text="Il n'y a pas de séminaires programmés pour le moment."
      ></v-empty-state>
    </div>
  </v-container>
</template>

<style scoped>
.text-truncate-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>