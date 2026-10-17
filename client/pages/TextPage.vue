<script setup lang="ts">
import { useRoute } from 'vue-router'
import { computed } from 'vue'
import NavLayout from '@/components/library/NavLayout.vue'
import { useTranslator } from '@/locales/translator'

const route = useRoute()
const textKey = computed(() => route.params.key)

const { t } = useTranslator()
</script>

<template>
  <div>
    <nav-layout>
      <div class="align-self-center text-muted">
        {{ t('pages.text.' + textKey + '.title') }}
      </div>
      <div></div>
    </nav-layout>

    <h1>{{ t('pages.text.' + textKey + '.title') }}</h1>
    <hr class="w-100 mb-3" />
    <p class="text-justify">{{ t('pages.text.' + textKey + '.content') }}</p>
    <p class="text-justify">{{ t('pages.text.' + textKey + '.content2') }}</p>
    <hr class="w-100 mb-3" />
    <p class="text-muted">{{ t('pages.text.footer') }}</p>
  </div>
</template>

<style scoped>
.text-justify {
  text-align: justify;
}
</style>
