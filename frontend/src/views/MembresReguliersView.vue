<script setup>
import { ref, onMounted } from 'vue'
import { usePhoto } from '../composables/usePhoto'

const { getPhotoUrl } = usePhoto()
const members = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchMembers = async () => {
    isLoading.value = true
    errorMessage.value = null
    
    try {
        // Préparation des URLs
        const urlRegulier = `${import.meta.env.VITE_API_URL}/api/persons/filter/${encodeURIComponent('Membre régulier')}`
        const urlComite = `${import.meta.env.VITE_API_URL}/api/persons/filter/${encodeURIComponent('Membre régulier du comité exécutif')}`

        // On lance les deux requêtes en parallèle pour gagner du temps
        const [resReguliers, resComite] = await Promise.all([
            fetch(urlRegulier),
            fetch(urlComite)
        ])

        if (!resReguliers.ok || !resComite.ok) throw new Error('Erreur lors de la récupération des données')

        const dataReguliers = await resReguliers.json()
        const dataComite = await resComite.json()

        // Extraction des tableaux (gestion du format { data: [] } ou directement [])
        const list1 = Array.isArray(dataReguliers) ? dataReguliers : (dataReguliers.data || [])
        const list2 = Array.isArray(dataComite) ? dataComite : (dataComite.data || [])

        // On fusionne les deux listes
        members.value = [...list1, ...list2]

    } catch (error) {
        errorMessage.value = "Impossible de charger les membres."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchMembers)
</script>

<template>
    <v-container class="py-10" max-width="1200">
        <h2 class="text-h4 font-weight-bold mb-8 color-title">Membres réguliers</h2>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
            {{ errorMessage }}
        </v-alert>

        <div v-else>
            <v-row v-if="members && members.length > 0">
                <v-col v-for="person in members" :key="person.id" cols="12" sm="6" class="mb-6">
                    <div class="d-flex align-start">
                        <router-link :to="{ name: 'membre', params: { id: person.id } }">
                            <v-img :src="getPhotoUrl(person.photoPath)" :width="150" :aspect-ratio="3 / 4" cover
                                class="mr-6 bg-grey-lighten-2 elevation-1"></v-img>
                        </router-link>

                        <div>
                            <router-link :to="{ name: 'membre', params: { id: person.id } }"
                                class="text-headline-small font-weight-semibold text-primary text-decoration-none d-block mb-1">
                                {{ person.lastName }}, {{ person.firstName }}
                            </router-link>

                            <div class="text-body-1 text-grey-darken-3">
                                {{ person.jobTitle }}
                            </div>

                            <div class="text-body-1 text-grey-darken-1">
                                {{ person.institutions[0]?.name || 'Institution non spécifiée' }}
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>

            <v-empty-state v-else icon="mdi-account-off-outline" title="Aucun membre trouvé"
                text="La base de données ne contient aucun membre régulier pour le moment.">
            </v-empty-state>
        </div>
    </v-container>
</template>