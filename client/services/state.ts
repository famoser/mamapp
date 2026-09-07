import { ref, watch } from 'vue'

// Persistent storage key
const STORAGE_KEY = 'species'

const loadFavorites = (): string[] => {
  const stored = localStorage.getItem(STORAGE_KEY)
  return stored ? JSON.parse(stored) : []
}

export const state = {
  favorites: loadFavorites()
}

export function useMammalsState() {
  const favorites = ref(state.favorites)

  const toggleFavorite = (id: string) => {
    if (favorites.value.includes(id)) {
      favorites.value = favorites.value.filter((i) => i !== id)
    } else {
      favorites.value.push(id)
    }
  }

  watch(
    favorites,
    (newValue) => {
      state.favorites = newValue
      localStorage.setItem(STORAGE_KEY, JSON.stringify(newValue))
    },
    { deep: true }
  )

  return { favorites, toggleFavorite }
}
