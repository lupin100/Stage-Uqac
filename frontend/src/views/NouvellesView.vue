<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const allNews = ref([]) // Contiendra uniquement les 3 news de la page actuelle
const isLoading = ref(true)
const errorMessage = ref(null)

// --- CONFIGURATION PAGINATION ---
const currentPage = ref(1)
const itemsPerPage = 3
const totalNews = ref(0) // Nombre total de news en base de données (pour le calcul des pages)

// Calcul du nombre total de pages pour le composant v-pagination
const pageCount = computed(() => {
    return Math.ceil(totalNews.value / itemsPerPage)
})

// --- APPEL API ---
const fetchNews = async () => {
    isLoading.value = true
    try {
        // On envoie les paramètres page et limit à Symfony
        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/news?page=${currentPage.value}&limit=${itemsPerPage}`
        )
        
        if (!response.ok) throw new Error('Erreur lors du chargement des nouvelles')
        
        const result = await response.json()

        // Selon ton contrôleur Symfony :
        allNews.value = result.data   // Les news de la page
        totalNews.value = result.total // Le total global (ex: 50)
        
    } catch (error) {
        errorMessage.value = "Impossible de charger les actualités."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// IMPORTANT : On surveille le changement de page pour relancer l'appel API
watch(currentPage, () => {
    fetchNews()
    // Optionnel : remonter en haut de page lors du changement
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

const formatDate = (dateString) => {
    if (!dateString) return ''
    return new Date(dateString).toLocaleDateString('fr-CA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

onMounted(fetchNews)
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Nouvelles</h2>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
            {{ errorMessage }}
        </v-alert>

        <div v-else>
            <v-row>
                <v-col v-for="news in allNews" :key="news.id" cols="12">
                    <v-card variant="outlined" class="mb-4 border-sm overflow-hidden">
                        <v-row no-gutters>

                            <v-col v-if="news.imagePath" cols="12" md="4">
                                <v-img :src="news.imagePath" height="100%" min-height="200" cover
                                    class="bg-grey-lighten-2">
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </v-col>

                            <v-col cols="12" :md="news.imagePath ? 8 : 12" class="pa-6 d-flex flex-column">
                                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap">
                                    {{ news.title }}
                                </v-card-title>

                                <div class="mb-0">
                                    <span class="text-overline text-grey">{{ formatDate(news.publishedAt) }}</span>
                                </div>

                                <v-card-text class="px-0 text-body-1 flex-grow-1">
                                    <div class="text-truncate-3">
                                        {{ news.content }}
                                    </div>
                                </v-card-text>

                                <v-card-actions class="px-0 pb-0">
                                    <v-btn variant="text" color="primary" class="font-weight-bold px-0"
                                        :to="{ name: 'nouvelle', params: { id: news.id } }">
                                        Lire la suite
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
                    <v-pagination 
                        v-model="currentPage" 
                        :length="pageCount" 
                        rounded="circle"
                        color="primary"
                    ></v-pagination>
                </v-col>
            </v-row>

            <v-empty-state 
                v-if="allNews.length === 0" 
                icon="mdi-newspaper-variant-outline"
                title="Aucune nouvelle pour le moment"
                text="Revenez plus tard pour consulter les dernières actualités du GRI."
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