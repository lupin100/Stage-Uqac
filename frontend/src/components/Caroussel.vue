<script setup>
import { ref, onMounted, computed } from 'vue'

const newsData = ref([])
const isLoadingNews = ref(true)
const newsError = ref(null)

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

const latestNews = computed(() =>
    [...newsData.value]
        .sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt))
        .slice(0, 4)
)

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
    fetchNews()
})

</script>
<template>
  <v-progress-circular
    v-if="isLoadingNews"
    indeterminate
    color="primary"
    class="mt-4"
  />

  <v-alert
    v-else-if="newsError"
    type="error"
    variant="tonal"
    class="mt-4"
  >
    {{ newsError }}
  </v-alert>

  <v-carousel
    v-else
    height="420"
    hide-delimiter-background
    show-arrows="hover"
  >
    <v-carousel-item
      v-for="news in latestNews"
      :key="news.id"
      transition="false"
      reverse-transition="false"
    >
      <v-img
        :src="news.imagePath"
        class="news-image"
        cover
        height="420"
      />
    </v-carousel-item>
  </v-carousel>
</template>