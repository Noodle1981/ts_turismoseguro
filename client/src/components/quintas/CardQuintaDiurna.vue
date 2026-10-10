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
        <div class="absolute top-3 right-3 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm shadow-md flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-[14px]">bolt</span>
          <span>Trato directo • 0% comisión</span>
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

    <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
      <RouterLink
        :to="`/quintas/${quinta.id}`"
        class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
      >
        <span class="material-symbols-outlined text-[18px]">info</span>
        Ver Detalles y Cotizar
      </RouterLink>
      <a
        :href="`https://wa.me/${quinta.whatsappPhone}?text=Hola,%20vi%20tu%20quinta%20${encodeURIComponent(quinta.title)}%20en%20Turismo%20Seguro`"
        target="_blank"
        rel="noopener noreferrer"
        class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm cursor-pointer"
        title="Abrir WhatsApp Directo con el Dueño"
      >
        <span class="material-symbols-outlined text-[20px]">chat</span>
      </a>
    </div>
  </article>
</template>
