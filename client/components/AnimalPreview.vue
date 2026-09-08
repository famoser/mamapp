<script setup lang="ts">
import type { Animal } from '@/domain/Animal'
import { useRouter } from 'vue-router'

defineProps<{
  animal: Animal
  isFavorite: boolean
}>()

const emit = defineEmits<{
  (e: 'toggle-favorite'): void
}>()

const router = useRouter()
</script>

<template>
  <div class="card card-horizontal card-button" role="button" @click="router.push('/species/' + animal.id)">
    <img :src="`${animal.images[0]?.path}`" :alt="animal.name" class="card-img-left animal-image me-3" />
    <div class="card-body d-flex align-items-start">
      <div class="flex-grow-1">
        <h2 class="mb-1">
          <b>{{ animal.name }}</b>
        </h2>
        <p class="mb-0 text-muted">
          <i>{{ animal.latinName }}</i>
        </p>
      </div>
      <button class="btn btn-icon ms-2">
        <i class="icon" :class="{ 'icon-thumbtack-solid': isFavorite, 'icon-thumbtack': !isFavorite }" @click.stop="emit('toggle-favorite')"></i>
      </button>
    </div>
  </div>
</template>

<style scoped>
.card-horizontal {
  display: flex;
  flex-direction: row;
}

.animal-image {
  width: 8em;
}

.card-button:hover {
  background-color: var(--brand-primary-light);
}
</style>
