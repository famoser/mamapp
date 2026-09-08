<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useTranslator } from '@/locales/translator'
import { useMammalsState } from '@/services/state'
import { useDatabase } from '@/services/data'
import { computed } from 'vue'
import ImageCarousel from '@/components/ImageCarousel.vue'

const route = useRoute()
const speciesId = route.params.id

const { t } = useTranslator()

const { favorites } = useMammalsState()

const { species } = useDatabase()
const animal = computed(() => {
  return species.find((s) => s.id === speciesId)
})
</script>

<template>
  <div v-if="animal">
    <image-carousel :images="animal.images" />
    <h1 class="text-primary-emphasis mt-2 mb-1">{{ animal.name }}</h1>
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
