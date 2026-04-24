<script setup>
import { ref, onMounted, computed, watch } from 'vue'

// --- ÉTATS DES DONNÉES ---
const allPublications = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

// --- ÉTATS DES FILTRES ---
const searchQuery = ref('')
const selectedType = ref('Tous')
const selectedYear = ref('Toutes')

const typeOptions = ref(['Tous'])
const yearOptions = ref(['Toutes'])

// --- CONFIGURATION PAGINATION ---
const currentPage = ref(1)
const itemsPerPage = 5
const totalItems = ref(0)

const pageCount = computed(() => {
    return Math.ceil(totalItems.value / itemsPerPage)
})

// --- RÉCUPÉRATION DES OPTIONS DE FILTRES ---
const fetchFilterOptions = async () => {
    try {
        const [resTypes, resYears] = await Promise.all([
            fetch(`${import.meta.env.VITE_API_URL}/api/publications/types`),
            fetch(`${import.meta.env.VITE_API_URL}/api/publications/years`)
        ])
        
        if (resTypes.ok) {
            const types = await resTypes.json()
            typeOptions.value = ['Tous', ...types]
        }
        if (resYears.ok) {
            const years = await resYears.json()
            yearOptions.value = ['Toutes', ...years]
        }
    } catch (error) {
        console.error("Erreur lors du chargement des options de filtres", error)
    }
}

// --- APPEL API PRINCIPAL ---
const fetchPublications = async () => {
    isLoading.value = true
    try {
        const params = new URLSearchParams({
            page: currentPage.value,
            limit: itemsPerPage,
            q: searchQuery.value,
            type: selectedType.value !== 'Tous' ? selectedType.value : '',
            year: selectedYear.value !== 'Toutes' ? selectedYear.value : ''
        })

        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/publications?${params.toString()}`
        )

        if (!response.ok) throw new Error('Erreur lors du chargement des publications')

        const result = await response.json()
        allPublications.value = result.data
        totalItems.value = result.total
    } catch (error) {
        errorMessage.value = "Impossible de charger les publications."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

// --- SURVEILLANCE (WATCHERS) ---

// Si un filtre change, on revient en page 1
watch([searchQuery, selectedType, selectedYear], () => {
    currentPage.value = 1
    fetchPublications()
})

// Navigation
watch(currentPage, () => {
    fetchPublications()
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

const resetFilters = () => {
    searchQuery.value = ''
    selectedType.value = 'Tous'
    selectedYear.value = 'Toutes'
}

onMounted(() => {
    fetchFilterOptions()
    fetchPublications()
})
</script>

<template>
    <v-container class="py-10" max-width="1000">
        <h2 class="text-h2 font-weight-bold mb-10">Publications</h2>

        <v-card variant="flat" class="pa-4 mb-8">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="searchQuery"
                        label="Rechercher par titre, auteur..."
                        prepend-inner-icon="mdi-magnify"
                        variant="solo"
                        hide-details
                        clearable
                    ></v-text-field>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-select
                        v-model="selectedType"
                        :items="typeOptions"
                        label="Type"
                        variant="solo"
                        hide-details
                    ></v-select>
                </v-col>

                <v-col cols="12" sm="6" md="3">
                    <v-select
                        v-model="selectedYear"
                        :items="yearOptions"
                        label="Année"
                        variant="solo"
                        hide-details
                    ></v-select>
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
                                    <v-chip variant="tonal" color="primary" size="small" class="px-2 font-weight-bold">
                                        {{ pub.publicationType }}
                                    </v-chip>
                                </div>
                                <v-card-title class="text-h6 font-weight-bold px-0 pt-0 text-wrap leading-tight">
                                    {{ pub.title }}
                                </v-card-title>

                                <v-card-subtitle class="px-0 text-body-1 italic text-grey-darken-2">
                                    <template v-if="pub.contributors?.length > 0">
                                        <span v-for="(contributor, index) in pub.contributors" :key="contributor.id">
                                            <router-link v-if="contributor.person"
                                                :to="{ name: 'membre', params: { id: contributor.person.id } }"
                                                class="text-black text-decoration-none author-link font-weight-medium">
                                                {{ contributor.person.firstName }} {{ contributor.person.lastName }}
                                            </router-link>
                                            <span v-else class="text-black">{{ contributor.displayName }}</span>
                                            <span v-if="index < pub.contributors.length - 1" class="mr-1">, </span>
                                        </span>
                                    </template>
                                    <span v-else>Auteur inconnu</span>
                                </v-card-subtitle>
                            </v-col>

                            <v-col cols="12" md="3" class="pa-4 text-md-right text-center">
                                <v-btn v-if="pub.externalUrl" :href="pub.externalUrl" target="_blank" variant="flat"
                                    color="primary" size="small" prepend-icon="mdi-open-in-new" rounded="pill">
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
                    <v-pagination v-model="currentPage" :length="pageCount" rounded="circle" color="primary"
                        density="comfortable"></v-pagination>
                </v-col>
            </v-row>

            <v-empty-state 
                v-if="allPublications.length === 0" 
                icon="mdi-magnify-remove-outline" 
                title="Aucun résultat"
                text="Nous n'avons trouvé aucune publication pour ces critères."
            >
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
.leading-tight { line-height: 1.25 !important; }

.transition-swing { transition: all 0.2s ease-in-out; }

.author-link:hover {
    color: rgb(var(--v-theme-primary)) !important;
    text-decoration: underline !important;
}
</style>