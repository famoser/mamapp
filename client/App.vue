<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { state } from '@/services/data'
import { restClient } from '@/services/api'

const loading = ref(true)

onMounted(async () => {
  state.species = await restClient.get('/species/species.json')
  loading.value = false
})
</script>

<template>
  <div class="container mw-100em">
    <div class="my-5">
      <router-view v-if="!loading"></router-view>
    </div>
  </div>
</template>

<style scoped>
.mw-100em {
  max-width: 35em;
}
</style>
