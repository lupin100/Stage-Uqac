<script setup>
import { ref } from 'vue'
import TableauAdmin from '../../components/TableauAdmin.vue'

const loading = ref(false)
const selected = ref([])

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Titre', key: 'title' },
  { title: 'Année', key: 'year' },
  { title: 'Type', key: 'publicationType' },
  { title: 'Lien', key: 'externalUrl', sortable: false },
  { title: 'Actions', key: 'actions', sortable: false },
]

const items = ref([
  {
    id: 1,
    title: 'Publication 1',
    year: 2026,
    publicationType: 'Article',
    externalUrl: 'https://example.com/publication1',
  },
  {
    id: 2,
    title: 'Publication 2',
    year: 2026,
    publicationType: 'Article',
    externalUrl: 'https://example.com/publication2',
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
      title="Gestion des publications"
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

      <template #item.year="{ item }">
        {{ item.year }}
      </template>
    </TableauAdmin>
  </v-container>
</template>