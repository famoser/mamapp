<script setup lang="ts">
import AnimalPreview from '@/components/AnimalPreview.vue'
import type { Animal } from '@/domain/Animal'
import { onMounted, ref } from 'vue'
import { Collapse } from 'bootstrap'

withDefaults(
  defineProps<{
    family: string
    animals: Animal[]
    favorites: string[]
    header?: boolean
  }>(),
  { header: true }
)

const emit = defineEmits<{
  (e: 'toggle-favorite', value: Animal): void
}>()

const collapseElement = ref<HTMLElement | null>(null)
let collapseInstance: Collapse | null = null
const collapseState = ref(false)

onMounted(() => {
  if (collapseElement.value) {
    collapseInstance = new Collapse(collapseElement.value, {
      toggle: false
    })
  }
})

const toggleCollapse = () => {
  collapseState.value = !collapseState.value
  collapseInstance?.toggle()
}
</script>

<template>
  <div>
    <div
      v-if="header"
      class="d-flex justify-content-between text-primary-emphasis p-2 bg-primary-subtle rounded-top-2"
      role="button"
      @click="toggleCollapse"
      :aria-expanded="true"
      aria-controls="collapse-body"
    >
      <p class="mb-1 fw-bold">{{ family }}</p>
      <span class="d-flex align-items-center gap-2 text-muted small">
        <span>{{ animals.length }} species</span>
        <i class="icon icon-chevron-left" :class="{ 'turn-90-ccw': !collapseState }"></i>
      </span>
    </div>
    <div ref="collapseElement" id="collapse-body" class="collapse show">
      <div class="d-flex flex-column gap-1 bg-primary-subtler p-1">
        <animal-preview v-for="animal in animals" :key="animal.id" :animal="animal" :is-favorite="favorites.includes(animal.id)" @toggle-favorite="emit('toggle-favorite', animal)" />
      </div>
    </div>
  </div>
</template>
