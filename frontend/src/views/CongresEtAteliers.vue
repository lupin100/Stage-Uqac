<script setup>
import { ref, onMounted, computed, watch } from 'vue'

// --- ÉTATS DES DONNÉES ---
const allEvents = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// --- ÉTATS DES FILTRES ---
const searchQuery = ref('')
const selectedStatus = ref('upcoming') // Par défaut : à venir

const statusOptions = [
    { label: 'À venir', value: 'upcoming' },
    { label: 'Passés', value: 'past' },
    { label: 'Tous', value: 'all' }
]

// --- LOGIQUE DE FILTRAGE ET PAGINATION (CLIENT-SIDE) ---
const currentPage = ref(1)
const itemsPerPage = 3

// 1. Filtrage global (Type + Recherche + Statut)
const filteredEvents = computed(() => {
    return allEvents.value.filter(event => {
        // Uniquement Congrès et Ateliers
        const isRightType = event.eventType === 'Congrès' || event.eventType === 'Atelier'

        // Recherche textuelle
        const matchesSearch = event.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            event.content.toLowerCase().includes(searchQuery.value.toLowerCase())

        // Filtre de statut (temporel)
        const eventDate = new Date(event.startDate)
        const now = new Date()
        let matchesStatus = true
        if (selectedStatus.value === 'upcoming') matchesStatus = eventDate >= now
        else if (selectedStatus.value === 'past') matchesStatus = eventDate < now

        return isRightType && matchesSearch && matchesStatus
    })
})

// 2. Pagination
const paginatedEvents = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    const end = start + itemsPerPage
    return filteredEvents.value.slice(start, end)
})

const pageCount = computed(() => Math.ceil(filteredEvents.value.length / itemsPerPage))

// --- APPEL API ---
const fetchEvents = async () => {
    isLoading.value = true
    try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/events`)
        if (!response.ok) throw new Error('Erreur API')
        const data = await response.json()
        allEvents.value = data
    } catch (error) {
        errorMessage.value = "Impossible de charger les événements."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// Reset pagination si on change un filtre
watch([searchQuery, selectedStatus], () => {
    currentPage.value = 1
})

onMounted(fetchEvents)
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Congrès et Ateliers</h2>

        <v-card variant="flat" class="pa-4 mb-8">
            <v-row>
                <v-col cols="12" md="8">
                    <v-text-field v-model="searchQuery" label="Rechercher un congrès ou un atelier"
                        prepend-inner-icon="mdi-magnify" variant="solo" hide-details clearable></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                    <v-select v-model="selectedStatus" :items="statusOptions" item-title="label" item-value="value"
                        label="Période" variant="solo" hide-details></v-select>
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
                <v-col v-for="event in paginatedEvents" :key="event.id" cols="12">
                    <v-card variant="outlined" class="mb-4 border-sm overflow-hidden hover-card">
                        <v-row no-gutters>
                            <v-col cols="12" md="3"
                                class="bg-primary-lighten-5 d-flex flex-column align-center justify-center pa-4 text-center">
                                <v-icon color="primary" size="32" class="mb-2">
                                    {{ event.eventType === 'Congrès' ? 'mdi-account-group' : 'mdi-tools' }}
                                </v-icon>
                                <div class="text-h4 font-weight-bold text-primary">
                                    {{ new Date(event.startDate).getDate() }}
                                </div>
                                <div class="text-uppercase font-weight-bold">
                                    {{ new Date(event.startDate).toLocaleDateString('fr-CA', { month: 'short' }) }}
                                </div>
                                <div class="text-body-2 text-grey-darken-1">
                                    {{ new Date(event.startDate).getFullYear() }}
                                </div>
                            </v-col>

                            <v-col cols="12" md="9" class="pa-6">
                                <div class="d-flex align-center mb-2">
                                    <v-chip color="primary" variant="flat" size="small" class="mr-3 font-weight-bold">
                                        {{ event.eventType }}
                                    </v-chip>
                                </div>

                                <v-card-title class="text-h5 font-weight-bold px-0 pt-0 text-wrap leading-tight">
                                    {{ event.title }}
                                </v-card-title>

                                <v-card-text class="px-0 text-body-1 mt-2 text-grey-darken-2">
                                    <div class="text-truncate-3">
                                        {{ event.content }}
                                    </div>
                                </v-card-text>

                                <v-card-actions class="px-0 pb-0">
                                    <v-btn variant="text" color="primary" class="font-weight-bold px-0"
                                        :to="{ name: 'congres-et-atelier', params: { id: event.id } }">
                                        Voir les détails
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
                    <v-pagination v-model="currentPage" :length="pageCount" rounded="circle"
                        color="primary"></v-pagination>
                </v-col>
            </v-row>

            <v-empty-state v-if="filteredEvents.length === 0" icon="mdi-magnify-remove-outline" title="Aucun résultat"
                text="Nous n'avons trouvé aucun événement correspondant à vos critères.">
                <template v-slot:actions>
                    <v-btn color="primary" variant="text" @click="searchQuery = ''; selectedStatus = 'all'">
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

.text-truncate-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hover-card {
    transition: all 0.2s ease;
}

.hover-card:hover {
    border-color: rgb(var(--v-theme-primary)) !important;
    background-color: #fafafa;
}

.bg-primary-lighten-5 {
    background-color: #f0f4f8;
}
</style>