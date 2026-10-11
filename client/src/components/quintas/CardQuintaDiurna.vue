<script setup lang="ts">
import { RouterLink } from 'vue-router'
export interface QuintaItem {
  id: number | string
  title: string
  location: string
  price: number
  priceFormatted: string
  shift: string
  capacity: number
  image: string
  amenities: string[]
  whatsappPhone: string
}

defineProps<{
  quinta: QuintaItem
}>()
</script>

<template>
  <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
    <div>
      <div class="relative h-64 overflow-hidden">
        <img
          class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
          :src="quinta.image"
          :alt="quinta.title"
        />
        <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md font-bold">
          <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
          <span class="font-label-sm text-label-sm">Sello Verificado In Situ</span>
        </div>
        <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-[14px]">location_on</span>
          {{ quinta.location }}
        </div>
      </div>

      <div class="p-space-lg">
        <div class="flex items-start justify-between gap-space-sm mb-space-xs">
          <h3 class="font-headline-md text-headline-md text-primary font-bold">
            {{ quinta.title }}
          </h3>
          <div class="text-right">
            <div class="font-headline-md text-headline-md text-secondary font-bold">
              {{ quinta.priceFormatted }}
            </div>
            <div class="font-label-sm text-label-sm text-on-surface-variant">
              / {{ quinta.shift }}
            </div>
          </div>
        </div>
        <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
          <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
          <span class="font-label-md text-label-md font-semibold text-on-surface">
            Capacidad: Hasta {{ quinta.capacity }} personas (diurno)
          </span>
        </div>
        <div class="flex flex-wrap gap-1.5 mb-space-md">
          <span
            v-for="amenity in quinta.amenities"
            :key="amenity"
            class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-medium"
          >
            <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span>
            {{ amenity }}
          </span>
        </div>
      </div>
    </div>

    <div class="px-space-lg pb-space-lg pt-0">
      <RouterLink
        :to="`/quintas/${quinta.id}`"
        class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-surface-container hover:bg-surface-dim text-primary font-label-md text-label-md transition-colors font-bold"
      >
        <span class="material-symbols-outlined text-[18px]">info</span>
        Ver Detalles
      </RouterLink>
    </div>
  </article>
</template>
