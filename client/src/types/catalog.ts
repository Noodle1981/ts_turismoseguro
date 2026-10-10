import type { paths, components } from './schema'

export type Department = components['schemas']['Department'] & {
  properties_count?: number
  experiences_count?: number
}

export type PropertyImage = components['schemas']['PropertyImage']

export type Property = components['schemas']['Property'] & {
  rules?: string[]
  host_phone?: string | null
}

export type Experience = components['schemas']['Experience'] & {
  images?: { id: number; image_path: string; caption?: string | null; is_primary?: boolean }[]
  includes?: { id: number; item: string; is_included: boolean }[]
  recommendations?: string[]
}

export type PropertyFilters = paths['/properties']['get']['parameters']['query']

export type ExperienceFilters = paths['/experiences']['get']['parameters']['query']

export type AvailabilityItem = {
  date: string
  status: 'occupied' | 'blocked' | 'reserved'
}
