<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { Collapse } from 'bootstrap'

defineProps<{
  title?: string
  subTitle?: string
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
      v-if="title || subTitle"
      class="d-flex justify-content-between text-primary-emphasis p-2 bg-primary-subtle rounded-top-2"
      role="button"
      @click="toggleCollapse"
      :aria-expanded="true"
      aria-controls="collapse-body"
    >
      <p class="mb-1 fw-bold">{{ title }}</p>
      <span class="d-flex align-items-center gap-2 text-muted small">
        <span>{{ subTitle }}</span>
        <i class="icon icon-chevron-left" :class="{ 'turn-90-ccw': !collapseState }"></i>
      </span>
    </div>
    <div ref="collapseElement" id="collapse-body" class="collapse show">
      <div class="d-flex flex-column gap-1 bg-primary-subtler p-1">
        <slot />
      </div>
    </div>
  </div>
</template>
