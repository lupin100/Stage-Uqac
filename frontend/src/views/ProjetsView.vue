<script setup>
import { ref, onMounted } from 'vue'

const allProjects = ref([]) 
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchProjects = async () => {
    isLoading.value = true
    try {
        // On appelle l'index sans paramètres de pagination
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/projects`)
        
        if (!response.ok) throw new Error('Erreur lors du chargement des projets')
        
        const data = await response.json()
        allProjects.value = data 
    } catch (error) {
        errorMessage.value = "Impossible de charger les projets."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

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
                <v-col v-for="project in allProjects" :key="project.id" cols="12">
                    <v-card variant="outlined" class="mb-4 border-sm hover-shadow transition-swing pa-4">
                        <v-row align="center" no-gutters>
                            
                            <v-col cols="12">
                                <v-chip size="x-small" color="primary" class="mb-2 font-weight-bold" variant="flat">
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

                        <v-card-actions class="px-0">
                            <v-badge
                                :color="project.isFinished ? 'grey' : 'success'"
                                :content="project.isFinished ? 'Terminé' : 'En cours'"
                                inline
                            ></v-badge>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <v-empty-state 
                v-if="allProjects.length === 0" 
                icon="mdi-folder-open-outline"
                title="Aucun projet"
                text="Il n'y a aucun projet à afficher pour le moment."
            ></v-empty-state>
        </div>
    </v-container>
</template>

<style scoped>
.hover-shadow:hover {
    background-color: #f9f9f9;
    border-color: #6B8915 !important;
    transform: translateY(-2px);
}

.transition-swing {
    transition: all 0.2s ease-in-out;
}

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.italic {
    font-style: italic;
}
</style>