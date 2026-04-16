<script setup>
defineProps({
  title: {
    type: String,
    default: '',
  },
  headers: {
    type: Array,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  itemValue: {
    type: String,
    default: 'id',
  },
  itemsPerPage: {
    type: Number,
    default: 10,
  },
  noDataText: {
    type: String,
    default: 'Aucune donnée disponible',
  },
})
</script>

<template>
  <v-card>
    <v-card-title
      v-if="title"
      class="d-flex align-center justify-space-between flex-wrap ga-3"
    >
      <div class="text-h6">
        {{ title }}
      </div>

      <slot name="toolbar" />
    </v-card-title>

    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="items"
        :loading="loading"
        :item-value="itemValue"
        :items-per-page="itemsPerPage"
        class="elevation-0"
      >
        <!-- Colonnes personnalisables -->
        <template
          v-for="header in headers"
          :key="header.key"
          #[`item.${header.key}`]="{ item }"
        >
          <slot
            :name="`item.${header.key}`"
            :item="item"
          >
            {{ item?.[header.key] }}
          </slot>
        </template>

        <template #no-data>
          <div class="py-6 text-medium-emphasis text-center">
            {{ noDataText }}
          </div>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>