<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useApi } from '../composables/useApi'
import type { HealthCheckResponse } from '../types/health'
import BadgeVerified from '../components/common/BadgeVerified.vue'
import { Search, Activity, CheckCircle, AlertTriangle, RefreshCw } from 'lucide-vue-next'

const { api } = useApi()

const healthData = ref<HealthCheckResponse | null>(null)
const isLoadingHealth = ref(true)
const healthError = ref<string | null>(null)

const checkBackendHealth = async () => {
  isLoadingHealth.value = true
  healthError.value = null
  try {
    const res = await api.get<HealthCheckResponse>('/health')
    healthData.value = res.data
  } catch (err: unknown) {
    healthError.value = 'No se pudo conectar con el backend de Laravel (http://localhost:8000/api/v1/health).'
  } finally {
    isLoadingHealth.value = false
  }
}

onMounted(() => {
  checkBackendHealth()
})
</script>

<template>
  <main class="flex-1">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-brand-950 via-brand-900 to-slate-900 text-white overflow-hidden py-20 lg:py-28">
      <!-- Decorative background glow -->
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.18),transparent_50%)]"></div>
      <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-surface-50 to-transparent"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs sm:text-sm font-medium text-brand-100">
          <span>Garantía Oficial Anti-Fraudes</span>
          <span class="text-white/40">•</span>
          <BadgeVerified size="sm" />
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight">
          Alquilá en San Juan con <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400">Total Confianza</span>
        </h1>

        <p class="text-base sm:text-xl text-brand-100 max-w-2xl mx-auto font-normal leading-relaxed">
          Cada cabaña, casa quinta y experiencia turística publicada fue inspeccionada físicamente por un validador matriculado en terreno.
        </p>

        <!-- Search Bar Placeholder (Glassmorphism) -->
        <div class="max-w-3xl mx-auto bg-white/95 backdrop-blur-xl p-3 sm:p-4 rounded-2xl shadow-2xl border border-white/30 text-surface-900 text-left">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
            <div class="px-3 py-2 border-b sm:border-b-0 sm:border-r border-slate-200">
              <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider">Destino</label>
              <span class="text-sm font-medium text-surface-800">Calingasta, Ullum, Valle Fértil...</span>
            </div>
            <div class="px-3 py-2 border-b sm:border-b-0 sm:border-r border-slate-200">
              <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider">Huéspedes</label>
              <span class="text-sm font-medium text-surface-800">¿Cuántas personas?</span>
            </div>
            <div>
              <button
                type="button"
                class="w-full flex items-center justify-center gap-2 px-5 py-3 text-sm font-bold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 rounded-xl shadow-md transition-all duration-200 cursor-pointer"
              >
                <Search class="w-4 h-4" />
                <span>Buscar Seguro</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Health Check Status Box (Live API Integration Check) -->
    <section class="max-w-4xl mx-auto px-4 -mt-8 relative z-10 mb-16">
      <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200/80">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center text-brand-700">
              <Activity class="w-5 h-5 text-brand-600" />
            </div>
            <div>
              <h2 class="text-base font-bold text-surface-900">Estado de Conexión API (Fase 1)</h2>
              <p class="text-xs text-surface-500">Prueba de contrato OpenAPI en tiempo real con Laravel API</p>
            </div>
          </div>

          <button
            type="button"
            :disabled="isLoadingHealth"
            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-brand-700 hover:bg-brand-50 rounded-lg transition-colors cursor-pointer disabled:opacity-50"
            @click="checkBackendHealth"
          >
            <RefreshCw class="w-3.5 h-3.5" :class="{ 'animate-spin': isLoadingHealth }" />
            <span>Reverificar</span>
          </button>
        </div>

        <div class="pt-4">
          <!-- Loading state -->
          <div v-if="isLoadingHealth" class="py-4 flex items-center gap-2 text-sm text-surface-500">
            <div class="w-4 h-4 border-2 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
            <span>Consultando endpoint <code>GET /api/v1/health</code>...</span>
          </div>

          <!-- Success state -->
          <div v-else-if="healthData" class="space-y-3">
            <div class="flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">
              <CheckCircle class="w-4 h-4 flex-shrink-0" />
              <span class="font-medium">Backend conectado y saludable: <strong>status: {{ healthData.status }}</strong></span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
              <div class="p-2.5 rounded-lg bg-surface-50 border border-slate-200/60">
                <span class="block text-surface-400">Entorno</span>
                <span class="font-semibold text-surface-800">{{ healthData.environment }}</span>
              </div>
              <div class="p-2.5 rounded-lg bg-surface-50 border border-slate-200/60">
                <span class="block text-surface-400">Base de Datos</span>
                <span class="font-semibold text-emerald-700">{{ healthData.services?.database }}</span>
              </div>
              <div class="p-2.5 rounded-lg bg-surface-50 border border-slate-200/60">
                <span class="block text-surface-400">Caché</span>
                <span class="font-semibold text-emerald-700">{{ healthData.services?.cache }}</span>
              </div>
              <div class="p-2.5 rounded-lg bg-surface-50 border border-slate-200/60">
                <span class="block text-surface-400">Timestamp</span>
                <span class="font-mono text-surface-700 tabular-nums">{{ new Date(healthData.timestamp).toLocaleTimeString() }}</span>
              </div>
            </div>
          </div>

          <!-- Error state -->
          <div v-else-if="healthError" class="flex items-start gap-2 text-sm text-amber-800 bg-amber-50 border border-amber-200 p-3 rounded-xl">
            <AlertTriangle class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" />
            <div class="space-y-1">
              <p class="font-medium">{{ healthError }}</p>
              <p class="text-xs text-amber-700">Asegúrate de que <code>php artisan serve</code> esté corriendo en <code>http://localhost:8000</code>.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
