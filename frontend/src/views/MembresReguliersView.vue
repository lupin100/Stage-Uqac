<script setup>
import { ref, onMounted, watch } from 'vue'
import defaultAvatar from '../assets/default-avatar.png'

// États des données
const members = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// États de la pagination
const currentPage = ref(1)
const totalPages = ref(1)
const itemsPerPage = ref(6)

const fetchMembers = async () => {
    isLoading.value = true
    errorMessage.value = null
    try {
        // On demande au backend uniquement les "Membre régulier" pour cette page
        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/persons?role=Membre régulier&page=${currentPage.value}&limit=${itemsPerPage.value}`
        )

        if (!response.ok) throw new Error('Erreur réseau')

        const responseData = await response.json()

        // On gère les deux formats de réponse possibles du backend
        if (responseData.data) {
            members.value = responseData.data
            totalPages.value = responseData.last_page
        } else {
            members.value = responseData
            totalPages.value = 1
        }

    } catch (error) {
        errorMessage.value = "Impossible de charger les membres."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// Recharger quand on change de page
watch(currentPage, () => {
    fetchMembers()
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

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
            <v-row v-if="members.length > 0">
                <v-col v-for="person in members" :key="person.id" cols="12" sm="6" class="mb-6">
                    <div class="d-flex align-start">
                        <router-link :to="{ name: 'membre', params: { id: person.id } }">
                            <v-avatar size="130" rounded="0" class="mr-6 bg-grey-lighten-2 elevation-1">
                                <v-img :src="person.photoPath || defaultAvatar" cover></v-img>
                            </v-avatar>
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
                                {{ person.institution?.name || 'Institution non spécifiée' }}
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>

            <v-row v-if="totalPages > 1" justify="center" class="mt-8">
                <v-pagination
                    v-model="currentPage"
                    :length="totalPages"
                    color="primary"
                    rounded="rounded"
                ></v-pagination>
            </v-row>

            <v-empty-state v-if="members.length === 0" 
                icon="mdi-account-off-outline" 
                title="Aucun membre trouvé"
                text="La base de données ne contient aucun membre régulier pour le moment.">
            </v-empty-state>
        </div>
    </v-container>
</template>