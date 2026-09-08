<script setup lang="ts">
defineProps<{
  label: string
}>()
</script>

<template>
  <div class="d-flex gap-3 mb-2">
    <h3 class="ms-2 my-0">{{ label }}</h3>
    <hr class="w-100 m-0 align-self-center" />
  </div>
</template>

<style scoped></style>
