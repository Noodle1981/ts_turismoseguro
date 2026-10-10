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
        class="flex-1 inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-surface-container hover:bg-surface-dim text-primary font-label-md text-label-md transition-colors font-bold"
      >
        <span class="material-symbols-outlined text-[18px]">info</span>
        Ver Detalles
      </RouterLink>
      <a
        :href="`https://wa.me/${quinta.whatsappPhone}?text=Hola,%20vi%20tu%20quinta%20o%20caba%C3%B1a%20${encodeURIComponent(quinta.title)}%20en%20Turismo%20Seguro`"
        target="_blank"
        rel="noopener noreferrer"
        class="flex-1 py-3 px-space-md rounded-xl bg-secondary text-on-secondary hover:bg-on-secondary-container transition-colors flex items-center justify-center gap-2 font-label-md text-label-md font-bold shadow-sm cursor-pointer"
        title="Contactar al dueño por WhatsApp"
      >
        <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
          <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.79.78 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.767-5.766zm9.969 5.766c0 5.505-4.475 9.98-9.97 9.98-1.742 0-3.376-.447-4.808-1.23l-5.222 1.369 1.393-5.087c-.89-1.493-1.393-3.238-1.393-5.032 0-5.506 4.475-9.981 9.97-9.981 5.505 0 10.03 4.475 10.03 9.981z" />
        </svg>
        <span>Contactar</span>
      </a>
    </div>
  </article>
</template>
