<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const allPublications = ref([]) // Stocke les publications de la page actuelle
const isLoading = ref(true)
const errorMessage = ref(null)

// --- CONFIGURATION PAGINATION SERVEUR ---
const currentPage = ref(1)
const itemsPerPage = 5 
const totalItems = ref(0) // Reçu du backend via result.total

// Calcul dynamique du nombre de pages pour le composant v-pagination
const pageCount = computed(() => {
    return Math.ceil(totalItems.value / itemsPerPage)
})

// --- APPEL API ---
const fetchPublications = async () => {
    isLoading.value = true
    try {
        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/publications?page=${currentPage.value}&limit=${itemsPerPage}`
        )
        
        if (!response.ok) throw new Error('Erreur lors du chargement des publications')
        
        const result = await response.json()

        // Mise à jour des références avec les données du backend
        allPublications.value = result.data 
        totalItems.value = result.total 
    } catch (error) {
        errorMessage.value = "Impossible de charger les publications."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// Relancer l'appel dès que l'utilisateur change de page
watch(currentPage, () => {
    fetchPublications()
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

onMounted(fetchPublications)
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Publications</h2>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
            {{ errorMessage }}
        </v-alert>

        <div v-else>
            <v-row>
                <v-col v-for="pub in allPublications" :key="pub.id" cols="12">
                    <v-card variant="outlined" class="mb-2 border-sm hover-shadow transition-swing">
                        <v-row no-gutters align="center">
                            
                            <v-col cols="12" md="1" class="pa-4 text-center">
                                <div class="text-h6 font-weight-bold text-primary">
                                    {{ pub.year }}
                                </div>
                            </v-col>

                            <v-col cols="12" md="8" class="pa-4">
                                <div class="mb-2">
                                    <v-chip variant="tonal" color="primary" size="x-small" class="font-weight-bold">
                                        {{ pub.publicationType }}
                                    </v-chip>
                                </div>
                                <v-card-title class="text-h6 font-weight-bold px-0 pt-0 text-wrap leading-tight">
                                    {{ pub.title }}
                                </v-card-title>
                            </v-col>

                            <v-col cols="12" md="3" class="pa-4 text-md-right text-center">
                                <v-btn 
                                    v-if="pub.externalUrl"
                                    :href="pub.externalUrl"
                                    target="_blank"
                                    variant="flat" 
                                    color="primary" 
                                    size="small"
                                    prepend-icon="mdi-open-in-new"
                                    rounded="pill"
                                >
                                    Consulter
                                </v-btn>
                                <v-chip v-else size="small" variant="text" color="grey">
                                    <v-icon start size="16">mdi-link-off</v-icon>
                                    Lien non disponible
                                </v-chip>
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
                        density="comfortable"
                    ></v-pagination>
                </v-col>
            </v-row>

            <v-empty-state 
                v-if="allPublications.length === 0" 
                icon="mdi-book-open-variant"
                title="Aucune publication"
                text="Le catalogue des publications est actuellement vide."
            ></v-empty-state>
        </div>
    </v-container>
</template>

<style scoped>
.leading-tight {
    line-height: 1.25 !important;
}

.hover-shadow:hover {
    background-color: #f9f9f9;
    border-color: #6B8915 !important; /* Ton vert GRI */
}

.transition-swing {
    transition: all 0.2s ease-in-out;
}
</style>