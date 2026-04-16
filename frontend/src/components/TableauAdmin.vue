<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
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
  selected: {
    type: Array,
    default: () => [],
  },
  showSelect: {
    type: Boolean,
    default: false,
  },
  showActions: {
    type: Boolean,
    default: true,
  },
  showCreateButton: {
    type: Boolean,
    default: true,
  },
  showDeleteSelectedButton: {
    type: Boolean,
    default: false,
  },
  createLabel: {
    type: String,
    default: 'Ajouter',
  },
//   searchLabel: {
//     type: String,
//     default: 'Rechercher',
//   },
  noDataText: {
    type: String,
    default: 'Aucune donnée disponible',
  },
})

const emit = defineEmits([
  'create',
  'edit',
  'delete',
  'delete-selected',
  'update:selected',
])

const search = ref('')

const internalSelected = computed({
  get: () => props.selected,
  set: (value) => emit('update:selected', value),
})

const selectedCount = computed(() => internalSelected.value.length)

function handleEdit(item) {
  emit('edit', item)
}

function handleDelete(item) {
  emit('delete', item)
}

function handleDeleteSelected() {
  emit('delete-selected', internalSelected.value)
}
</script>

<template>
  <v-card>
    <v-card-title class="d-flex align-center justify-space-between flex-wrap ga-3">
      <div class="text-h6">
        {{ title }}
      </div>

      <div class="d-flex align-center flex-wrap ga-2">
        <slot name="toolbar-left" />

        <!-- <v-btn
          v-if="showCreateButton"
          color="primary"
          @click="$emit('create')"
        >
          {{ createLabel }}
        </v-btn> -->
        <v-btn
            v-if="showCreateButton"
            icon
            size="x-small"
            color="primary"
            variant="flat"
            class="rounded-sm"
            @click="$emit('create')"
            >
            <v-icon>mdi-plus</v-icon>
        </v-btn>

        <v-btn
          v-if="showDeleteSelectedButton"
          color="error"
          variant="outlined"
          :disabled="selectedCount === 0"
          @click="handleDeleteSelected"
        >
          Supprimer la sélection
        </v-btn>

        <slot name="toolbar-right" :selected="internalSelected" />
      </div>
    </v-card-title>

    <v-card-text>
      <!-- <div class="mb-4">
        <v-text-field
          v-model="search"
          :label="searchLabel"
          prepend-inner-icon="mdi-magnify"
          variant="outlined"
          density="compact"
          hide-details
        />
      </div> -->

      <v-data-table
        v-model="internalSelected"
        :headers="headers"
        :items="items"
        :loading="loading"
        :search="search"
        :item-value="itemValue"
        :show-select="showSelect"
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

        <!-- Colonne actions par défaut -->
        <template
          v-if="showActions"
          #item.actions="{ item }"
        >
          <slot name="item.actions" :item="item">
            <div class="d-flex align-center ga-4">
              <!-- <v-btn
                size="small"
                color="primary"
                variant="text"
                @click="handleEdit(item)"
              >
                Modifier
              </v-btn> -->
              <v-btn
                icon
                size="x-small"
                color="info"
                variant="flat"
                class="rounded-sm"
                @click="handleEdit(item)"
                >
                <v-icon>mdi-pencil</v-icon>
              </v-btn>

              <v-btn
                icon
                size="x-small"
                color="error"
                variant="flat"
                class="rounded-sm"
                @click="handleDelete(item)"
            >
                <v-icon>mdi-delete</v-icon>
            </v-btn>

            </div>
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