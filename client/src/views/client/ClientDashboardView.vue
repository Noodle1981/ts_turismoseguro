<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { useClientProperties } from '../../composables/useClientProperties'
import type { Property } from '../../types/catalog'
import VerificationStatusTimeline from '../../components/client/VerificationStatusTimeline.vue'
import BadgeVerified from '../../components/common/BadgeVerified.vue'
import {
  Building2,
  Plus,
  Calendar,
  AlertCircle,
  Loader2,
  Eye,
  CheckCircle,
  Send,
} from 'lucide-vue-next'

const { user } = useAuth()
const { getMyProperties, requestVerification, isLoading, errorMessage } = useClientProperties()

const properties = ref<Property[]>([])
const requestingId = ref<number | null>(null)
const successNotice = ref<string | null>(null)

const loadProperties = async () => {
  try {
    properties.value = await getMyProperties()
  } catch {}
}

const handleRequestVerification = async (propertyId: number) => {
  requestingId.value = propertyId
  successNotice.value = null
  try {
    const res = await requestVerification(propertyId, 'Solicitud iniciada desde el dashboard del anfitrión.')
    successNotice.value = res.message || 'Solicitud de verificación enviada.'
    await loadProperties()
  } catch (err: any) {
    alert(err.response?.data?.message || 'Error al solicitar validación.')
  } finally {
    requestingId.value = null
  }
}

onMounted(() => {
  loadProperties()
})
</script>

<template>
  <div class="min-h-screen bg-surface-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Top Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Panel Oficial del Anfitrión</span>
          <h1 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
            Mis Alojamientos y Certificaciones
          </h1>
          <p class="text-xs sm:text-sm text-surface-500 mt-1">
            Bienvenido, <strong class="text-surface-800">{{ user?.name }}</strong>. Gestioná tus publicaciones, disponibilidad y solicitudes de sello.
          </p>
        </div>

        <div>
          <RouterLink
            to="/panel/cliente/propiedades/nueva"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-accent-500 hover:bg-accent-600 text-white text-xs sm:text-sm font-bold rounded-xl shadow-md transition"
          >
            <Plus class="w-4 h-4" />
            <span>Publicar Nuevo Alojamiento</span>
          </RouterLink>
        </div>
      </div>

      <!-- Success Alert -->
      <div v-if="successNotice" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm text-emerald-800 flex items-center gap-2">
        <CheckCircle class="w-5 h-5 text-safe-600 flex-shrink-0" />
        <span>{{ successNotice }}</span>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && properties.length === 0" class="py-20 text-center flex flex-col items-center justify-center space-y-3">
        <Loader2 class="w-8 h-8 text-brand-600 animate-spin" />
        <p class="text-sm text-surface-500 font-medium">Cargando tus propiedades...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="errorMessage" class="p-6 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-700">
        <AlertCircle class="w-6 h-6 flex-shrink-0" />
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Empty State -->
      <div v-else-if="properties.length === 0" class="bg-white rounded-3xl p-10 border border-slate-200/80 text-center space-y-4 max-w-lg mx-auto shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 mx-auto flex items-center justify-center">
          <Building2 class="w-8 h-8" />
        </div>
        <h3 class="text-lg font-bold text-surface-900">Aún no registraste ninguna propiedad</h3>
        <p class="text-xs text-surface-500 leading-relaxed">
          Para que tu alojamiento sea visible y certificado con el distintivo de Turismo Seguro, completá la ficha de registro.
        </p>
        <RouterLink
          to="/panel/cliente/propiedades/nueva"
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition"
        >
          <Plus class="w-4 h-4" />
          <span>Registrar Propiedad</span>
        </RouterLink>
      </div>

      <!-- Properties Grid with Status Timelines -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div
          v-for="prop in properties"
          :key="prop.id"
          class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs flex flex-col justify-between space-y-6"
        >
          <!-- Property Basic Info & Badge -->
          <div class="space-y-4">
            <div class="flex items-start justify-between gap-4">
              <div>
                <span class="text-xs font-semibold text-brand-700 uppercase tracking-wider">{{ prop.property_type }}</span>
                <h3 class="text-lg sm:text-xl font-bold text-surface-900 font-display mt-0.5">{{ prop.title }}</h3>
                <p class="text-xs text-surface-500">{{ prop.location }}</p>
              </div>

              <div>
                <BadgeVerified v-if="prop.verification_status === 'verified'" size="sm" />
                <span
                  v-else
                  class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200"
                >
                  Borrador / No visible
                </span>
              </div>
            </div>

            <!-- Verification Progress Tracking Component -->
            <VerificationStatusTimeline
              :status="prop.verification_status"
              :certificate-number="prop.verification_certificate_number"
            />
          </div>

          <!-- Actions Footer: Availability, View, Request Verification -->
          <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
              <RouterLink
                :to="`/panel/cliente/propiedades/${prop.id}/disponibilidad`"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-surface-50 hover:bg-surface-100 text-xs font-semibold text-surface-700 transition"
              >
                <Calendar class="w-3.5 h-3.5 text-brand-600" />
                <span>Gestionar Calendario</span>
              </RouterLink>

              <RouterLink
                v-if="prop.publication_status === 'published'"
                :to="`/alquileres/${prop.slug}`"
                target="_blank"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 bg-white hover:bg-surface-50 text-xs font-semibold text-surface-700 transition"
              >
                <Eye class="w-3.5 h-3.5" />
                <span>Ver en Catálogo</span>
              </RouterLink>
            </div>

            <!-- Solicitar Validación Button -->
            <button
              v-if="prop.verification_status !== 'verified'"
              type="button"
              :disabled="requestingId === prop.id || prop.verification_status === 'in_review'"
              class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition cursor-pointer"
              @click="handleRequestVerification(prop.id)"
            >
              <Loader2 v-if="requestingId === prop.id" class="w-3.5 h-3.5 animate-spin" />
              <Send v-else class="w-3.5 h-3.5" />
              <span>Solicitar Validación In Situ</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
