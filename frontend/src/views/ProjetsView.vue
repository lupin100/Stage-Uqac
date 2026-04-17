<script setup>
import { ref, onMounted, computed, watch } from 'vue'

// --- ÉTATS DES DONNÉES ---
const projects = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// --- ÉTATS DES FILTRES ---
const searchQuery = ref('')
const selectedStatus = ref('all') 
const selectedThematic = ref('Tous')
const thematicOptions = ref(['Tous']) // Initialisé avec 'Tous' par défaut

const statusOptions = [
  { label: 'Tous les états', value: 'all' },
  { label: 'Terminés', value: 'finished' },
  { label: 'En cours', value: 'ongoing' }
]

// --- RÉCUPÉRATION DES THÉMATIQUES DISTINCTES ---
const fetchThematics = async () => {
    try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/projects/thematics`)
        if (!response.ok) throw new Error()
        const data = await response.json()
        
        // On fusionne 'Tous' avec les thématiques reçues de l'API
        thematicOptions.value = ['Tous', ...data]
    } catch (error) {
        console.error("Erreur lors du chargement des thématiques:", error)
        // En cas d'erreur, on garde au moins 'Tous' pour ne pas bloquer l'UI
    }
}

// --- RÉCUPÉRATION DES PROJETS ---
const fetchProjects = async () => {
    isLoading.value = true
    try {
        const params = new URLSearchParams({
            page: currentPage.value,
            limit: itemsPerPage.value,
            q: searchQuery.value,
            status: selectedStatus.value !== 'all' ? selectedStatus.value : '',
            thematic: selectedThematic.value !== 'Tous' ? selectedThematic.value : ''
        })

        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/projects?${params.toString()}`)
        if (!response.ok) throw new Error('Erreur API')
        const responseData = await response.json()

        projects.value = responseData.data || responseData
        totalPages.value = responseData.last_page || 1
    } catch (error) {
        errorMessage.value = "Impossible de charger les projets."
    } finally {
        isLoading.value = false
    }
}

// --- PAGINATION & WATCHERS ---
const currentPage = ref(1)
const totalPages = ref(1)
const itemsPerPage = ref(5)

watch([searchQuery, selectedStatus, selectedThematic], () => {
    currentPage.value = 1
    fetchProjects()
})

watch(currentPage, () => {
    fetchProjects()
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

// --- INITIALISATION ---
onMounted(() => {
    fetchThematics() // Charge la liste des thématiques pour le menu déroulant
    fetchProjects()  // Charge les projets
})

const resetFilters = () => {
    searchQuery.value = ''
    selectedStatus.value = 'all'
    selectedThematic.value = 'Tous'
}
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Projets de recherche</h2>

        <v-card variant="flat" class="pa-4 mb-8">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field v-model="searchQuery" label="Rechercher un projet..." prepend-inner-icon="mdi-magnify"
                        variant="solo" hide-details clearable></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-select v-model="selectedThematic" :items="thematicOptions" label="Thématique" variant="solo"
                        hide-details></v-select>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-select v-model="selectedStatus" :items="statusOptions" item-title="label" item-value="value"
                        label="État" variant="solo" hide-details></v-select>
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
                <v-col v-for="project in projects" :key="project.id" cols="12">
                    <v-card variant="outlined" class="mb-4 border-sm hover-card transition-swing pa-4 py-2">
                        <v-row align="center" no-gutters>
                            <v-col cols="12">
                                <v-chip size="small" color="primary" class="mb-2 px-2 font-weight-bold" variant="flat">
                                    {{ project.thematic }}
                                </v-chip>

                                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap leading-tight">
                                    {{ project.title }}
                                </v-card-title>

                                <v-card-subtitle class="px-0 text-body-1 text-grey-darken-2 italic">
                                    <template v-if="project.contributors?.length > 0">
                                        <span v-for="(contributor, index) in project.contributors"
                                            :key="contributor.id">
                                            <router-link v-if="contributor.person"
                                                :to="{ name: 'membre', params: { id: contributor.person.id } }"
                                                class="text-black text-decoration-none author-link">
                                                {{ contributor.person.firstName }} {{ contributor.person.lastName }}
                                            </router-link>
                                            <span v-else class="text-grey-darken-3">{{ contributor.displayName }}</span>
                                            <span v-if="index < project.contributors.length - 1" class="mr-1">, </span>
                                        </span>
                                    </template>
                                    <span v-else>Auteur non spécifié</span>
                                </v-card-subtitle>

                                <v-card-text v-if="project.summary" class="px-0 pt-4 text-body-2 text-truncate-2">
                                    {{ project.summary }}
                                </v-card-text>
                            </v-col>
                        </v-row>

                        <v-card-actions class="px-0 mt-4">
                            <v-chip class="px-3 font-weight-bold text-white" rounded="pill"
                                :color="project.isFinished ? 'green-darken-1' : 'orange-darken-1'"
                                size="small">
                                {{ project.isFinished ? 'Terminé' : 'En cours' }}
                            </v-chip>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <div v-if="totalPages > 1" class="mt-10 d-flex justify-center">
                <v-pagination v-model="currentPage" :length="totalPages" :total-visible="3" color="primary"
                    rounded="circle"></v-pagination>
            </div>

            <v-empty-state v-if="projects.length === 0" icon="mdi-folder-search-outline" title="Aucun projet trouvé"
                text="Ajustez vos filtres pour trouver ce que vous cherchez.">
                <template v-slot:actions>
                    <v-btn color="primary" variant="text" @click="resetFilters">
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

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.author-link:hover {
    color: rgb(var(--v-theme-primary)) !important;
    text-decoration: underline !important;
}
</style>