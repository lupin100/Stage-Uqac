<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { breadcrumbStore } from '../main.js'
import defaultAvatar from '../assets/default-avatar.png'

const route = useRoute()
const person = ref(null)
const isLoading = ref(true)
const errorMessage = ref(null)

const isStudentProfile = computed(() => {
  const role = person.value?.role
  return role === 'Etudiant' || role === 'Ancien étudiant'
})

const fetchPersonDetail = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/persons/details/${route.params.id}`)
    if (!response.ok) throw new Error('Membre introuvable')

    const data = await response.json()
    person.value = data

    // Mise à jour du fil d'Ariane
    breadcrumbStore.dynamicTitle = `${data.firstName} ${data.lastName}`

  } catch (error) {
    errorMessage.value = "Impossible de charger le profil."
    console.error(error)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchPersonDetail)
</script>

<template>
  <v-container class="py-10" max-width="900">
    <div v-if="isLoading" class="text-center py-10">
      <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
    </div>

    <v-alert v-else-if="errorMessage" type="error" variant="tonal">
      {{ errorMessage }}
    </v-alert>

    <div v-else-if="person">
      <h1 class="text-h3 font-weight-bold mb-6">{{ person.firstName }} {{ person.lastName }}</h1>

      <v-row class="mb-10">
        <v-col cols="12" md="4">
          <v-img :src="person.photoPath || defaultAvatar" :width="200" :aspect-ratio="3 / 4" cover
            class="bg-grey-lighten-3 elevation-1"></v-img>
        </v-col>

        <v-col cols="12" md="8">
          <!-- Affichage étudiant/ancien étudiant -->
          <template v-if="isStudentProfile">
            <div class="info-header mb-2">
              <h3 class="text-h5 font-weight-bold">
                Directeur :
                {{ person.studentProfile?.supervisor?.firstName || '' }}
                {{ person.studentProfile?.supervisor?.lastName || 'Non précisé' }}
              </h3>
              <h3 class="text-h5 font-weight-bold">
                Co-Directeur :
                {{ person.studentProfile?.coSupervisor?.firstName }}
                {{ person.studentProfile?.coSupervisor?.lastName }}
              </h3>
              <v-divider class="border-opacity-50 my-2" />
            </div>

            <div class="member-details text-body-1">
              <p class="mb-1">
                <strong>Depuis :</strong>
                {{ person.studentProfile?.studentDegree?.startYear || 'Non précisé' }}
              </p>

              <p class="mb-1">
                <strong>Jusqu'à :</strong>
                {{ person.studentProfile?.studentDegree?.endYear || 'Non précisé' }}
              </p>

              <p class="mb-1">
                <v-icon start size="small">mdi-email-outline</v-icon>
                <a :href="'mailto:' + person.email" class="text-decoration-none text-primary">
                  {{ person.email || 'Email non précisé' }}
                </a>
              </p>

              <p class="mb-1 text-grey-darken-1">
                {{ person.institution?.name || 'Institution non précisée' }}
              </p>
            </div>
          </template>

          <!-- Affichage normal -->
          <template v-else>
            <div class="info-header mb-4">
              <h2 class="text-h5 font-weight-bold">
                {{ person.role || 'Rôle non précisé' }}
              </h2>
              <v-divider class="border-opacity-50 my-2" />
            </div>

            <div class="member-details text-body-1">
              <p class="mb-1">
                <strong>{{ person.jobTitle || 'Profession non spécifiée' }}</strong>
              </p>

              <p class="mb-1 text-grey-darken-1">
                {{ person.departement?.name || 'Département non précisé' }}
              </p>

              <p class="mb-1">
                <v-icon start size="small">mdi-email-outline</v-icon>
                <a :href="'mailto:' + person.email" class="text-decoration-none text-primary">
                  {{ person.email || 'Email non précisé' }}
                </a>
              </p>

              <p class="mb-1 text-grey-darken-1">
                {{ person.institution?.name || 'Institution non précisée' }}
              </p>

              <v-btn
                v-if="person.personalPageUrl"
                :href="person.personalPageUrl"
                target="_blank"
                variant="outlined"
                color="primary"
                size="small"
                class="mt-4"
                prepend-icon="mdi-web"
              >
                Page personnelle
              </v-btn>
            </div>
          </template>
        </v-col>
      </v-row>

      <!-- Bas de page étudiant -->
      <template v-if="isStudentProfile">
        <div class="mb-10">
          <h3 class="text-h5 font-weight-bold mb-4">Sujet de mémoire/thèse</h3>
          <div class="text-body-1 biography-text">
            {{ person.studentProfile?.topic || "Aucun sujet disponible pour le moment." }}
          </div>
        </div>
      </template>

      <!-- Bas de page normal -->
      <template v-else>
        <div class="mb-10">
          <h3 class="text-h5 font-weight-bold mb-4">Biographie</h3>
          <div class="text-body-1 text-justify biography-text">
            {{ person.biography || "Aucune biographie disponible pour le moment." }}
          </div>
        </div>
      </template>
    </div>
  </v-container>
</template>

<style scoped>
.biography-text {
  line-height: 1.8;
  white-space: pre-line;
}

.info-header h2 {
  color: #333;
}

.rounded {
  border: 1px solid #e0e0e0;
}
</style>