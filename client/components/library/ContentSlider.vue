<script setup lang="ts">
import { computed, ref, watch } from 'vue'

type SwipeDirection = 'before' | 'after'

const emit = defineEmits<{
  (event: 'swipe-completed', direction: SwipeDirection): void
}>()

const startX = ref<number | null>(null)
const currentX = ref(0)
const containerRef = ref<HTMLElement | null>(null)
const dragOffset = computed(() => {
  if (startX.value === null) {
    return 0
  }

  return currentX.value - startX.value
})

const activeSide = ref<SwipeDirection>('before')
watch(dragOffset, () => {
  if (dragOffset.value > 0) {
    activeSide.value = 'before'
  }

  if (dragOffset.value < 0) {
    activeSide.value = 'after'
  }
})

function onPointerDown(event: PointerEvent): void {
  currentX.value = event.clientX
  startX.value = event.clientX
}

function onPointerMove(event: PointerEvent): void {
  event.currentTarget instanceof HTMLElement && event.currentTarget.setPointerCapture(event.pointerId)
  currentX.value = event.clientX
}

const isAnimating = ref(false)
const containerStyle = computed(() => ({
  transform: `translateX(${dragOffset.value}px)`,
  transition: isAnimating.value ? 'transform 0.2s ease-out' : 'none'
}))

function onPointerUp(event: PointerEvent): void {
  event.currentTarget instanceof HTMLElement && event.currentTarget.setPointerCapture(event.pointerId)

  let containerWidth = containerRef.value?.offsetWidth ?? 300
  const swipeThreshold = containerRef.value ? containerWidth * 0.3 : 80
  const completedDirection = Math.abs(dragOffset.value) >= swipeThreshold ? activeSide.value : null

  if (completedDirection) {
    // Animate to full width
    isAnimating.value = true
    currentX.value = startX.value! + (completedDirection === 'before' ? containerWidth : -containerWidth)

    // Wait for animation to complete before emitting event
    setTimeout(() => {
      isAnimating.value = false
      startX.value = null
      currentX.value = 0
      emit('swipe-completed', completedDirection)
    }, 200)
  } else {
    // Animate back to 0
    isAnimating.value = true
    currentX.value = startX.value!

    setTimeout(() => {
      isAnimating.value = false
      startX.value = null
      currentX.value = 0
    }, 200)
  }
}
</script>

<template>
  <div ref="containerRef" class="slidable-view" @pointerdown="onPointerDown" @pointermove="onPointerMove" @pointerup="onPointerUp" @pointercancel="onPointerUp">
    <div v-if="activeSide === 'before'" class="slidable-view__side slidable-view__side--before">
      <slot name="before" />
    </div>

    <div v-if="activeSide === 'after'" class="slidable-view__side slidable-view__side--after">
      <slot name="after" />
    </div>

    <div class="slidable-view__content" :style="containerStyle">
      <slot />
    </div>
  </div>
</template>

<style scoped>
.slidable-view {
  position: relative;
  overflow: hidden;
  width: 100%;
  touch-action: pan-y;
  user-select: none;
}

.slidable-view__content {
  position: relative;
  z-index: 1;
  width: 100%;
  background: white;
}

.slidable-view__side {
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
  width: 100%;
}
</style>
