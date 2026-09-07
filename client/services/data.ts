import type { Animal } from '@/domain/Animal'

export const state = {
  species: []
}

export function useDatabase(): { species: Animal[] } {
  return { ...state }
}
