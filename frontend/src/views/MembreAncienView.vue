<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { breadcrumbStore } from '../main.js'
import Tableau from '../components/Tableau.vue'

const formerStudents = ref([])
const isLoading = ref(true)
const errorMessage = ref(null)

const headers = [
  { title: 'NOM', key: 'nom', sortable: true },
  { title: 'UNIVERSITÉ', key: 'universite', sortable: true },
  { title: 'STATUT', key: 'statut', sortable: true },
  { title: 'SUPERVISEUR', key: 'directeur', sortable: true },
  { title: 'ANNÉES', key: 'annee', sortable: true },
  { title: 'SUJET', key: 'topic' },
]

const formatYears = (startYear, endYear) => {
  if (startYear && endYear) return `${startYear}/${endYear}`
  if (startYear) return `${startYear}`
  return '—'
}

const fetchFormerStudents = async () => {
  try {
    isLoading.value = true
    errorMessage.value = null
    breadcrumbStore.dynamicTitle = 'Ancien.ne.s étudiant.e.s'

    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/student-profiles/details/anciens-etudiants`
    )

    if (!response.ok) {
      throw new Error('Impossible de récupérer les ancien.ne.s étudiant.e.s')
    }

    const data = await response.json()

    formerStudents.value = Array.isArray(data)
      ? data.map((student) => ({
          ...student,
          annee: formatYears(student.start_year, student.end_year),
        }))
      : []
  } catch (error) {
    errorMessage.value = "Impossible de charger la liste des ancien.ne.s étudiant.e.s."
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchFormerStudents)
</script>

<template>
  <v-container class="students-page">
    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64" />
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal">
      {{ errorMessage }}
    </v-alert>

    <div v-else>
      <h2>Ancien.ne.s étudiant.e.s</h2>

      <Tableau
        :headers="headers"
        :items="formerStudents"
        :loading="isLoading"
        item-value="id"
        :items-per-page="10"
        no-data-text="Aucun ancien étudiant trouvé."
        class="students-table"
      >
        <template #item.nom="{ item }">
          <RouterLink
            :to="`/membres/${item.id}`"
            class="student-link"
          >
            {{ item.nom }}
          </RouterLink>
        </template>
      </Tableau>
    </div>
  </v-container>
</template>

<style scoped>
.students-page {
  max-width: 1100px;
  padding-top: 32px;
  padding-bottom: 48px;
}

:deep(.students-table table) {
  table-layout: auto !important;
  width: 100%;
}

:deep(.students-table th),
:deep(.students-table td) {
  white-space: normal;
  vertical-align: top;
}

.student-link {
  color: #0070b8;
  text-decoration: none;
  font-weight: 500;
}

.student-link:hover {
  text-decoration: underline;
}

:deep(.students-table .v-card) {
  box-shadow: none;
  border-radius: 0;
  background: transparent;
}

:deep(.students-table .v-card-text) {
  padding: 0;
}

:deep(.students-table .v-table) {
  border-top: 1px solid #cfcfcf;
}

:deep(.students-table th) {
  font-size: 1rem;
  font-weight: 800;
  padding-top: 28px;
  padding-bottom: 28px;
  border-bottom: 1px solid #cfcfcf;
}

:deep(.students-table th:nth-child(4)),
:deep(.students-table th:nth-child(5)),
:deep(.students-table th:nth-child(6)) {
  color: #222;
}

:deep(.students-table td) {
  padding-top: 22px;
  padding-bottom: 42px;
  font-size: 1rem;
  line-height: 1.55;
  color: #222;
  border-bottom: 1px solid #d9d9d9;
}

/* Répartition des colonnes */
:deep(.students-table th:nth-child(1)),
:deep(.students-table td:nth-child(1)) {
  width: 16%;
}

:deep(.students-table th:nth-child(2)),
:deep(.students-table td:nth-child(2)) {
  width: 12%;
}

:deep(.students-table th:nth-child(3)),
:deep(.students-table td:nth-child(3)) {
  width: 12%;
}

:deep(.students-table th:nth-child(4)),
:deep(.students-table td:nth-child(4)) {
  width: 14%;
}

:deep(.students-table th:nth-child(5)),
:deep(.students-table td:nth-child(5)) {
  width: 8%;
  white-space: nowrap;
}

:deep(.students-table th:nth-child(6)),
:deep(.students-table td:nth-child(6)) {
  width: 38%;
}

@media (max-width: 960px) {
  .page-title {
    font-size: 2.2rem;
    margin-bottom: 32px;
  }
}
</style>