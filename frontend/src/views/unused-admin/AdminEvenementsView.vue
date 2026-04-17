<script setup>
import { ref } from 'vue'
import TableauAdmin from '../../components/TableauAdmin.vue'

const loading = ref(false)
const selected = ref([])

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Titre', key: 'title' },
  { title: 'Type', key: 'eventType' },
  { title: 'Date de début', key: 'startDate' },
  { title: 'Actions', key: 'actions', sortable: false },
]

const items = ref([
  {
    id: 1,
    title: 'Evenement 1',
    startDate: '2026-04-12',
    eventType: 'Conférence',
    startDate: '2026-04-15',

  },
  {
    id: 2,
    title: 'Evenement 2',
    startDate: '2026-04-10',
    eventType: 'Atelier',
    startDate: '2026-04-20',
  },
])

function onCreate() {
  console.log('Créer')
}

function onEdit(item) {
  console.log('Modifier', item)
}

function onDelete(item) {
  console.log('Supprimer', item)
}

function onDeleteSelected(ids) {
  console.log('Supprimer sélection', ids)
}
</script>

<template>
  <v-container fluid>
    <TableauAdmin
      v-model:selected="selected"
      title="Gestion des évènements"
      :headers="headers"
      :items="items"
      :loading="loading"
      item-value="id"
      show-select
      show-delete-selected-button
      create-label="Ajouter une nouvelle"
      @create="onCreate"
      @edit="onEdit"
      @delete="onDelete"
      @delete-selected="onDeleteSelected"
    >
      <template #item.startDate="{ item }">
        {{ new Date(item.startDate).toLocaleDateString('fr-FR') }}
      </template>
    </TableauAdmin>
  </v-container>
</template>