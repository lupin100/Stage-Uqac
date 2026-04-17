<script setup>
import { ref } from 'vue'
import TableauAdmin from '../../components/TableauAdmin.vue'

const loading = ref(false)
const selected = ref([])

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Image', key: 'imagePath', sortable: false },
  { title: 'Titre', key: 'title' },
  { title: 'Date', key: 'publishedAt' },
  { title: 'Actions', key: 'actions', sortable: false },
]

const items = ref([
  {
    id: 1,
    title: 'Nouvelle 1',
    imagePath: '/uploads/news1.jpg',
    publishedAt: '2026-04-12',
  },
  {
    id: 2,
    title: 'Nouvelle 2',
    imagePath: '/uploads/news2.jpg',
    publishedAt: '2026-04-10',
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
      title="Gestion des nouvelles"
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
      <template #item.imagePath="{ item }">
        <v-img
          :src="item.imagePath"
          width="60"
          height="40"
          cover
          class="rounded"
        />
      </template>

      <template #item.publishedAt="{ item }">
        {{ new Date(item.publishedAt).toLocaleDateString('fr-FR') }}
      </template>
    </TableauAdmin>
  </v-container>
</template>