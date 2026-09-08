<script setup lang="ts">
import { useTranslator } from '@/locales/translator'
import { useDatabase } from '@/services/data'
import FamilyCollapse from '@/components/FamilyCollapse.vue'
import { computed, ref } from 'vue'
import { useMammalsState } from '@/services/state'
import type { Animal } from '@/domain/Animal'
import MenuLayout from '@/components/MenuLayout.vue'

const { t } = useTranslator()
const { favorites, toggleFavorite } = useMammalsState()

const search = ref('')

const { species } = useDatabase()
const animalsByFamily = computed(() => {
  const map = new Map<string, Animal[]>()
  const searchKey = search.value.toLocaleLowerCase()
  const filteredSpecies = search.value
    ? species.filter((s) => s.name.toLocaleLowerCase().includes(searchKey) || s.family.toLocaleLowerCase().includes(searchKey) || s.latinName.toLocaleLowerCase().includes(searchKey))
    : species
  filteredSpecies.map((s) => {
    const list = map.get(s.family) ?? []
    list.push(s)
    map.set(s.family, list)
  })

  return map
})
</script>

<template>
  <menu-layout>
    <div class="input-group flex-grow-1 w-100">
      <span class="input-group-text">
        <i class="icon icon-magnifying-glass icon-sm" />
      </span>
      <input type="text" class="form-control" placeholder="Spezies suchen..." v-model="search" />
    </div>
  </menu-layout>
  <h3 class="mb-3">{{ t('pages.home.title') }}</h3>
  <div class="d-flex flex-column gap-2">
    <family-collapse v-for="[family, animals] in animalsByFamily.entries()" :key="family" :family="family" :animals="animals" :favorites="favorites" @toggle-favorite="toggleFavorite($event.id)" />
  </div>
</template>
