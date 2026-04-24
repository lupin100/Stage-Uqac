<script setup>
import { ref, onMounted } from 'vue'
import { usePhoto } from '../composables/usePhoto'

// États des données
const committeeMembers = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

const { getPhotoUrl } = usePhoto()

const fetchCommittee = async () => {
    isLoading.value = true
    errorMessage.value = null
    try {
        const filter = encodeURIComponent('Membre régulier du comité exécutif')
        const response = await fetch(
            `${import.meta.env.VITE_API_URL}/api/persons/filter/${filter}`
        )

        if (!response.ok) throw new Error('Erreur réseau')

        const responseData = await response.json()

        if (responseData.data) {
            committeeMembers.value = responseData.data
        } else {
            committeeMembers.value = responseData
        }

    } catch (error) {
        errorMessage.value = "Impossible de charger les membres du comité."
        console.error(error)
    } finally {
        isLoading.value = false
    }
}

onMounted(fetchCommittee)
</script>

<template>
    <v-container class="py-10" max-width="1200">

        <h2 class="text-h4 font-weight-bold mb-8 color='secondary'">Comités scientifique et exécutif</h2>

        <section class="intro-section mb-12">
            <p class="text-body-1 mb-4 text-justify">
                Le <em>comité scientifique</em> est composé des membres réguliers du LATECE et aborde les affaires liées
                à la
                gouvernance du laboratoire. Il sert de pont entre les activités du labo, du comité exécutif et du
                conseil stratégique et légitime les recommandations du comité exécutif de façon participative par droit
                de vote.
            </p>
            <p class="text-body-1 mb-4 text-justify">
                Le <em>comité exécutif</em> quant à lui mène les travaux administratifs du laboratoire ainsi que ses
                opérations
                quotidiennes dont les relations internes et externes et le développement des partenariats.
            </p>
        </section>


        <h2 class="text-h4 font-weight-bold mb-8 color='secondary'">Comité exécutif</h2>

        <div v-if="isLoading" class="text-center py-10">
            <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </div>

        <v-alert v-else-if="errorMessage" type="error" variant="tonal" class="mb-6">
            {{ errorMessage }}
        </v-alert>

        <div v-else>
            <v-row v-if="committeeMembers.length > 0">
                <v-col v-for="person in committeeMembers" :key="person.id" cols="12" sm="6" class="mb-6">
                    <div class="d-flex align-start">
                        <router-link :to="{ name: 'membre', params: { id: person.id } }">
                            <v-img :src="getPhotoUrl(person.photoPath)" :width="150" :aspect-ratio="3 / 4" cover
                                class="mr-6 bg-grey-lighten-2 elevation-1 rounded"></v-img>
                        </router-link>

                        <div>
                            <router-link :to="{ name: 'membre', params: { id: person.id } }"
                                class="text-headline-small font-weight-semibold text-primary text-decoration-none d-block mb-1">
                                {{ person.lastName }}, {{ person.firstName }}
                            </router-link>

                            <div class="text-body-1 font-weight-bold text-grey-darken-3">
                                {{ person.jobTitle }}
                            </div>

                            <div class="text-body-1 text-grey-darken-1">
                                {{ person.institution?.name || 'Institution non spécifiée' }}
                            </div>

                            <div class="text-caption text-grey-darken-1 mt-1">
                                {{ person.departement?.name }}
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>

            <v-empty-state v-if="committeeMembers.length === 0" icon="mdi-account-group-outline"
                title="Comité en cours de formation" text="Aucun membre n'est actuellement assigné au comité exécutif.">
            </v-empty-state>
        </div>
    </v-container>
</template>