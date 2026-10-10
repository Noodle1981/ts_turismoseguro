<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { useCatalog } from '../../composables/useCatalog'
import type { Property, AvailabilityItem } from '../../types/catalog'
import BadgeVerified from '../../components/common/BadgeVerified.vue'
import {
  MapPin,
  Users,
  Bed,
  Bath,
  ShieldCheck,
  Calendar,
  MessageCircle,
  Phone,
  Check,
  AlertCircle,
  Loader2,
  ChevronLeft,
  Share2,
  CheckCircle2,
  FileText,
} from 'lucide-vue-next'

const route = useRoute()
const { getPropertyBySlug, getPropertyAvailability, isLoading, errorMessage } = useCatalog()

const property = ref<Property | null>(null)
const availabilities = ref<AvailabilityItem[]>([])
const activeImageIndex = ref(0)

// Formulario de consulta
const bookingForm = ref({
  checkinDate: '',
  checkoutDate: '',
  guests: 2,
})
const bookingSuccessMessage = ref<string | null>(null)

const activeImage = computed(() => {
  if (property.value?.images && property.value.images.length > 0) {
    return property.value.images[activeImageIndex.value]?.image_path || property.value.images[0].image_path
  }
  return '/img/propiedades/placeholder.jpg'
})

const formattedPrice = computed(() => {
  if (!property.value) return '$0'
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(property.value.price)
})

const whatsappUrl = computed(() => {
  if (!property.value?.host_whatsapp) return null
  const cleanPhone = property.value.host_whatsapp.replace(/[^0-9]/g, '')
  const text = encodeURIComponent(
    `Hola! Vi tu propiedad "${property.value.title}" verificada en Turismo Seguro San Juan y quería consultar disponibilidad.`
  )
  return `https://wa.me/${cleanPhone}?text=${text}`
})

const isCopied = ref(false)
const copyLink = () => {
  if (typeof window !== 'undefined' && navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href)
    isCopied.value = true
    setTimeout(() => {
      isCopied.value = false
    }, 2000)
  }
}

const handleBookingSubmit = () => {
  bookingSuccessMessage.value = `¡Tu solicitud para ${bookingForm.value.guests} persona(s) ha sido enviada al anfitrión! Te contactarán a la brevedad.`
}

onMounted(async () => {
  const slug = String(route.params.slug)
  try {
    property.value = await getPropertyBySlug(slug)
    if (property.value?.id) {
      availabilities.value = await getPropertyAvailability(property.value.id)
    }
  } catch (err) {
    // Handled
  }
})
</script>

<template>
  <div class="min-h-screen bg-surface-50 py-8">
    <!-- Loading State -->
    <div v-if="isLoading" class="min-h-[60vh] flex flex-col items-center justify-center space-y-3">
      <Loader2 class="w-8 h-8 text-brand-600 animate-spin" />
      <p class="text-sm text-surface-500 font-medium">Cargando detalles de la propiedad oficial...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="errorMessage || !property" class="max-w-3xl mx-auto px-4 py-16 text-center space-y-4">
      <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 mx-auto flex items-center justify-center">
        <AlertCircle class="w-8 h-8" />
      </div>
      <h2 class="text-2xl font-bold text-surface-900 font-display">Alojamiento no encontrado</h2>
      <p class="text-sm text-surface-500">
        {{ errorMessage || 'La propiedad solicitada no existe o no cuenta con certificación vigente.' }}
      </p>
      <RouterLink
        to="/alquileres"
        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 rounded-xl transition shadow-xs"
      >
        <ChevronLeft class="w-4 h-4" />
        <span>Ver todos los alquileres</span>
      </RouterLink>
    </div>

    <!-- Main Content -->
    <div v-else class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
      <!-- Breadcrumb Navigation -->
      <nav class="flex items-center gap-2 text-xs text-surface-500">
        <RouterLink to="/" class="hover:text-brand-600">Inicio</RouterLink>
        <span>/</span>
        <RouterLink to="/alquileres" class="hover:text-brand-600">Alquileres</RouterLink>
        <span>/</span>
        <span class="text-surface-800 font-medium truncate max-w-xs">{{ property.title }}</span>
      </nav>

      <!-- Property Header: Title, Badges, Location -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-2">
        <div class="space-y-2">
          <div class="flex flex-wrap items-center gap-2">
            <BadgeVerified size="md" />
            <span
              v-if="property.verification_certificate_number"
              class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-safe-50 text-safe-700 border border-safe-200"
            >
              Certificado Nº {{ property.verification_certificate_number }}
            </span>
          </div>

          <h1 class="text-2xl sm:text-4xl font-extrabold text-surface-900 font-display tracking-tight">
            {{ property.title }}
          </h1>

          <div class="flex items-center gap-2 text-sm text-surface-600">
            <MapPin class="w-4 h-4 text-brand-600 flex-shrink-0" />
            <span>{{ property.location }}</span>
            <span class="text-surface-300">·</span>
            <span class="font-semibold text-brand-800">{{ property.property_type }}</span>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <button
            type="button"
            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-surface-700 hover:bg-surface-50 text-xs font-semibold flex items-center gap-1.5 transition cursor-pointer"
            @click="copyLink"
          >
            <Share2 class="w-3.5 h-3.5" />
            <span>{{ isCopied ? '¡Enlace copiado!' : 'Compartir' }}</span>
          </button>
        </div>
      </div>

      <!-- Gallery Grid -->
      <div class="space-y-3">
        <!-- Main Highlight Image -->
        <div class="relative aspect-16/9 md:aspect-21/9 rounded-3xl overflow-hidden bg-surface-100 shadow-md">
          <img
            :src="activeImage"
            :alt="property.title"
            class="w-full h-full object-cover transition-opacity duration-300"
          />
          <div class="absolute bottom-4 left-4 bg-brand-950/70 backdrop-blur-md px-3 py-1.5 rounded-xl text-xs text-white font-medium flex items-center gap-2">
            <ShieldCheck class="w-4 h-4 text-safe-400" />
            <span>Fotografía auditada por inspección técnica</span>
          </div>
        </div>

        <!-- Thumbnails carousel -->
        <div v-if="property.images && property.images.length > 1" class="flex gap-3 overflow-x-auto pb-2">
          <button
            v-for="(img, idx) in property.images"
            :key="img.id"
            type="button"
            :class="[
              'relative w-24 h-16 rounded-xl overflow-hidden flex-shrink-0 border-2 transition-all cursor-pointer',
              activeImageIndex === idx ? 'border-brand-600 ring-2 ring-brand-500/30' : 'border-transparent opacity-70 hover:opacity-100'
            ]"
            @click="activeImageIndex = idx"
          >
            <img :src="img.image_path" :alt="img.caption || ''" class="w-full h-full object-cover" />
          </button>
        </div>
      </div>

      <!-- Main Layout: Content + Booking Sidebar -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start pt-4">
        <!-- Left 2 Cols: Details -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Overview Badges -->
          <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs grid grid-cols-3 gap-4 text-center">
            <div class="border-r border-slate-100 pr-2">
              <Users class="w-5 h-5 text-brand-600 mx-auto mb-1" />
              <p class="text-xs text-surface-400">Capacidad</p>
              <p class="text-sm sm:text-base font-bold text-surface-900">{{ property.capacity }} Personas</p>
            </div>
            <div class="border-r border-slate-100 pr-2">
              <Bed class="w-5 h-5 text-brand-600 mx-auto mb-1" />
              <p class="text-xs text-surface-400">Habitaciones</p>
              <p class="text-sm sm:text-base font-bold text-surface-900">{{ property.bedrooms }} Dormitorios</p>
            </div>
            <div>
              <Bath class="w-5 h-5 text-brand-600 mx-auto mb-1" />
              <p class="text-xs text-surface-400">Baños</p>
              <p class="text-sm sm:text-base font-bold text-surface-900">{{ property.bathrooms }} Baños</p>
            </div>
          </div>

          <!-- Description Section -->
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h2 class="text-xl font-bold text-surface-900 font-display">Descripción del Alojamiento</h2>
            <p class="text-sm text-surface-600 leading-relaxed whitespace-pre-line">
              {{ property.description }}
            </p>
          </div>

          <!-- Amenities Section -->
          <div v-if="property.amenities && property.amenities.length > 0" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h2 class="text-xl font-bold text-surface-900 font-display">Comodidades y Servicios</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <div
                v-for="amenity in property.amenities"
                :key="amenity"
                class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-50 border border-slate-100 text-xs font-semibold text-surface-800"
              >
                <div class="w-5 h-5 rounded-full bg-safe-100 text-safe-700 flex items-center justify-center flex-shrink-0">
                  <Check class="w-3.5 h-3.5" />
                </div>
                <span>{{ amenity }}</span>
              </div>
            </div>
          </div>

          <!-- House Rules Section -->
          <div v-if="property.rules && property.rules.length > 0" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h2 class="text-xl font-bold text-surface-900 font-display flex items-center gap-2">
              <FileText class="w-5 h-5 text-brand-600" />
              <span>Reglas de Convivencia y Uso</span>
            </h2>
            <ul class="space-y-2 text-xs text-surface-600">
              <li v-for="rule in property.rules" :key="rule" class="flex items-start gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 mt-1.5 flex-shrink-0"></span>
                <span>{{ rule }}</span>
              </li>
            </ul>
          </div>

          <!-- Official Security Guarantee Box -->
          <div class="bg-gradient-to-br from-emerald-50 via-safe-50/50 to-brand-50 p-6 rounded-2xl border border-safe-200 space-y-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-safe-600 text-white flex items-center justify-center flex-shrink-0">
                <ShieldCheck class="w-6 h-6" />
              </div>
              <div>
                <h3 class="text-sm font-bold text-surface-900">Garantía Turismo Seguro San Juan</h3>
                <p class="text-xs text-surface-600">
                  Alojamiento inspeccionado física y documentalmente. Tu reserva cuenta con respaldo ministerial.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Col: Booking & Host Contact Sidebar (Sticky) -->
        <aside class="space-y-6 sticky top-24">
          <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-lg space-y-6">
            <!-- Price Display -->
            <div class="border-b border-slate-100 pb-4">
              <span class="text-xs text-surface-400 block font-medium">Tarifa por jornada</span>
              <p class="font-display font-extrabold text-brand-900 text-3xl leading-tight">
                {{ formattedPrice }}
                <span class="text-xs font-normal text-surface-500">/ {{ property.price_type }}</span>
              </p>
              <p class="text-[11px] text-surface-400 mt-1">Precio regulado y sin comisiones ocultas.</p>
            </div>

            <!-- Booking Inquiry Form -->
            <form class="space-y-4" @submit.prevent="handleBookingSubmit">
              <h3 class="text-xs font-bold text-surface-900 uppercase tracking-wider flex items-center gap-1.5">
                <Calendar class="w-4 h-4 text-brand-600" />
                <span>Consultar Disponibilidad</span>
              </h3>

              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-[11px] font-semibold text-surface-600 mb-1">Llegada</label>
                  <input
                    v-model="bookingForm.checkinDate"
                    type="date"
                    required
                    class="w-full px-2.5 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"
                  />
                </div>
                <div>
                  <label class="block text-[11px] font-semibold text-surface-600 mb-1">Salida</label>
                  <input
                    v-model="bookingForm.checkoutDate"
                    type="date"
                    required
                    class="w-full px-2.5 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label class="block text-[11px] font-semibold text-surface-600 mb-1">Cantidad de Huéspedes</label>
                <input
                  v-model.number="bookingForm.guests"
                  type="number"
                  min="1"
                  :max="property.capacity"
                  required
                  class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none"
                />
              </div>

              <div v-if="bookingSuccessMessage" class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-start gap-2">
                <CheckCircle2 class="w-4 h-4 text-safe-600 mt-0.5 flex-shrink-0" />
                <span>{{ bookingSuccessMessage }}</span>
              </div>

              <button
                type="submit"
                class="w-full py-3 px-4 bg-brand-600 hover:bg-brand-700 active:scale-95 text-white font-semibold rounded-xl text-xs shadow-md shadow-brand-500/20 transition cursor-pointer"
              >
                Enviar Solicitud al Anfitrión
              </button>
            </form>

            <!-- Direct Host Contact Buttons -->
            <div class="border-t border-slate-100 pt-4 space-y-2">
              <p class="text-[11px] text-surface-500 text-center font-medium">O comunicate de manera directa:</p>

              <a
                v-if="whatsappUrl"
                :href="whatsappUrl"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs transition shadow-xs cursor-pointer"
              >
                <MessageCircle class="w-4 h-4" />
                <span>Contactar por WhatsApp</span>
              </a>

              <a
                v-if="property.host_phone"
                :href="`tel:${property.host_phone}`"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-slate-200 hover:bg-surface-50 text-surface-700 font-semibold rounded-xl text-xs transition cursor-pointer"
              >
                <Phone class="w-4 h-4" />
                <span>Llamar al Anfitrión</span>
              </a>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>
