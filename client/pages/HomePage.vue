<script setup lang="ts">
import { useDatabase } from '@/services/data'
import FamilyCollapse from '@/components/FamilyCollapse.vue'
import { computed, ref } from 'vue'
import { useMammalsState } from '@/services/state'
import type { Animal } from '@/domain/Animal'
import MenuLayout from '@/components/MenuLayout.vue'

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

const favoriteAnimals = computed(() => {
  return species.filter((s) => favorites.value.includes(s.id))
})
</script>

<template>
  <menu-layout>
    <div class="input-group mw-20em me-1">
      <span class="input-group-text">
        <i class="icon icon-magnifying-glass icon-sm" />
      </span>
      <input type="text" class="form-control" placeholder="Spezies suchen..." v-model="search" />
    </div>
  </menu-layout>
  <div class="mb-5" v-if="favoriteAnimals.length > 0">
    <h3 class="mb-2">Pinned</h3>
    <family-collapse family="Pinned" :animals="favoriteAnimals" :favorites="favorites" @toggle-favorite="toggleFavorite($event.id)" :header="false" />
  </div>

  <div class="d-flex flex-column gap-5">
    <family-collapse v-for="[family, animals] in animalsByFamily.entries()" :key="family" :family="family" :animals="animals" :favorites="favorites" @toggle-favorite="toggleFavorite($event.id)" />
  </div>
</template>

<style scoped>
.mw-20em {
  max-width: 20em;
}
</style>
