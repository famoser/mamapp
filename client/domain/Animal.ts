export type Animal = {
  id: string
  name: string
  latinName: string
  family: string
  description: string
  similarSpecies: string
  habitat: string
  observe: string
  conservation: string
  images: Image[]
}

export type Image = {
  path: string
  caption: string
}
