<script setup lang="ts">
withDefaults(
  defineProps<{
    label: string
    icon: string
    active?: boolean
  }>(),
  { active: false }
)
</script>

<template>
  <div class="d-flex gap-3 p-2 mb-1 rounded-2 menu-entry" :class="{ active: active }">
    <i class="icon" :class="icon"></i>
    <span class="text-primary-emphasis" :class="{ 'fw-bold': active }">{{ label }}</span>
  </div>
</template>

<style scoped>
.menu-entry {
  cursor: pointer;
}

.menu-entry:hover {
  background-color: var(--brand-primary-light);
}

.menu-entry.active {
  background-color: var(--brand-primary-light);
}
</style>
