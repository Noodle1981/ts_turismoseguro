<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink } from 'vue-router'
import type { Property } from '../../types/catalog'
import BadgeVerified from '../common/BadgeVerified.vue'
import { MapPin, Users, Bath, Bed, Sparkles } from 'lucide-vue-next'

const props = defineProps<{
  property: Property
}>()

const primaryImage = computed(() => {
  if (props.property.images && props.property.images.length > 0) {
    const primary = props.property.images.find(img => img.is_primary)
    return primary ? primary.image_path : props.property.images[0].image_path
  }
  return '/img/propiedades/placeholder.jpg'
})

const formattedPrice = computed(() => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(props.property.price)
})
</script>

<template>
  <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
    <!-- Image with badging -->
    <div class="relative aspect-16/10 overflow-hidden bg-surface-100">
      <img
        :src="primaryImage"
        :alt="property.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        loading="lazy"
      />
      <div class="absolute top-3 left-3">
        <BadgeVerified size="sm" />
      </div>

      <div
        v-if="property.featured"
        class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/90 backdrop-blur-xs text-white shadow-xs"
      >
        <Sparkles class="w-3 h-3" />
        <span>Destacado</span>
      </div>

      <div class="absolute bottom-2 left-3 px-2.5 py-1 rounded-md bg-brand-950/70 backdrop-blur-xs text-[11px] font-semibold text-white uppercase tracking-wider">
        {{ property.property_type }}
      </div>
    </div>

    <!-- Body Content -->
    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
      <div>
        <div class="flex items-center gap-1.5 text-xs text-surface-500 font-medium mb-1">
          <MapPin class="w-3.5 h-3.5 text-brand-600 flex-shrink-0" />
          <span>{{ property.location }}</span>
        </div>

        <h3 class="font-display font-bold text-surface-900 text-lg leading-snug group-hover:text-brand-600 transition-colors line-clamp-1">
          {{ property.title }}
        </h3>

        <!-- Key specs: capacity, bedrooms, bathrooms -->
        <div class="flex items-center gap-4 mt-3 text-xs text-surface-600 font-medium border-y border-slate-100 py-2">
          <div class="flex items-center gap-1">
            <Users class="w-4 h-4 text-surface-400" />
            <span>{{ property.capacity }} pers.</span>
          </div>
          <div v-if="property.bedrooms && property.bedrooms > 0" class="flex items-center gap-1">
            <Bed class="w-4 h-4 text-surface-400" />
            <span>{{ property.bedrooms }} dorm.</span>
          </div>
          <div class="flex items-center gap-1">
            <Bath class="w-4 h-4 text-surface-400" />
            <span>{{ property.bathrooms }} baños</span>
          </div>
        </div>

        <!-- Amenities Preview -->
        <div v-if="property.amenities && property.amenities.length > 0" class="flex flex-wrap gap-1.5 mt-3">
          <span
            v-for="amenity in property.amenities.slice(0, 3)"
            :key="amenity"
            class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-surface-100 text-surface-600"
          >
            {{ amenity }}
          </span>
          <span
            v-if="property.amenities.length > 3"
            class="px-1.5 py-0.5 rounded-md text-[11px] font-medium bg-surface-50 text-surface-400"
          >
            +{{ property.amenities.length - 3 }}
          </span>
        </div>
      </div>

      <!-- Price & Action CTA -->
      <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
        <div>
          <span class="text-xs text-surface-400 block font-medium">Tarifa oficial</span>
          <p class="font-display font-extrabold text-brand-900 text-xl leading-tight">
            {{ formattedPrice }}
            <span class="text-xs font-normal text-surface-500">/ {{ property.price_type }}</span>
          </p>
        </div>

        <RouterLink
          :to="`/alquileres/${property.slug}`"
          class="px-4 py-2 text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 active:scale-95 rounded-xl transition shadow-xs cursor-pointer"
        >
          Ver Detalle
        </RouterLink>
      </div>
    </div>
  </div>
</template>
