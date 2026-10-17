<script setup lang="ts">
import { useDatabase } from '@/services/data'
import { computed, ref } from 'vue'
import { useMammalsState } from '@/services/state'
import SidebarLayout from '@/components/SidebarLayout.vue'
import { useTranslator } from '@/locales/translator'
import ContentCollapse from '@/components/library/ContentCollapse.vue'
import AnimalPreview from '@/components/AnimalPreview.vue'
import { useRouter } from 'vue-router'
import type { Animal } from '@/domain/Animal'

const router = useRouter()
const { favorites, toggleFavorite } = useMammalsState()

const search = ref('')

const { t } = useTranslator()
const { animals, animalsByFamily } = useDatabase(search)

const favoriteAnimals = computed(() => {
  return favorites.value.map((s) => animals.value.find((a) => a.id === s) as Animal)
})
</script>

<template>
  <sidebar-layout>
    <div class="input-group mw-20em me-1">
      <span class="input-group-text">
        <i class="icon icon-magnifying-glass icon-sm" />
      </span>
      <input type="text" class="form-control" :placeholder="t('pages.home.search')" v-model="search" />
    </div>
  </sidebar-layout>
  <div class="mb-5" v-if="favoriteAnimals.length > 0">
    <h3 class="mb-2">{{ t('pages.home.favorites') }}</h3>
    <content-collapse>
      <animal-preview
        v-for="(animal, index) in favoriteAnimals"
        :key="animal.id"
        :animal="animal"
        :is-favorite="true"
        @toggle-favorite="toggleFavorite(animal.id)"
        @navigate="router.push('/favorites/' + index)"
      />
    </content-collapse>
  </div>

  <div class="d-flex flex-column gap-5">
    <content-collapse v-for="[family, animals] in animalsByFamily.entries()" :key="family" :title="family" :sub-title="t('view.family.species', { count: animals.length })">
      <animal-preview
        v-for="animal in animals"
        :key="animal.id"
        :animal="animal"
        :is-favorite="favorites.includes(animal.id)"
        @toggle-favorite="toggleFavorite(animal.id)"
        @navigate="router.push('/species/' + animal.id)"
      />
    </content-collapse>
  </div>
</template>

<style scoped>
.mw-20em {
  max-width: 20em;
}
</style>
