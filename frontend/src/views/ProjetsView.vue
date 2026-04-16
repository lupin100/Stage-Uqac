<script setup>
import { ref, onMounted, computed, watch } from 'vue'

// États des données
const projects = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// États de la pagination
const currentPage = ref(1)
const totalPages = ref(1)
const itemsPerPage = ref(5)

const fetchProjects = async () => {
    isLoading.value = true
    try {
        // Appel avec paramètres de pagination
        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/projects?page=${currentPage.value}&limit=${itemsPerPage.value}`
        )

        if (!response.ok) throw new Error('Erreur lors du chargement des projets')

        const responseData = await response.json()

        // Si on a reçu un objet de pagination (structure {data, total...})
        if (responseData.data) {
            projects.value = responseData.data
            totalPages.value = responseData.last_page
        } else {
            // Sécurité : si le backend renvoie un tableau simple
            projects.value = responseData
            totalPages.value = 1
        }
    } catch (error) {
        errorMessage.value = "Impossible de charger les projets."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// On recharge les données dès que la page change
watch(currentPage, () => {
    fetchProjects()
    window.scrollTo({ top: 0, behavior: 'smooth' }) // Remonte en haut de page
})

onMounted(fetchProjects)
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Projets de recherche</h2>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
            {{ errorMessage }}
        </v-alert>

        <div v-else>
            <v-row>
                <v-col v-for="project in projects" :key="project.id" cols="12">
                    <v-card variant="outlined" class="mb-4 border-sm hover-shadow transition-swing pa-4 py-2">
                        <v-row align="center" no-gutters>
                            <v-col cols="12">
                                <v-chip size="small" color="primary" class="mb-2 px-2 font-weight-bold" variant="flat">
                                    {{ project.thematic }}
                                </v-chip>

                                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap">
                                    {{ project.title }}
                                </v-card-title>

                                <v-card-subtitle class="px-0 text-body-1 text-grey-darken-2 italic">
                                    <v-icon start size="18">mdi-account-circle-outline</v-icon>
                                    {{ project.fundingSource || 'Auteur non spécifié' }}
                                </v-card-subtitle>

                                <v-card-text v-if="project.summary" class="px-0 pt-4 text-body-2 text-truncate-2">
                                    {{ project.summary }}
                                </v-card-text>
                            </v-col>
                        </v-row>

                        <v-card-actions class="px-0 mt-4">
                            <v-chip 
                                class="px-3 font-weight-bold" 
                                rounded="pill" 
                                :color="project.isFinished ? 'primary' : 'grey-lighten-1'"
                                :class="project.isFinished ? 'text-white' : 'text-grey-darken-3'" 
                                size="small"
                            >
                                {{ project.isFinished ? 'Terminé' : 'En cours' }}
                            </v-chip>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <div class="mt-10 d-flex justify-center">
                <v-pagination
                    v-model="currentPage"
                    :length="totalPages"
                    color="primary"
                    rounded="rounded"
                ></v-pagination>
            </div>

            <v-empty-state 
                v-if="projects.length === 0" 
                icon="mdi-folder-open-outline" 
                title="Aucun projet"
                text="Il n'y a aucun projet à afficher pour le moment."
            ></v-empty-state>
        </div>
    </v-container>
</template>