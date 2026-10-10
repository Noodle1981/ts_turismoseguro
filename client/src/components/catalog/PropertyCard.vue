<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import type { Property } from '../../types/catalog'
import BadgeVerified from '../common/BadgeVerified.vue'

const props = defineProps<{
  property: Property
}>()

const primaryImage = computed(() => {
  if (props.property.images && props.property.images.length > 0) {
    const primary = props.property.images.find(img => img.is_primary)
    return primary ? primary.image_path : props.property.images[0].image_path
  }
  return 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80'
})

const formattedPrice = computed(() => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(props.property.price)
})

const priceUnit = computed(() => {
  return props.property.price_type || 'por día'
})
</script>

<template>
  <div class="group bg-surface-container-lowest rounded-2xl overflow-hidden border border-surface-container shadow-card-stitch hover:shadow-hover-stitch hover:-translate-y-1 transition-all duration-300 flex flex-col">
    <!-- Image with Badges -->
    <div class="relative aspect-16/10 overflow-hidden bg-surface-container">
      <img
        :src="primaryImage"
        :alt="property.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        loading="lazy"
      />

      <!-- Official Verification Badge -->
      <div class="absolute top-3 left-3">
        <BadgeVerified size="sm" :verified-at="property.verified_at ? property.verified_at.slice(0, 10) : '2026'" />
      </div>

      <!-- Daytime Shift / Feature Tag -->
      <div
        v-if="property.featured"
        class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-tertiary-container/95 text-tertiary-fixed shadow-xs"
      >
        <span class="material-symbols-outlined text-[13px] text-tertiary-fixed">sunny</span>
        <span>Día Completo</span>
      </div>

      <!-- Property Type Tag -->
      <div class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg bg-primary/85 backdrop-blur-xs text-[10px] font-bold text-on-primary uppercase tracking-wider">
        {{ property.property_type }}
      </div>
    </div>

    <!-- Body -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
      <div>
        <div class="flex items-center gap-1.5 text-xs text-on-surface-variant font-semibold mb-1">
          <span class="material-symbols-outlined text-[16px] text-secondary">location_on</span>
          <span>{{ property.location }}</span>
        </div>

        <h3 class="font-headline-sm font-bold text-on-surface text-base group-hover:text-primary transition-colors line-clamp-1">
          {{ property.title }}
        </h3>

        <!-- Key specs: Capacidad de Invitados, Cerca de Pileta, Baños -->
        <div class="flex items-center justify-between mt-3 text-xs text-on-surface-variant font-medium border-y border-surface-container py-2.5">
          <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-outline">group</span>
            <span class="font-bold text-on-surface">Hasta {{ property.capacity }} pers.</span>
          </div>
          <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-secondary">pool</span>
            <span>Pileta con cerca</span>
          </div>
          <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-outline">outdoor_grill</span>
            <span>Parrilla</span>
          </div>
        </div>

        <!-- Amenities Preview Chips -->
        <div v-if="property.amenities && property.amenities.length > 0" class="flex flex-wrap gap-1.5 mt-3">
          <span
            v-for="amenity in property.amenities.slice(0, 3)"
            :key="amenity"
            class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container-low text-on-surface-variant border border-surface-container"
          >
            {{ amenity }}
          </span>
          <span
            v-if="property.amenities.length > 3"
            class="px-1.5 py-0.5 rounded-full text-[11px] font-semibold bg-surface-container text-outline"
          >
            +{{ property.amenities.length - 3 }}
          </span>
        </div>
      </div>

      <!-- Price & CTA Footer -->
      <div class="pt-3 border-t border-surface-container flex items-center justify-between">
        <div>
          <span class="text-[10px] uppercase font-bold text-on-surface-variant block tracking-wider">Tarifa base</span>
          <div class="flex items-baseline gap-1">
            <span class="text-lg font-extrabold text-primary">{{ formattedPrice }}</span>
            <span class="text-[11px] text-on-surface-variant font-semibold">/ {{ priceUnit }}</span>
          </div>
        </div>

        <RouterLink
          :to="`/alquileres/${property.slug}`"
          class="inline-flex items-center gap-1 px-3.5 py-2 rounded-lg text-xs font-bold text-on-primary bg-primary hover:bg-primary-container transition-colors shadow-xs"
        >
          <span>Ver Quinta</span>
          <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
        </RouterLink>
      </div>
    </div>
  </div>
</template>
