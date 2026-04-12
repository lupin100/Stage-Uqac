<script setup>
import { ref, onMounted, computed  } from 'vue'

// Évènements
const eventsData = ref([])
const isLoadingEvents = ref(true)
const eventsError = ref(null)

const fetchEvents = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/events`, {
      headers: {
        Accept: 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error(`Erreur HTTP: ${response.status}`)
    }

    const data = await response.json()
    eventsData.value = data
  } catch (error) {
    console.error('Erreur chargement évènements :', error)
    eventsError.value = 'Impossible de charger les évènements.'
  } finally {
    isLoadingEvents.value = false
  }
}

const latestEvents = computed(() =>
  [...eventsData.value]
    .sort((a, b) => new Date(b.startDate) - new Date(a.startDate))
    .slice(0, 5)
)

const formatEventDay = (date) => {
  const d = new Date(date)
  return d.getDate()
}

const formatEventMonth = (date) => {
  const d = new Date(date)
  return d.toLocaleDateString('fr-CA', { month: 'short' }).replace('.', '').toUpperCase()
}

const formatEventYear = (date) => {
  const d = new Date(date)
  return d.getFullYear()
}

const formatEventWeekday = (date) => {
  const d = new Date(date)
  return d.toLocaleDateString('fr-CA', { weekday: 'short' })
}

// On lance l'appel dès que le composant est monté (affiché à l'écran)
onMounted(() => {
  fetchEvents()
})

</script>

<template>
          <!-- ÉVÈNEMENTS -->
          <!-- <v-col cols="12" md="6"> -->
            <div class="section-header">Évènements</div>

            <v-progress-circular
              v-if="isLoadingEvents"
              indeterminate
              color="primary"
              class="mt-4"
            />

            <v-alert
              v-else-if="eventsError"
              type="error"
              variant="tonal"
              class="mt-4"
            >
              {{ eventsError }}
            </v-alert>

            <div v-else class="events-list">
              <div
                v-for="event in latestEvents"
                :key="event.id"
                class="event-item"
              >
                <div class="event-date-box">
                  <div class="event-weekday">{{ formatEventWeekday(event.startDate) }}</div>
                  <div class="event-day">{{ formatEventDay(event.startDate) }}</div>
                  <div class="event-month">{{ formatEventMonth(event.startDate) }}</div>
                  <div class="event-year">{{ formatEventYear(event.startDate) }}</div>
                </div>

                <div class="event-content">
                  <p class="event-type">{{ event.eventType }}</p>
                  <!-- <p class="event-title">{{ event.title }}</p> -->
                   <router-link
                      v-if="event.eventType?.toLowerCase() === 'séminaire'"
                      :to="{ name: 'seminaire', params: { id: event.id } }"
                      class="event-title-link"
                    >
                      {{ event.title }}
                    </router-link>

                    <router-link
                      v-else-if="
                        event.eventType?.toLowerCase() === 'congrès' ||
                        event.eventType?.toLowerCase() === 'atelier'
                      "
                      :to="{ name: 'congres-et-atelier', params: { id: event.id } }"
                      class="event-title-link"
                    >
                      {{ event.title }}
                    </router-link>

                    <p v-else class="event-title">
                      {{ event.title }}
                    </p>
                </div>
              </div>
              <div class="publications-header">
                <router-link to="/evenements" class="all-link">
                  Tous les évènements +
                </router-link>
              </div>
            </div>
          <!-- </v-col> -->
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

.events-list {
  border-top: 2px solid #bdbdbd;
}

.event-item {
  display: flex;
  gap: 18px;
  padding: 14px 0;
  border-bottom: 2px solid #c7c7c7;
}

.event-date-box {
  width: 100px;
  min-width: 100px;
  background-color: #3b3b3b;
  color: white;
  text-align: center;
  padding: 8px 6px;
  line-height: 1.1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.event-weekday {
  font-size: 0.95rem;
  font-weight: 500;
  text-transform: capitalize;
}

.event-day {
  font-size: 2rem;
  font-weight: 700;
}

.event-month {
  font-size: 1.5rem;
  font-weight: 700;
}

.event-year {
  font-size: 1.5rem;
  font-weight: 700;
}

.event-type {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 8px;
}

.event-title {
  font-size: 1.05rem;
  color: #2b8ccf;
  font-weight: 500;
  line-height: 1.35;
  margin: 0;
}

.event-title-link {
  color: #2b8ccf;
  text-decoration: none;
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.3;
}

.event-title-link:hover {
  text-decoration: underline;
}


@media (max-width: 960px) {
  .event-item {
    align-items: flex-start;
  }

  .event-date-box {
    width: 85px;
    min-width: 85px;
  }

  .event-day {
    font-size: 1.6rem;
  }

  .event-month,
  .event-year {
    font-size: 1.15rem;
  }
}

/* .all-link {
  color: #2b8ccf;
  font-weight: 500;
  text-decoration: none;
}

.all-link:hover {
  text-decoration: underline;
} */

</style>