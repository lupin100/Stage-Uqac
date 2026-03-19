<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

import { breadcrumbStore } from '../main.js' // On importe le store ici aussi pour l'affichage des titres

const route = useRoute()

const breadcrumbs = computed(() => {
  const metaBreadcrumbs = route.meta?.breadcrumb || []
  
  return metaBreadcrumbs.map(item => {
    // Si on détecte notre mot-clé magique, on remplace le titre
    if (item.title === 'Nouvelle/:id') {
      return {
        ...item,
        title: breadcrumbStore.dynamicTitle
      }
    }
    return item
  })
})
</script>

<template>
  <nav class="breadcrumb" aria-label="Fil d’Ariane">
    <template v-for="(item, index) in breadcrumbs" :key="`${item.title}-${index}`">
      <RouterLink
        v-if="item.to"
        :to="item.to"
        class="breadcrumb-link"
        :class="{ 'breadcrumb-current': index === breadcrumbs.length - 1 }"
      >
        {{ item.title }}
      </RouterLink>

      <span v-else class="breadcrumb-current">
        {{ item.title }}
      </span>

      <span v-if="index !== breadcrumbs.length - 1" class="breadcrumb-separator">
        &gt;
      </span>
    </template>
  </nav>
</template>

<style scoped>
.breadcrumb {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
  font-size: 14px;
  color: white;
}

.breadcrumb-link {
  color: white;
  text-decoration: none;
}

.breadcrumb-link:hover {
  text-decoration: underline;
}

.breadcrumb-current {
  font-weight: 600;
}

.breadcrumb-separator {
  opacity: 0.7;
}
</style>