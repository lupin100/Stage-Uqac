<!-- <script setup>
import { ref, onMounted, computed } from 'vue'

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

const getSafeDate = (date) => {
  const d = new Date(date)
  return isNaN(d) ? null : d
}

const formatEventWeekday = (date) => {
  const d = getSafeDate(date)
  return d
    ? d.toLocaleDateString('fr-CA', { weekday: 'short' }).replace('.', '')
    : ''
}

const formatEventDay = (date) => {
  const d = getSafeDate(date)
  return d ? d.getDate() : ''
}

const formatEventMonth = (date) => {
  const d = getSafeDate(date)
  return d
    ? d.toLocaleDateString('fr-CA', { month: 'short' }).replace('.', '').toUpperCase()
    : ''
}

const formatEventYear = (date) => {
  const d = getSafeDate(date)
  return d ? d.getFullYear() : ''
}

onMounted(() => {
  fetchEvents()
})
</script>

<template>
  <div>
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
          <p class="event-type">
            {{ event.eventType }}
          </p>

          <p class="event-title">
            {{ event.title }}
          </p>
        </div>
      </div>

      <div class="section-footer-link">
        Tous les évènements +
      </div>
    </div>
  </div>
</template>

<style scoped>
.section-header {
  display: inline-block;
  background-color: #6e8f12;
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
  background-color: #3c3c3c;
  color: white;
  text-align: center;
  padding: 8px 6px;
  line-height: 1.1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.event-content {
  flex: 1;
  text-align: left;
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
  font-size: 1.4rem;
  font-weight: 700;
}

.event-year {
  font-size: 1.4rem;
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

.section-footer-link {
  text-align: right;
  color: #2b8ccf;
  font-size: 1rem;
  font-weight: 500;
  margin-top: 14px;
  cursor: pointer;
}

@media (max-width: 960px) {
  .event-date-box {
    width: 85px;
    min-width: 85px;
  }

  .event-day {
    font-size: 1.6rem;
  }

  .event-month,
  .event-year {
    font-size: 1.1rem;
  }
}
</style> -->