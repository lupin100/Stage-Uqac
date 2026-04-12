<script setup>
import { ref, onMounted, computed } from 'vue'
import defaultAvatar from '../assets/default-avatar.png'

const allPersons = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

const fetchPersons = async () => {
    isLoading.value = true
    try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/persons`)
        if (!response.ok) throw new Error('Erreur lors du chargement des membres')

        const data = await response.json()
        allPersons.value = data
    } catch (error) {
        errorMessage.value = "Impossible de charger la liste des membres."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

const regularMembers = computed(() => {
    return allPersons.value.filter(person => person.role === 'Membre régulier')
})

onMounted(fetchPersons)
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

        <v-row v-else>
            <v-col v-for="person in regularMembers" :key="person.id" cols="12" sm="6" class="mb-6">
                <div class="d-flex align-start">
                    <router-link :to="{ name: 'membre', params: { id: person.id } }">
                        <v-avatar size="130" rounded="0" class="mr-6 bg-grey-lighten-2 elevation-1">
                            <v-img :src="person.photoPath || defaultAvatar" cover></v-img>
                        </v-avatar>
                    </router-link>

                    <div>
                        <router-link :to="{ name: 'membre', params: { id: person.id } }"
                            class="text-h5 member-name text-decoration-none d-block mb-1">
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

        <v-empty-state v-if="!isLoading && regularMembers.length === 0" icon="mdi-account-off-outline"
            title="Aucun membre trouvé"
            text="Il n'y a aucun membre régulier enregistré dans la base de données."></v-empty-state>
    </v-container>
</template>

<style scoped>
.color-title {
    color: #333;
    font-size: 2.2rem !important;
}

/* Ajustement pour l'alignement de la photo si elle est rectangulaire */
:deep(.v-avatar .v-img__img--cover) {
    object-position: top center;
}
</style>