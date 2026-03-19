<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import { breadcrumbStore } from '../main.js' 

const route = useRoute()
const news = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchNewsDetail = async () => {
  try {
    // On utilise l'ID passé dans l'URL : route.params.id
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/news/${route.params.id}`)
    if (!response.ok) throw new Error('Nouvelle introuvable')
    
    const data = await response.json()
    news.value = data

    breadcrumbStore.dynamicTitle = data.title

  } catch (error) {
    errorMessage.value = "Impossible de charger les détails de cette nouvelle."
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('fr-CA', {
    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
  })
}

onMounted(fetchNewsDetail)
</script>

<template>
  <v-container class="py-10" max-width="900">
    <v-btn variant="text" color="primary" to="/nouvelles" class="mb-6 px-0">
      <v-icon start>mdi-arrow-left</v-icon> Retour aux nouvelles
    </v-btn>

    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal">
      {{ errorMessage }}
    </v-alert>

    <div v-else-if="news">
      <v-img
        v-if="news.imagePath"
        :src="news.imagePath"
        height="400"
        cover
        class="rounded-lg mb-8 bg-grey-lighten-2"
      ></v-img>

      <div class="mb-4">
        <span class="text-overline text-grey">Publié le {{ formatDate(news.publishedAt) }}</span>
      </div>

      <h1 class="text-h2 font-weight-bold mb-6">{{ news.title }}</h1>

      <v-divider class="mb-8"></v-divider>

      <div class="text-body-1 text-justify" style="line-height: 1.8; white-space: pre-line;">
        {{ news.content }}
      </div>
    </div>
  </v-container>
</template>