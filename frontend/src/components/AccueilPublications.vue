<script setup>
import { ref, onMounted, computed } from 'vue'

// Publications
const publicationsData = ref([])
const isLoadingPublications = ref(true)
const publicationsError = ref(null)

const fetchPublications = async () => {
    try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/publications`, {
            headers: {
                Accept: 'application/json'
            }
        })

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`)
        }

        const data = await response.json()
        publicationsData.value = data
    } catch (error) {
        console.error('Erreur chargement publications :', error)
        publicationsError.value = 'Impossible de charger les publications.'
    } finally {
        isLoadingPublications.value = false
    }
}

const latestPublications = computed(() =>
    [...publicationsData.value]
        .sort((a, b) => b.year - a.year)
        .slice(0, 3)
)

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
    fetchPublications()
})

</script>

<template>
    <div class="section-header">Publications</div>

    <div v-if="isLoadingPublications" class="mt-4">
        <v-progress-circular v-if="isLoadingPublications" indeterminate color="primary" class="mt-4" />
    </div>

    <v-alert v-else-if="publicationsError" type="error" variant="tonal" class="mt-4">
        {{ publicationsError }}
    </v-alert>

    <div v-else class="publications-list">
        <div class="publications-grid">
            <div v-for="publication in latestPublications" :key="publication.id" class="publication-item">
                <a v-if="publication.externalUrl" :href="publication.externalUrl" target="_blank"
                    rel="noopener noreferrer" class="publication-title-link">
                    {{ publication.title }}
                </a>

                <v-tooltip v-else text="Lien non disponible" location="top">
                    <template #activator="{ props }">
                        <p v-bind="props" class="publication-title no-link">
                            {{ publication.title }}
                        </p>
                    </template>
                </v-tooltip>
            </div>
        </div>

        <div class="publications-header">
            <router-link to="/publications" class="all-link">
                Toutes les publications +
            </router-link>
        </div>
    </div>
</template>

<style scoped>
.section-header {
    display: inline-block;
    background-color: #6b8e00;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    padding: 14px 28px;
    margin-bottom: 0;
}

.publications-list {
    border-top: 2px solid #bdbdbd;
    padding-top: 14px;
}

.publications-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

.publication-item {
    text-align: left;
}

.publication-title {
    font-size: 1rem;
    line-height: 1.35;
    color: #333;
    margin: 0;
}

@media (max-width: 960px) {
    .publications-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

.publications-header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    border-bottom: 2px solid #bdbdbd;
}

.publication-title-link {
    display: inline-block;
    color: #2b8ccf;
    text-decoration: none;
    font-weight: 500;
    cursor: pointer;
}

.publication-title-link:visited {
    color: #551A8B;
}

.publication-title-link:hover {
    text-decoration: underline;
}

.no-link {
    cursor: not-allowed;
}

.all-link {
  color: #2b8ccf;
  font-weight: 500;
  text-decoration: none;
}

.all-link:hover {
  text-decoration: underline;
}
</style>