<script setup lang="ts">
import { useTranslator } from '@/locales/translator'

const { t } = useTranslator()
</script>

<template>
  <h3 class="mb-3">{{ t('pages.home.title') }}</h3>
</template>
