<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useDatabase } from '@/services/data'
import { computed } from 'vue'
import NavLayout from '@/components/library/NavLayout.vue'
import AnimalView from '@/components/AnimalView.vue'
import ContentSlider from '@/components/library/ContentSlider.vue'
import type { Animal } from '@/domain/Animal'

const route = useRoute()
const speciesId = computed(() => route.params.id)

const { animals, animalsByFamily } = useDatabase()
const animal = computed(() => {
  return animals.value.find((s) => s.id === speciesId.value) as Animal
})

const animalIndex = computed(() => {
  return animals.value.indexOf(animal.value)
})

const animalFamilyIndex = computed(() => {
  return animalsByFamily.value.get(animal.value.family)?.indexOf(animal.value) ?? 0
})

const nextAnimal = computed(() => {
  const nextIndex = animalIndex.value + 1
  return nextIndex >= animals.value.length ? animals.value[0] : animals.value[nextIndex]
})

const previousAnimal = computed(() => {
  const nextIndex = animalIndex.value - 1
  return nextIndex < 0 ? animals.value[animals.value.length - 1] : animals.value[nextIndex]
})

const swipeCompleted = function (swipeDirection: 'before' | 'after') {
  if (swipeDirection === 'before') {
    router.push('/species/' + previousAnimal.value.id)
  } else {
    router.push('/species/' + nextAnimal.value.id)
  }
}

const router = useRouter()
</script>

<template>
  <div>
    <nav-layout>
      <div class="align-self-center text-muted">{{ animalFamilyIndex + 1 }} / {{ animalsByFamily.get(animal.family)?.length ?? 1 }} {{ animal.family }}</div>
      <div>
        <button class="btn btn-icon py-1" @click="router.push('/species/' + previousAnimal.id)" aria-label="Previous animal">
          <i class="icon icon-chevron-left" />
        </button>
        <button class="btn btn-icon py-1" @click="router.push('/species/' + nextAnimal.id)" aria-label="Next animal">
          <i class="icon icon-chevron-left turn-180" />
        </button>
      </div>
    </nav-layout>
    <content-slider @swipe-completed="swipeCompleted">
      <template #before>
        <animal-view :animal="previousAnimal" />
      </template>
      <animal-view :animal="animal" />
      <template #after>
        <animal-view :animal="nextAnimal" />
      </template>
    </content-slider>
  </div>
</template>
