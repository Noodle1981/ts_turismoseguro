<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { useCatalog } from '../../composables/useCatalog'
import type { Property, AvailabilityItem } from '../../types/catalog'
import BadgeVerified from '../../components/common/BadgeVerified.vue'

const route = useRoute()
const { getPropertyBySlug, getPropertyAvailability, isLoading, errorMessage } = useCatalog()

const property = ref<Property | null>(null)
const availabilities = ref<AvailabilityItem[]>([])
const activeImageIndex = ref(0)
const isCopied = ref(false)

// Calculador interactivo de presupuesto diurno (Stitch 2.0)
const rentDate = ref(new Date().toISOString().slice(0, 10))
const selectedShift = ref<'full_day' | 'evening'>('full_day')
const guestCount = ref(20)

const shiftPrices = computed(() => {
  const base = property.value?.price || 45000
  return {
    full_day: base,
    evening: Math.round(base * 1.1),
  }
})

const extraGuestFee = 1500
const baseGuestsIncluded = 20

const extraGuestsCount = computed(() => {
  return Math.max(0, guestCount.value - baseGuestsIncluded)
})

const extraGuestsTotal = computed(() => {
  return extraGuestsCount.value * extraGuestFee
})

const currentShiftPrice = computed(() => {
  return shiftPrices.value[selectedShift.value]
})

const totalCalculated = computed(() => {
  return currentShiftPrice.value + extraGuestsTotal.value
})

const formattedTotal = computed(() => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(totalCalculated.value)
})

const incrementGuests = () => {
  const max = property.value?.capacity || 30
  if (guestCount.value < max) {
    guestCount.value++
  }
}

const decrementGuests = () => {
  if (guestCount.value > 1) {
    guestCount.value--
  }
}

const galleryImages = computed(() => {
  if (property.value?.images && property.value.images.length > 0) {
    return property.value.images.map(img => img.image_path)
  }
  return [
    'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80',
    'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=800&q=80',
  ]
})

const activeImage = computed(() => {
  return galleryImages.value[activeImageIndex.value] || galleryImages.value[0]
})

const formattedBasePrice = computed(() => {
  if (!property.value) return '$0'
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(property.value.price)
})

const whatsappBookingUrl = computed(() => {
  if (!property.value?.host_whatsapp) return '#'
  const cleanPhone = property.value.host_whatsapp.replace(/[^0-9]/g, '')
  const shiftText = selectedShift.value === 'full_day' ? 'Día Completo (10:00 a 20:30)' : 'Tarde / Noche (18:00 a 02:00)'
  const message = `¡Hola! Me comunico a través del portal oficial *Turismo Seguro San Juan* para reservar tu quinta *${property.value.title}* (ID: ${property.value.id}).\n\n📅 Fecha: ${rentDate.value}\n⏰ Turno: ${shiftText}\n👥 Cantidad de Personas: ${guestCount.value}\n💵 Presupuesto de Referencia: ${formattedTotal.value}\n\n¿Tienen disponibilidad para coordinar la seña oficial? Muchas gracias!`
  return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(message)}`
})

const copyLink = () => {
  if (typeof window !== 'undefined' && navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href)
    isCopied.value = true
    setTimeout(() => {
      isCopied.value = false
    }, 2000)
  }
}

onMounted(async () => {
  const slug = String(route.params.slug)
  try {
    property.value = await getPropertyBySlug(slug)
    if (property.value?.id) {
      availabilities.value = await getPropertyAvailability(property.value.id)
      if (property.value.capacity) {
        guestCount.value = Math.min(20, property.value.capacity)
      }
    }
  } catch (err) {
    console.error('Error al cargar la propiedad:', err)
  }
})
</script>

<template>
  <div class="min-h-screen bg-surface py-6 sm:py-8">
    <!-- Loading State -->
    <div v-if="isLoading" class="min-h-[60vh] flex flex-col items-center justify-center space-y-3">
      <span class="material-symbols-outlined text-[40px] text-secondary animate-spin">progress_activity</span>
      <p class="text-xs sm:text-sm text-on-surface-variant font-medium">Cargando detalles auditados de la quinta...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="errorMessage || !property" class="max-w-3xl mx-auto px-4 py-16 text-center space-y-4">
      <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 mx-auto flex items-center justify-center">
        <span class="material-symbols-outlined text-[32px]">warning</span>
      </div>
      <h2 class="text-2xl font-bold text-primary font-headline-sm">Quinta no encontrada</h2>
      <p class="text-sm text-on-surface-variant">
        {{ errorMessage || 'La propiedad solicitada no existe o no cuenta con certificación vigente in situ.' }}
      </p>
      <RouterLink
        to="/alquileres"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-on-primary bg-primary hover:bg-primary-container rounded-xl transition shadow-xs"
      >
        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
        <span>Ver todas las quintas</span>
      </RouterLink>
    </div>

    <!-- Main Content: Stitch 2.0 Layout -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 space-y-6">
      <!-- 1. Breadcrumbs -->
      <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs text-on-surface-variant font-medium">
        <RouterLink to="/" class="hover:text-primary transition-colors flex items-center gap-1">
          <span class="material-symbols-outlined text-[15px]">home</span>
          <span>Inicio</span>
        </RouterLink>
        <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
        <RouterLink to="/alquileres" class="hover:text-primary transition-colors">
          {{ property.department?.name || 'San Juan' }}
        </RouterLink>
        <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
        <span class="text-on-surface font-bold truncate max-w-xs">{{ property.title }}</span>
      </nav>

      <!-- 2. Property Header -->
      <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 pb-2">
        <div class="space-y-2">
          <div class="flex flex-wrap items-center gap-2">
            <!-- Official In Situ Badge -->
            <BadgeVerified size="md" :verified-at="property.verified_at ? property.verified_at.slice(0, 10) : '2026'" />

            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container text-on-surface-variant text-[11px] font-bold">
              <span class="material-symbols-outlined text-[15px] text-amber-500">wb_sunny</span>
              <span>Alquiler Diurno Exclusivo (Sin pernocte)</span>
            </span>

            <span
              v-if="property.verification_certificate_number"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed text-[11px] font-mono font-bold"
            >
              <span class="material-symbols-outlined text-[14px]">pin_drop</span>
              <span>Certificado Nº {{ property.verification_certificate_number }}</span>
            </span>
          </div>

          <h1 class="font-headline-xl text-2xl sm:text-4xl font-extrabold text-primary tracking-tight">
            {{ property.title }}
          </h1>

          <p class="text-xs sm:text-sm text-on-surface-variant flex items-center gap-1.5 font-medium">
            <span class="material-symbols-outlined text-[18px] text-secondary">location_on</span>
            <span>{{ property.location }} — <em>{{ property.department?.name || 'San Juan' }}</em></span>
          </p>
        </div>

        <div class="flex items-center gap-3">
          <button
            type="button"
            class="px-3.5 py-2 rounded-xl border border-surface-container hover:border-outline bg-surface-container-lowest text-on-surface text-xs font-bold flex items-center gap-1.5 transition cursor-pointer"
            @click="copyLink"
          >
            <span class="material-symbols-outlined text-[16px]">share</span>
            <span>{{ isCopied ? '¡Enlace copiado!' : 'Compartir' }}</span>
          </button>
        </div>
      </div>

      <!-- 3. Photo Gallery Bento Grid (Stitch 5-Photo Mosaic) -->
      <section class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-2 sm:gap-3 rounded-2xl overflow-hidden shadow-card-stitch bg-surface-container-lowest border border-surface-container">
        <!-- Main Large Photo (4 cols desktop) -->
        <div class="relative md:col-span-4 lg:col-span-4 h-72 sm:h-96 lg:h-[460px] group overflow-hidden bg-surface-container">
          <img
            :src="activeImage"
            :alt="property.title"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-primary/75 via-transparent to-transparent pointer-events-none"></div>

          <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
            <div class="space-y-1">
              <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary text-on-secondary text-[11px] font-bold shadow-md">
                <span class="material-symbols-outlined text-[15px]">fence</span>
                <span>Pileta con Cerco de Seguridad Homologado</span>
              </span>
              <p class="text-sm sm:text-base font-bold text-white drop-shadow-md">
                {{ property.address || 'Predio privado arbolado en San Juan' }}
              </p>
            </div>
            <span class="bg-surface-container-lowest/90 text-primary px-3 py-1 rounded-lg text-xs font-bold shadow-md">
              {{ galleryImages.length }} fotos
            </span>
          </div>
        </div>

        <!-- Right 4-Thumbnail Mosaic (2 cols desktop) -->
        <div class="md:col-span-4 lg:col-span-2 grid grid-cols-2 lg:grid-cols-1 gap-2 sm:gap-3 h-full">
          <button
            v-for="(imgUrl, idx) in galleryImages.slice(1, 5)"
            :key="idx"
            type="button"
            :class="[
              'relative h-28 sm:h-36 lg:h-[105px] group overflow-hidden rounded-lg transition-all cursor-pointer',
              activeImageIndex === idx + 1 ? 'ring-2 ring-secondary' : 'opacity-85 hover:opacity-100',
            ]"
            @click="activeImageIndex = idx + 1"
          >
            <img :src="imgUrl" :alt="property.title" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
            <div class="absolute inset-0 bg-primary/20 group-hover:bg-transparent transition-colors"></div>
          </button>
        </div>
      </section>

      <!-- 4. Content Split (65% Main / 35% Reservation Sticky Card) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pt-2">
        <!-- LEFT COLUMN (8 cols desktop) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-6">
          <!-- Ficha Técnica Rápida de Uso Diurno -->
          <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-card-stitch border border-surface-container space-y-4">
            <div class="flex items-center justify-between pb-3 bg-surface-container-low px-4 py-3 rounded-xl border border-surface-container">
              <h2 class="font-headline-sm text-base sm:text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">assignment</span>
                <span>Ficha Técnica de Uso Diurno</span>
              </h2>
              <span class="text-[11px] text-secondary bg-surface-container-lowest px-2.5 py-1 rounded-full uppercase tracking-wider font-extrabold border border-secondary/30">
                Protocolo TS San Juan
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div class="p-3.5 bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shadow-xs">
                  <span class="material-symbols-outlined text-[22px]">groups</span>
                </div>
                <div>
                  <span class="text-[11px] text-on-surface-variant block font-medium">Capacidad Diurna</span>
                  <span class="text-base font-extrabold text-on-surface">Hasta {{ property.capacity }} pers.</span>
                  <span class="text-[10px] text-on-surface-variant block">Exclusivo familiar</span>
                </div>
              </div>

              <div class="p-3.5 bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shadow-xs">
                  <span class="material-symbols-outlined text-[22px]">schedule</span>
                </div>
                <div>
                  <span class="text-[11px] text-on-surface-variant block font-medium">Horario de Turnos</span>
                  <span class="text-base font-extrabold text-on-surface">10:00 a 20:30</span>
                  <span class="text-[10px] text-on-surface-variant block">Turno Día Completo</span>
                </div>
              </div>

              <div class="p-3.5 bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-secondary shadow-xs">
                  <span class="material-symbols-outlined text-[22px]">pool</span>
                </div>
                <div>
                  <span class="text-[11px] text-on-surface-variant block font-medium">Seguridad de Pileta</span>
                  <span class="text-base font-extrabold text-on-surface">Cerca de niños</span>
                  <span class="text-[10px] text-on-surface-variant block">Filtrado diario</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Descripción y Equipamiento para Asados -->
          <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-card-stitch border border-surface-container space-y-4">
            <h2 class="font-headline-sm text-base sm:text-lg font-bold text-primary flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[22px]">outdoor_grill</span>
              <span>Descripción y Equipamiento para Asados</span>
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed font-medium whitespace-pre-line">
              {{ property.description }}
            </p>

            <!-- Amenities Chips -->
            <div v-if="property.amenities && property.amenities.length > 0" class="pt-4 border-t border-surface-container space-y-2">
              <span class="text-xs font-bold text-primary uppercase tracking-wider block">
                Comodidades Auditadas en el Predio:
              </span>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="amenity in property.amenities"
                  :key="amenity"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low text-on-surface text-xs font-semibold border border-surface-container"
                >
                  <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                  <span>{{ amenity }}</span>
                </span>
              </div>
            </div>
          </div>

          <!-- INFORME DEL AUDITOR DE TURISMO SEGURO (Anti-Estafas Transparencia) -->
          <div class="bg-primary text-on-primary p-6 sm:p-8 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-10 pointer-events-none">
              <span class="material-symbols-outlined text-[180px]">verified</span>
            </div>

            <div class="relative z-10 space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-white/10">
                <div class="flex items-center gap-3">
                  <div class="w-12 h-12 rounded-xl bg-secondary flex items-center justify-center text-white shadow-md">
                    <span class="material-symbols-outlined text-[28px]">policy</span>
                  </div>
                  <div>
                    <span class="text-[11px] text-secondary-fixed uppercase tracking-wider block font-bold">Auditoría Presencial Oficial</span>
                    <h3 class="font-headline-sm text-lg font-bold text-on-primary">Informe del Validador Técnico TS</h3>
                  </div>
                </div>
                <div class="bg-primary-container px-3 py-1.5 rounded-lg">
                  <span class="text-[10px] text-on-primary-container block">Validador Matriculado</span>
                  <span class="text-xs text-on-primary font-bold">Equipo Técnico Turismo Seguro (#TS-SJ)</span>
                </div>
              </div>

              <p class="text-xs text-on-primary/80 leading-relaxed font-medium">
                Esta quinta fue inspeccionada físicamente en el departamento. Se cotejó la titularidad real de la tierra, la veracidad del cerco perimetral, estado del agua de pileta y los datos bancarios del anfitrión oficial.
              </p>

              <!-- Checklist -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-primary-container/60 backdrop-blur-xs">
                  <span class="material-symbols-outlined text-secondary-fixed text-[18px] mt-0.5">check_circle</span>
                  <div>
                    <span class="text-xs font-bold text-on-primary block">Agua de Pileta Inspeccionada</span>
                    <span class="text-[11px] text-on-primary/70">Clorado correcto, filtros funcionando.</span>
                  </div>
                </div>

                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-primary-container/60 backdrop-blur-xs">
                  <span class="material-symbols-outlined text-secondary-fixed text-[18px] mt-0.5">check_circle</span>
                  <div>
                    <span class="text-xs font-bold text-on-primary block">Perímetro Cerrado y Seguro</span>
                    <span class="text-[11px] text-on-primary/70">Cerco perimetral para niños homologado.</span>
                  </div>
                </div>

                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-primary-container/60 backdrop-blur-xs">
                  <span class="material-symbols-outlined text-secondary-fixed text-[18px] mt-0.5">check_circle</span>
                  <div>
                    <span class="text-xs font-bold text-on-primary block">CBU y Alias Auditados</span>
                    <span class="text-[11px] text-on-primary/70">Cuenta bancaria cotejada contra DNI real.</span>
                  </div>
                </div>

                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-primary-container/60 backdrop-blur-xs">
                  <span class="material-symbols-outlined text-secondary-fixed text-[18px] mt-0.5">check_circle</span>
                  <div>
                    <span class="text-xs font-bold text-on-primary block">Trato Directo sin Comisión</span>
                    <span class="text-[11px] text-on-primary/70">0% de recargo por reserva a inquilinos.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN: Sticky Reservation & Live Day Quotation Card (Stitch 2.0) -->
        <aside class="lg:col-span-5 xl:col-span-4 sticky top-24 space-y-4">
          <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-modal-stitch border border-surface-container space-y-5">
            <!-- Price Header -->
            <div class="flex items-start justify-between pb-3 border-b border-surface-container">
              <div>
                <span class="text-[11px] text-on-surface-variant uppercase tracking-wider block font-bold">Valor Base Diurno</span>
                <div class="flex items-baseline gap-1">
                  <span class="font-headline-xl text-3xl font-extrabold text-primary">{{ formattedBasePrice }}</span>
                  <span class="text-xs text-on-surface-variant font-medium">/ jornada</span>
                </div>
              </div>
              <div class="text-right">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed text-[11px] font-bold">
                  <span class="material-symbols-outlined text-[13px]">handshake</span>
                  <span>Trato Directo</span>
                </span>
                <span class="block text-[10px] text-secondary font-bold mt-1">0% Comisión</span>
              </div>
            </div>

            <!-- 1. Date Selector -->
            <div>
              <label class="block text-xs font-bold text-primary mb-1">
                Fecha del Día de Campo:
              </label>
              <input
                v-model="rentDate"
                type="date"
                class="w-full bg-surface-container-low text-on-surface text-xs font-semibold px-3.5 py-2.5 rounded-xl border border-surface-container focus:outline-none focus:border-primary"
              />
            </div>

            <!-- 2. Shift Selector (Turno) -->
            <div>
              <label class="block text-xs font-bold text-primary mb-1.5">
                Seleccioná la Franja Horaria:
              </label>
              <div class="space-y-2">
                <!-- Option 1: Día Completo -->
                <label
                  :class="[
                    'flex items-center justify-between p-3 rounded-xl border cursor-pointer transition-all',
                    selectedShift === 'full_day'
                      ? 'bg-surface-container-low border-primary/40 shadow-xs'
                      : 'bg-surface-container-lowest border-surface-container hover:bg-surface-container-low',
                  ]"
                >
                  <div class="flex items-center gap-2.5">
                    <input
                      v-model="selectedShift"
                      type="radio"
                      value="full_day"
                      class="text-secondary accent-secondary"
                    />
                    <div>
                      <div class="flex items-center gap-1 text-xs font-bold text-primary">
                        <span class="material-symbols-outlined text-[15px] text-amber-500">sunny</span>
                        <span>Día Completo Familiar</span>
                      </div>
                      <span class="text-[10px] text-on-surface-variant">10:00 a 20:30 hs</span>
                    </div>
                  </div>
                  <span class="text-xs font-bold text-primary">${{ shiftPrices.full_day.toLocaleString('es-AR') }}</span>
                </label>

                <!-- Option 2: Tarde / Noche -->
                <label
                  :class="[
                    'flex items-center justify-between p-3 rounded-xl border cursor-pointer transition-all',
                    selectedShift === 'evening'
                      ? 'bg-surface-container-low border-primary/40 shadow-xs'
                      : 'bg-surface-container-lowest border-surface-container hover:bg-surface-container-low',
                  ]"
                >
                  <div class="flex items-center gap-2.5">
                    <input
                      v-model="selectedShift"
                      type="radio"
                      value="evening"
                      class="text-secondary accent-secondary"
                    />
                    <div>
                      <div class="flex items-center gap-1 text-xs font-bold text-primary">
                        <span class="material-symbols-outlined text-[15px] text-primary">bedtime</span>
                        <span>Turno Tarde / Cena</span>
                      </div>
                      <span class="text-[10px] text-on-surface-variant">18:00 a 02:00 hs</span>
                    </div>
                  </div>
                  <span class="text-xs font-bold text-primary">${{ shiftPrices.evening.toLocaleString('es-AR') }}</span>
                </label>
              </div>
            </div>

            <!-- 3. Guest Count Stepper -->
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <label class="text-xs font-bold text-primary">
                  Cantidad Total de Personas:
                </label>
                <span class="text-[10px] text-on-surface-variant">Base: hasta {{ baseGuestsIncluded }}</span>
              </div>
              <div class="flex items-center justify-between bg-surface-container-low p-2 rounded-xl border border-surface-container">
                <button
                  type="button"
                  aria-label="Restar una persona"
                  class="w-9 h-9 rounded-lg bg-surface-container-lowest text-primary flex items-center justify-center font-bold hover:bg-surface-container transition-all shadow-xs cursor-pointer"
                  @click="decrementGuests"
                >
                  <span class="material-symbols-outlined text-[18px]">remove</span>
                </button>
                <div class="text-center">
                  <span class="text-xl font-extrabold text-primary">{{ guestCount }}</span>
                  <span class="text-[10px] text-on-surface-variant block">invitados (máx. {{ property.capacity }})</span>
                </div>
                <button
                  type="button"
                  aria-label="Sumar una persona"
                  class="w-9 h-9 rounded-lg bg-surface-container-lowest text-primary flex items-center justify-center font-bold hover:bg-surface-container transition-all shadow-xs cursor-pointer"
                  @click="incrementGuests"
                >
                  <span class="material-symbols-outlined text-[18px]">add</span>
                </button>
              </div>
            </div>

            <!-- 4. Quotation Breakdown -->
            <div class="bg-surface-container-low p-3.5 rounded-xl space-y-2 text-xs border border-surface-container">
              <div class="flex justify-between text-on-surface-variant">
                <span>Valor del turno:</span>
                <span class="font-bold text-primary">${{ currentShiftPrice.toLocaleString('es-AR') }}</span>
              </div>
              <div v-if="extraGuestsCount > 0" class="flex justify-between text-on-surface-variant">
                <span>Invitados adicionales (+{{ extraGuestsCount }}):</span>
                <span class="font-bold text-primary">${{ extraGuestsTotal.toLocaleString('es-AR') }}</span>
              </div>
              <div class="pt-2 border-t border-surface-container-highest flex justify-between items-baseline font-bold">
                <span class="text-primary">Presupuesto Referencia:</span>
                <span class="text-lg font-extrabold text-secondary">{{ formattedTotal }}</span>
              </div>
            </div>

            <!-- 5. High-Conversion WhatsApp CTA -->
            <div class="space-y-2">
              <a
                :href="whatsappBookingUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-secondary text-on-secondary font-bold text-xs shadow-safe-glow hover:bg-secondary/90 transition-all text-center cursor-pointer"
              >
                <span class="material-symbols-outlined text-[20px]">chat</span>
                <div class="flex flex-col items-start text-left leading-tight">
                  <span>Reservar vía WhatsApp Verificado</span>
                  <span class="text-[10px] font-normal opacity-90">Trato directo con el anfitrión auditado</span>
                </div>
              </a>

              <a
                v-if="property.host_phone"
                :href="`tel:${property.host_phone}`"
                class="w-full flex items-center justify-center gap-1.5 py-2.5 px-4 rounded-xl border border-surface-container hover:bg-surface-container-low text-on-surface text-xs font-bold transition text-center"
              >
                <span class="material-symbols-outlined text-[16px] text-primary">phone</span>
                <span>Llamar al Anfitrión</span>
              </a>
            </div>

            <p class="text-[10px] text-on-surface-variant text-center font-medium leading-tight">
              Sin comisiones por servicio. Contactás de forma transparente al dueño legítimo.
            </p>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>
