<script setup lang="ts">
import type { Animal } from '@/domain/Animal'
import AnimalText from '@/components/AnimalText.vue'
import ImageCarousel from '@/components/library/ImageCarousel.vue'

defineProps<{
  animal: Animal
}>()
</script>

<template>
  <image-carousel :images="animal.images" />
  <h1 class="text-primary-emphasis mt-5 mb-1">{{ animal.name }}</h1>
  <p class="text-muted">
    <i>{{ animal.latinName }}</i>
  </p>

  <div class="d-flex flex-column gap-5">
    <img :src="animal.mapSrc" class="img-fluid border-1 border rounded-1" :alt="'Map of ' + animal.name" />

    <animal-text icon="icon-books" label="Beschreibung" :text="animal.description" />
    <animal-text icon="icon-almost-equal-to" label="Ähnliche Spezies" :text="animal.similarSpecies" />
    <animal-text icon="icon-tree" label="Lebensraum" :text="animal.habitat" />
    <animal-text icon="icon-binoculars" label="Wie beobachten" :text="animal.observe" />
    <animal-text icon="icon-leaf" label="Schutzstatus" :text="animal.conservation" />
  </div>
</template>
