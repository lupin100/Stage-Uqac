<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const project = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchProjectDetail = async () => {
    try {
        isLoading.value = true
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/projects/${route.params.id}`)
        
        if (!response.ok) {
            throw new Error('Projet introuvable')
        }
        
        const data = await response.json()
        project.value = data
    } catch (error) {
        errorMessage.value = "Impossible de charger les détails du projet."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    fetchProjectDetail()
})
</script>

<template>
    <v-container class="py-10" max-width="900">
        <v-btn variant="text" prepend-icon="mdi-arrow-left" @click="router.back()" class="mb-6">
            Retour aux projets
        </v-btn>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal">
            {{ errorMessage }}
        </v-alert>

        <div v-else-if="project">
            <v-row class="mb-6">
                <v-col cols="12">
                    <div class="d-flex align-center flex-wrap gap-2 mb-4">
                        <v-chip color="primary" variant="flat" class="font-weight-bold">
                            {{ project.thematic }}
                        </v-chip>
                        
                        <v-chip 
                            :color="project.isFinished ? 'green-darken-1' : 'orange-darken-1'" 
                            class="text-white font-weight-bold"
                        >
                            {{ project.isFinished ? 'Terminé' : 'En cours' }}
                        </v-chip>
                    </div>

                    <h1 class="text-h3 font-weight-bold mb-4 leading-tight">
                        {{ project.title }}
                    </h1>

                    <v-divider class="mb-6"></v-divider>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="8">
                    <h3 class="text-h5 font-weight-bold mb-4">Résumé du projet</h3>
                    <div class="text-body-1 summary-text mb-10">
                        {{ project.summary || "Aucun résumé disponible pour ce projet." }}
                    </div>
                </v-col>

                <v-col cols="12" md="4">
                    <v-card variant="outlined" class="pa-4 border-sm bg-grey-lighten-4">
                        <h3 class="text-h6 font-weight-bold mb-4">Équipe du projet</h3>
                        
                        <div v-if="project.contributors?.length > 0">
                            <div v-for="contributor in project.contributors" :key="contributor.id" class="mb-3 d-flex align-center">
                                <v-icon size="small" class="mr-2">mdi-account-circle-outline</v-icon>
                                
                                <router-link 
                                    v-if="contributor.person"
                                    :to="{ name: 'membre', params: { id: contributor.person.id } }"
                                    class="text-primary text-decoration-none font-weight-medium author-link"
                                >
                                    {{ contributor.person.firstName }} {{ contributor.person.lastName }}
                                </router-link>
                                
                                <span v-else class="text-grey-darken-3">
                                    {{ contributor.displayName }}
                                </span>
                            </div>
                        </div>
                        <div v-else class="text-grey italic">
                            Aucun contributeur répertorié.
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<style scoped>
.leading-tight {
    line-height: 1.2 !important;
}

.summary-text {
    line-height: 1.8;
    white-space: pre-line;
    text-align: justify;
}

.author-link:hover {
    text-decoration: underline !important;
}

.gap-2 {
    gap: 8px;
}
</style>