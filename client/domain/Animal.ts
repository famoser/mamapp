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
  mapSrc: string
}

export type Image = {
  src: string
  caption: string
}
