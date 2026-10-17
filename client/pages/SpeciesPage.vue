<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useMammalsState } from '@/services/state'
import { useDatabase } from '@/services/data'
import { computed } from 'vue'
import NavLayout from '@/components/library/NavLayout.vue'
import AnimalView from '@/components/AnimalView.vue'
import ContentSlider from '@/components/library/ContentSlider.vue'
import type { Animal } from '@/domain/Animal'
import { useTranslator } from '@/locales/translator'

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
const nextAnimal = computed(() => species.find((s) => s.id === nextAnimalId.value) as Animal)

const previousAnimalId = computed(() => {
  const nextIndex = currentAnimalIndex.value - 1
  return nextIndex < 0 ? favorites.value[favorites.value.length - 1] : favorites.value[nextIndex]
})
const previousAnimal = computed(() => species.find((s) => s.id === previousAnimalId.value) as Animal)

const swipeCompleted = function (swipeDirection: 'before' | 'after') {
  if (swipeDirection === 'before') {
    router.push('/species/' + previousAnimalId.value)
  } else {
    router.push('/species/' + nextAnimalId.value)
  }
}
const { t } = useTranslator()

const router = useRouter()
</script>

<template>
  <div v-if="animal">
    <nav-layout>
      <div v-if="favorites.includes(animal.id) && favorites.length > 1" class="align-self-center text-muted">
        {{ currentAnimalIndex + 1 }} / {{ favorites.length }} {{ t('pages.home.favorites') }}
      </div>
      <div v-if="favorites.includes(animal.id) && favorites.length > 1">
        <button class="btn btn-icon py-1" @click="router.push('/species/' + previousAnimalId)" aria-label="Previous animal">
          <i class="icon icon-chevron-left" />
        </button>
        <button class="btn btn-icon py-1" @click="router.push('/species/' + nextAnimalId)" aria-label="Next animal">
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
