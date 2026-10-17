<script setup lang="ts">
import type { Call } from '@/domain/Animal'

defineProps<{
  calls: Call[]
}>()
</script>

<template>
  <div class="d-flex flex-column gap-2">
    <div class="d-flex" v-for="call in calls" :key="call.src">
      <span><!-- add audio player (single button to start/stop recording; starting one recording stops all others) --></span>
      <span>{{call.caption}}</span>
    </div>
  </div>
</template>

<style scoped></style>
