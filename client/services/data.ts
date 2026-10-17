import type { Animal } from '@/domain/Animal'
import { computed, ref } from 'vue'

export const state: { species: Animal[] } = {
  species: []
}

export function useDatabase(search = ref('')) {
  const animalsByFamily = computed(() => {
    const map = new Map<string, Animal[]>()
    const searchKey = search.value.toLocaleLowerCase()
    const filteredSpecies = search.value
      ? state.species.filter((s) => s.name.toLocaleLowerCase().includes(searchKey) || s.family.toLocaleLowerCase().includes(searchKey) || s.latinName.toLocaleLowerCase().includes(searchKey))
      : state.species
    filteredSpecies.forEach((s) => {
      const list = map.get(s.family) ?? []
      list.push(s)
      map.set(s.family, list)
    })

    return map
  })

  const animals = computed(() => {
    const list: Animal[] = []
    animalsByFamily.value.forEach((animals) => {
      list.push(...animals)
    })

    return list
  })

  return { animalsByFamily, animals }
}
