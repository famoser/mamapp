<script setup lang="ts">
import { useTranslator } from '@/locales/translator'
import { useDatabase } from '@/services/data'
import FamilyCollapse from '@/components/FamilyCollapse.vue'
import { computed } from 'vue'
import { useMammalsState } from '@/services/state'
import type { Animal } from '@/domain/Animal'

const { t } = useTranslator()
const { favorites, toggleFavorite } = useMammalsState()

const { species } = useDatabase()
const animalsByFamily = computed(() => {
  const map = new Map<string, Animal[]>()
  species.map((s) => {
    const list = map.get(s.family) ?? []
    list.push(s)
    map.set(s.family, list)
  })

  return map
})
</script>

<template>
  <h3 class="mb-3">{{ t('pages.home.title') }}</h3>
  <family-collapse v-for="[family, animals] in animalsByFamily.entries()" :key="family" :family="family" :animals="animals" :favorites="favorites" @toggle-favorite="toggleFavorite($event.id)" />
</template>
