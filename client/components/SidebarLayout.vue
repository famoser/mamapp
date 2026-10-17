<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Offcanvas } from 'bootstrap'
import MenuEntry from '@/components/library/MenuEntry.vue'
import MenuHeader from '@/components/library/MenuHeader.vue'
import { useTranslator } from '@/locales/translator'

const { t } = useTranslator()

const offcanvasElement = ref<HTMLElement | null>(null)
let offcanvasInstance: Offcanvas | null = null

onMounted(() => {
  if (offcanvasElement.value) {
    offcanvasInstance = new Offcanvas(offcanvasElement.value)
  }
})

const toggleMenu = () => {
  offcanvasInstance?.toggle()
}
</script>

<template>
  <nav class="navbar sticky-top border-bottom bg-primary-subtler mb-3">
    <div class="d-flex gap-2 justify-content-between w-100 ms-1">
      <button class="btn btn-icon py-1" @click="toggleMenu" aria-label="Toggle menu">
        <i class="icon icon-bars" />
      </button>
      <slot />
    </div>
  </nav>

  <div class="d-flex"></div>

  <!-- Offcanvas Sidebar Menu -->
  <div ref="offcanvasElement" class="offcanvas offcanvas-start bg-primary-subtler" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header border-bottom">
      <div class="d-flex gap-3">
        <i class="icon icon-brand icon-xl align-self-center" />
        <div>
          <h2 class="mb-0">{{ t('layout.sidebar.title') }}</h2>
          <p class="byline text-muted mb-0">{{ t('layout.sidebar.subtitle') }}</p>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
      <div class="mb-5 mt-2">
        <menu-header :label="t('layout.sidebar.explore')" />
        <menu-entry :label="t('layout.sidebar.speciesList')" icon="icon-list" :active="true" @click="toggleMenu" />
        <menu-entry :label="t('layout.sidebar.identify')" icon="icon-magnifying-glass" />
      </div>
      <div class="mb-5">
        <menu-header :label="t('layout.sidebar.learn')" />
        <menu-entry :label="t('layout.sidebar.observationMethods')" icon="icon-binoculars" />
        <menu-entry :label="t('layout.sidebar.ethics')" icon="icon-leaf" />
      </div>
      <div class="mb-5">
        <menu-header :label="t('layout.sidebar.about')" />
        <menu-entry :label="t('layout.sidebar.contactUs')" icon="icon-envelope" />
        <menu-entry :label="t('layout.sidebar.acknowledgements')" icon="icon-award" />
        <menu-entry :label="t('layout.sidebar.sources')" icon="icon-books" />
      </div>
      <div>
        <menu-header :label="t('layout.sidebar.preferences')" />
        <menu-entry :label="t('layout.sidebar.settings')" icon="icon-gear" />
        <menu-entry :label="t('layout.sidebar.language')" icon="icon-globe" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.byline {
  font-size: 0.8em;
}
</style>
