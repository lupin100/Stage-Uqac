<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { breadcrumbStore } from '../main.js'

const route = useRoute()
const event = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchEventDetail = async () => {
  try {
    // Appel à l'API pour un événement spécifique
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/events/${route.params.id}`)
    if (!response.ok) throw new Error('Événement introuvable')
    
    const data = await response.json()
    event.value = data

    // Mise à jour du fil d'Ariane avec le titre du séminaire
    breadcrumbStore.dynamicTitle = data.title
    
  } catch (error) {
    errorMessage.value = "Impossible de charger les détails du séminaire."
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

// Helpers de formatage
const formatDateFull = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-CA', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  })
}

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString('fr-CA', {
    hour: '2-digit', minute: '2-digit'
  })
}

onMounted(fetchEventDetail)
</script>

<template>
  <v-container class="py-10" max-width="900">
    <v-btn variant="text" color="primary" to="/seminaires" class="mb-6 px-0">
      <v-icon start>mdi-arrow-left</v-icon> Retour aux séminaires
    </v-btn>

    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal">
      {{ errorMessage }}
    </v-alert>

    <div v-else-if="event">
      <v-sheet border rounded="lg" class="pa-6 mb-8 bg-grey-lighten-4">
        <v-row align="center">
          <v-col cols="12" md="auto">
             <v-avatar color="primary" size="80" rounded="lg">
                <v-icon color="white" size="40">mdi-presentation</v-icon>
             </v-avatar>
          </v-col>
          <v-col>
            <h1 class="text-h3 font-weight-bold mb-2">{{ event.title }}</h1>
            
            <div class="d-flex flex-wrap align-center text-body-1 text-grey-darken-2">
              <v-icon start size="20">mdi-calendar-clock</v-icon>
              <span class="font-weight-bold mr-2">{{ formatDateFull(event.startDate) }}</span>
              <v-divider vertical class="mx-3"></v-divider>
              <v-icon start size="20">mdi-clock-outline</v-icon>
              <span>{{ formatTime(event.startDate) }} — {{ formatTime(event.endDate) }}</span>
            </div>
          </v-col>
        </v-row>
      </v-sheet>

      <div class="content-section">
        <div class="text-body-1 text-justify" style="line-height: 1.8; white-space: pre-line;">
          {{ event.content }}
        </div>
      </div>

    </div>
  </v-container>
</template>

<style scoped>
.content-section {
  color: #333;
}
</style>