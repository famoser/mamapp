<script setup lang="ts">
defineProps<{
  icon: string
  label: string
  text: string
}>()
</script>

<template>
  <div class="border border-1 border-primary-subtle p-2 rounded-1 position-relative">
    <div class="d-flex align-items-center gap-2 mb-2 shift-up bg-white px-2">
      <i class="icon" :class="icon" />
      <span class="text-muted">{{ label }}</span>
    </div>
    {{ text }}
  </div>
</template>

<style scoped>
.shift-up {
  position: absolute;
  top: -1em;
}
</style>
