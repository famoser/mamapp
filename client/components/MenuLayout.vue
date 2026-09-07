<script setup lang="ts">
import { useRouter } from 'vue-router'
import { ref, onMounted } from 'vue'
import { Offcanvas } from 'bootstrap'

const router = useRouter()
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

const closeMenu = () => {
  offcanvasInstance?.hide()
}

const navigateTo = (path: string) => {
  router.push(path)
  closeMenu()
}
</script>

<template>
  <div class="d-flex">
    <button class="btn btn-link m-3" @click="toggleMenu" aria-label="Toggle menu">
      <i class="icon icon-bars" />
    </button>
    <slot />
  </div>

  <!-- Offcanvas Sidebar Menu -->
  <div ref="offcanvasElement" class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
      <!-- Explore Section -->
      <div class="list-group list-group-flush">
        <div class="px-3 py-2">
          <h6 class="text-uppercase small fw-bold text-success">Explore</h6>
        </div>
        <a href="#" @click.prevent="navigateTo('/')" class="list-group-item list-group-item-action"> Home </a>
        <a href="#" @click.prevent="" class="list-group-item list-group-item-action"> Species </a>
      </div>

      <!-- Learn Section -->
      <div class="list-group list-group-flush mt-3">
        <div class="px-3 py-2">
          <h6 class="text-uppercase small fw-bold text-success">Learn</h6>
        </div>
        <a href="#" @click.prevent="" class="list-group-item list-group-item-action"> Guides </a>
        <a href="#" @click.prevent="" class="list-group-item list-group-item-action"> Taxonomy </a>
      </div>

      <!-- About Section -->
      <div class="list-group list-group-flush mt-3">
        <div class="px-3 py-2">
          <h6 class="text-uppercase small fw-bold text-success">About</h6>
        </div>
      </div>
    </div>
  </div>
</template>
