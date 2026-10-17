<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useMammalsState } from '@/services/state'
import { useDatabase } from '@/services/data'
import { computed } from 'vue'
import NavLayout from '@/components/library/NavLayout.vue'
import AnimalView from '@/components/AnimalView.vue'
import ContentSlider from '@/components/library/ContentSlider.vue'
import { useTranslator } from '@/locales/translator'
import type { Animal } from '@/domain/Animal'

const route = useRoute()
const favoriteIndex = computed(() => Number(route.params.index))

const { animals } = useDatabase()
const { favorites } = useMammalsState()

const findAnimalByFavoriteIndex = function (favoriteIndex: number) {
  const favorite = favorites.value[favoriteIndex]
  return animals.value.find((s) => s.id === favorite) as Animal
}

const animal = computed(() => {
  return findAnimalByFavoriteIndex(favoriteIndex.value)
})

const nextIndex = computed(() => {
  return (favoriteIndex.value + 1) % favorites.value.length
})
const nextAnimal = computed(() => findAnimalByFavoriteIndex(nextIndex.value))

const previousIndex = computed(() => {
  if (favoriteIndex.value <= 0) {
    return favorites.value.length - 1
  }
  return favoriteIndex.value - 1
})
const previousAnimal = computed(() => findAnimalByFavoriteIndex(previousIndex.value))

const swipeCompleted = function (swipeDirection: 'before' | 'after') {
  if (swipeDirection === 'before') {
    router.push('/favorites/' + previousIndex.value)
  } else {
    router.push('/favorites/' + nextIndex.value)
  }
}
const { t } = useTranslator()

const router = useRouter()
</script>

<template>
  <div>
    <nav-layout>
      <div class="align-self-center text-muted">{{ favoriteIndex + 1 }} / {{ favorites.length }} {{ t('pages.home.favorites') }}</div>
      <div v-if="favorites.includes(animal.id) && favorites.length > 1">
        <button class="btn btn-icon py-1" @click="router.push('/favorites/' + previousIndex)" aria-label="Previous animal">
          <i class="icon icon-chevron-left" />
        </button>
        <button class="btn btn-icon py-1" @click="router.push('/favorites/' + nextIndex)" aria-label="Next animal">
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
