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
  calls: Call[]
  mapSrc: string
}

export type Image = {
  src: string
  caption: string
}

export type Call = {
  src: string
  caption: string
}
