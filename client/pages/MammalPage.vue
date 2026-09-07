<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useTranslator } from '@/locales/translator'
import { useMammalsState } from '@/services/state'

const route = useRoute()
const mammalId = route.params.id

const { t } = useTranslator()

const { favorites } = useMammalsState()
</script>

<template>
  <div class="mb-4">
    <h3 class="mb-2">{{ t('pages.title') }}</h3>
    {{ mammalId }}

    {{ favorites.length }}
  </div>
</template>
