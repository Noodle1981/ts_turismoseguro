<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useCatalog } from '../../composables/useCatalog'
import type { Property, Department } from '../../types/catalog'

const { getProperties, getDepartments, isLoading } = useCatalog()

const properties = ref<Property[]>([])
const departments = ref<Department[]>([])

onMounted(async () => {
  try {
    const [propsRes, deptsRes] = await Promise.all([
      getProperties({ page: 1 }),
      getDepartments(),
    ])
    properties.value = propsRes.data
    departments.value = deptsRes
  } catch (err) {
    console.error('Error cargando catálogo:', err)
  }
})
</script>

<template>
  <main class="w-full bg-background py-space-md min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md mb-space-md">
        <RouterLink to="/" class="hover:text-primary transition-colors flex items-center gap-1">
          <span class="material-symbols-outlined text-[16px]">home</span>
          Inicio
        </RouterLink>
        <span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
        <span class="text-on-surface font-semibold">Alquileres Diurnos Verificados en San Juan</span>
      </nav>

      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md mb-space-lg">
        <div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed font-label-sm text-label-sm shadow-sm mb-2 font-bold">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
            <span>100% AUDITADAS IN SITU POR TURISMO SEGURO</span>
          </div>
          <h1 class="font-headline-xl text-headline-xl text-primary tracking-tight">
            Quintas y Quinchos en San Juan
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant mt-1">
            Alquileres temporarios para pasar el día con pileta, parrilla y trato directo con el dueño.
          </p>
        </div>
      </div>

      <!-- Properties Grid -->
      <div v-if="isLoading" class="min-h-[40vh] flex flex-col items-center justify-center space-y-3">
        <span class="material-symbols-outlined text-[40px] text-secondary animate-spin">progress_activity</span>
        <p class="font-body-sm text-body-sm text-on-surface-variant font-medium">Cargando quintas auditadas...</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
        <article
          v-for="property in properties"
          :key="property.id"
          class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
        >
          <div>
            <div class="relative h-64 overflow-hidden bg-surface-container">
              <img
                :src="property.images && property.images[0] ? property.images[0].image_path : 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=800&q=80'"
                :alt="property.title"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
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
                {{ property.location }}
              </div>
            </div>

            <div class="p-space-lg">
              <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                <h3 class="font-headline-md text-headline-md text-primary font-bold line-clamp-1">
                  {{ property.title }}
                </h3>
                <div class="text-right">
                  <div class="font-headline-md text-headline-md text-secondary font-bold">
                    ${{ property.price.toLocaleString('es-AR') }}
                  </div>
                  <div class="font-label-sm text-label-sm text-on-surface-variant">/ {{ property.price_type || 'día' }}</div>
                </div>
              </div>
              <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                <span class="font-label-md text-label-md font-semibold text-on-surface">
                  Capacidad: Hasta {{ property.capacity }} personas (diurno)
                </span>
              </div>
              <div v-if="property.amenities && property.amenities.length > 0" class="flex flex-wrap gap-1.5 mb-space-md">
                <span
                  v-for="amenity in property.amenities.slice(0, 4)"
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
              :to="`/alquileres/${property.slug}`"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">info</span>
              Ver Detalles y Reservar
            </RouterLink>
            <a
              v-if="property.host_whatsapp"
              :href="`https://wa.me/${property.host_whatsapp.replace(/[^0-9]/g, '')}?text=Hola,%20vi%20tu%20quinta%20en%20Turismo%20Seguro`"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm cursor-pointer"
              title="Abrir WhatsApp Directo con el Dueño"
            >
              <span class="material-symbols-outlined text-[20px]">chat</span>
            </a>
          </div>
        </article>
      </div>
    </div>
  </main>
</template>
