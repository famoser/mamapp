<script setup lang="ts">
import type { Image } from '@/domain/Animal'
import { nextTick, onMounted, ref, watch } from 'vue'
import { Carousel } from 'bootstrap'

const props = defineProps<{
  images: Image[]
}>()

const carouselElement = ref<HTMLElement | null>(null)
let carouselInstance: Carousel | null = null

onMounted(() => {})

watch(() => props.images, () => {
  nextTick(() => {
    if (carouselElement.value) {
      const firstImage = carouselElement.value.querySelector('.carousel-item')
      if (firstImage) {
        ;(firstImage as HTMLElement).classList.add('active')
      }

      carouselInstance = new Carousel(carouselElement.value)
    }
  })
}, { immediate: true})

const previous = () => {
  carouselInstance?.prev()
}
const next = () => {
  carouselInstance?.next()
}
</script>

<template>
  <div ref="carouselElement" class="carousel slide">
    <div class="carousel-inner">
      <div class="carousel-item position-relative" v-for="image in images" :key="image.src">
        <img :src="image.src" class="d-block w-100 rounded-top-2" :alt="image.caption" />
        <p class="p-1 bg-black text-white mb-0 rounded-bottom-1">
          {{ image.caption }}
        </p>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" @click="previous()">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" @click="next()">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</template>

<style scoped></style>
