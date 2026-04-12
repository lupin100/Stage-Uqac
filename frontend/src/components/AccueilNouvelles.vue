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
    <!-- <v-col cols="12" md="6"> -->
        <div class="section-header">Nouvelles</div>

        <v-progress-circular v-if="isLoadingNews" indeterminate color="primary" class="mt-4" />

        <v-alert v-else-if="newsError" type="error" variant="tonal" class="mt-4">
            {{ newsError }}
        </v-alert>

        <div v-else class="news-list">
            <div v-for="news in latestNews" :key="news.id" class="news-item">
                <div class="news-image-wrapper">
                    <v-img :src="news.imagePath" class="news-image" cover />
                </div>

                <div class="news-content">
                    <p class="news-date">
                        {{ new Date(news.publishedAt).toLocaleDateString('fr-CA', {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        }) }}
                    </p>

                    <router-link :to="{ name: 'nouvelle', params: { id: news.id } }" class="news-title-link">
                        {{ news.title }}
                    </router-link>
                </div>
            </div>
            <div class="section-footer">
                <router-link to="/nouvelles" class="all-link">
                    Toutes les nouvelles +
                </router-link>
            </div>
        </div>
    <!-- </v-col> -->
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

.news-list {
  border-top: 2px solid #bdbdbd;
}

.news-item {
  display: flex;
  gap: 18px;
  padding: 14px 0;
  border-bottom: 2px solid #c7c7c7;
}

.news-image-wrapper {
  width: 190px;
  min-width: 190px;
}

.news-image {
  width: 190px;
  height: 140px;
  border: 1px solid #cfcfcf;
}

.news-content,
.event-content {
  flex: 1;
  text-align: left;
}

.news-date {
  font-size: 0.95rem;
  color: #444;
  margin-bottom: 10px;
  text-transform: capitalize;
}

.news-title {
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.3;
  color: #222;
  margin: 0;
}

.news-title-link {
  color: #2b8ccf;
  text-decoration: none;
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.3;
}

.news-title-link:hover {
  text-decoration: underline;
}

</style>