<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useMammalsState } from '@/services/state'
import { useDatabase } from '@/services/data'
import { computed } from 'vue'
import ImageCarousel from '@/components/ImageCarousel.vue'
import NavLayout from '@/components/NavLayout.vue'

const route = useRoute()
const speciesId = computed(() => route.params.id)

const { favorites } = useMammalsState()

const { species } = useDatabase()
const animal = computed(() => {
  return species.find((s) => s.id === speciesId.value)
})

const currentAnimalIndex = computed(() => {
  if (!animal.value) {
    return 0
  }

  return favorites.value.indexOf(animal.value.id)
})

const nextAnimalId = computed(() => {
  const nextIndex = currentAnimalIndex.value + 1
  return nextIndex >= favorites.value.length ? favorites.value[0] : favorites.value[nextIndex]
})

const previousAnimalId = computed(() => {
  const nextIndex = currentAnimalIndex.value - 1
  return nextIndex < 0 ? favorites.value[favorites.value.length - 1] : favorites.value[nextIndex]
})

const router = useRouter()
</script>

<template>
  <div v-if="animal">
    <nav-layout>
      <div v-if="favorites.includes(animal.id) && favorites.length > 1" class="align-self-center text-muted">
        {{ currentAnimalIndex + 1 }} / {{ favorites.length }} Pinned</div>
      <div v-if="favorites.includes(animal.id) && favorites.length > 1">
        <button class="btn btn-icon py-1" @click="router.push('/species/' + previousAnimalId)" aria-label="Previous animal">
          <i class="icon icon-chevron-left" />
        </button>
        <button class="btn btn-icon py-1" @click="router.push('/species/' + nextAnimalId)" aria-label="Next animal">
          <i class="icon icon-chevron-left turn-180" />
        </button>
      </div>
    </nav-layout>
    <image-carousel :images="animal.images" />
    <h1 class="text-primary-emphasis mt-5 mb-1">{{ animal.name }}</h1>
    <p class="text-muted">
      <i>{{ animal.latinName }}</i>
    </p>

    <p>{{ animal.description }}</p>

    <p>{{ animal.similarSpecies }}</p>
    <p>{{ animal.habitat }}</p>
    <p>{{ animal.observe }}</p>
    <p>{{ animal.conservation }}</p>
  </div>
</template>
