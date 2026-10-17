<script setup lang="ts">
import { ref } from 'vue'
import type { Call } from '@/domain/Animal'

defineProps<{
  calls: Call[]
}>()

const currentlyPlayingIndex = ref<number | null>(null)
const audioRefs = ref<(HTMLAudioElement | null)[]>([])

function toggleAudio(index: number): void {
  const audio = audioRefs.value[index]
  if (!audio) return

  // Stop all other audio players
  audioRefs.value.forEach((audioElement, idx) => {
    if (idx !== index && audioElement) {
      audioElement.pause()
      audioElement.currentTime = 0
    }
  })

  // Toggle current audio
  if (currentlyPlayingIndex.value === index && !audio.paused) {
    audio.pause()
    audio.currentTime = 0
    currentlyPlayingIndex.value = null
  } else {
    audio.play()
    currentlyPlayingIndex.value = index
  }
}

function onAudioEnded(index: number): void {
  if (currentlyPlayingIndex.value === index) {
    currentlyPlayingIndex.value = null
  }
}
</script>

<template>
  <div class="d-flex flex-column gap-2">
    <div class="d-flex gap-2 align-items-center my-2" v-for="(call, index) in calls" :key="call.src">
      <button
        class="btn btn-outline-secondary"
        :class="{ 'btn-secondary': currentlyPlayingIndex === index }"
        @click="toggleAudio(index)"
        :aria-label="`${currentlyPlayingIndex === index ? 'Pause' : 'Play'} audio for ${call.caption}`"
      >
        <i v-if="currentlyPlayingIndex === index" class="icon icon-pause" />
        <i v-else class="icon icon-play" />
      </button>
      <audio :ref="(el) => (audioRefs[index] = el as HTMLAudioElement | null)" :src="call.src" @ended="onAudioEnded(index)" />
      <span class="caption">{{ call.caption }}</span>
    </div>
  </div>
</template>

<style scoped>
.caption {
  flex: 1;
  word-break: break-word;
}

.icon {
  font-size: 0.6em;
  background-color: black;
}
.btn-secondary .icon {
  font-size: 0.6em;
  background-color: white;
}
</style>
