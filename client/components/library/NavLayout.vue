<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()
</script>

<template>
  <nav class="navbar sticky-top border-bottom bg-primary-subtler mb-3">
    <div class="d-flex gap-2 justify-content-between w-100 ms-1">
      <button class="btn btn-icon py-1" @click="router.push('/')" aria-label="Toggle menu">
        <i class="icon icon-arrow-left" />
      </button>
      <slot />
    </div>
  </nav>
</template>
