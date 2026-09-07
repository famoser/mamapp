<script setup lang="ts">
import AnimalPreview from '@/components/AnimalPreview.vue'
import type { Animal } from '@/domain/Animal'
import { ref } from 'vue'

defineProps<{
  family: string
  animals: Animal[]
  favorites: string[]
}>()

const emit = defineEmits<{
  (e: 'toggle-favorite', value: Animal): void
}>()

const isCollapsed = ref(false)
</script>

<template>
  <div class="card">
    <div class="card-header" role="button" @click="isCollapsed = !isCollapsed" style="cursor: pointer">
      <div class="d-flex align-items-center">
        <i :class="['fa', isCollapsed ? 'fa-chevron-right' : 'fa-chevron-down', 'me-2']"></i>
        {{ family }}
      </div>
    </div>
    <div v-show="!isCollapsed" class="card-body">
      <animal-preview v-for="animal in animals" :key="animal.id" :animal="animal" :is-favorite="favorites.includes(animal.id)" @toggle-favorite="emit('toggle-favorite', animal)" />
    </div>
  </div>
</template>
