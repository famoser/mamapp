<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Offcanvas } from 'bootstrap'
import MenuEntry from '@/components/MenuEntry.vue'
import MenuHeader from '@/components/MenuHeader.vue'

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
          <h2 class="mb-0">Mammal Guide Europe</h2>
          <p class="byline text-muted mb-0">Explore · Learn · Protect</p>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
      <div class="mb-5 mt-2">
        <menu-header label="Explore" />
        <menu-entry label="Species list" icon="icon-list" :active="true" @click="toggleMenu" />
        <menu-entry label="Identify" icon="icon-magnifying-glass" />
      </div>
      <div class="mb-5">
        <menu-header label="Learn" />
        <menu-entry label="Observation methods" icon="icon-binoculars" />
        <menu-entry label="Ethics" icon="icon-leaf" />
      </div>
      <div class="mb-5">
        <menu-header label="About" />
        <menu-entry label="Contact us" icon="icon-envelope" />
        <menu-entry label="Acknowledgements" icon="icon-award" />
        <menu-entry label="Sources" icon="icon-books" />
      </div>
      <div>
        <menu-header label="Preferences" />
        <menu-entry label="Settings" icon="icon-gear" />
        <menu-entry label="Language" icon="icon-globe" />
      </div>
    </div>
  </div>
</template>

<style scoped>
.byline {
  font-size: 0.8em;
}
</style>
