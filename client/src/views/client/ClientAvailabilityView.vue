<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { useClientProperties } from '../../composables/useClientProperties'
import type { Property, AvailabilityItem } from '../../types/catalog'
import {
  Calendar,
  ArrowLeft,
  Loader2,
  AlertCircle,
  CheckCircle,
  Lock,
  Unlock,
} from 'lucide-vue-next'

const route = useRoute()
const { getMyPropertyById, getPropertyAvailability, updatePropertyAvailability, isLoading, errorMessage } = useClientProperties()

const property = ref<Property | null>(null)
const availabilities = ref<AvailabilityItem[]>([])
const successNotice = ref<string | null>(null)
const errorNotice = ref<string | null>(null)

const form = ref({
  start_date: '',
  end_date: '',
  action: 'block' as 'block' | 'free',
})

const propertyId = Number(route.params.id)

const loadData = async () => {
  try {
    property.value = await getMyPropertyById(propertyId)
    availabilities.value = await getPropertyAvailability(propertyId)
  } catch {}
}

const handleSubmit = async () => {
  successNotice.value = null
  errorNotice.value = null

  try {
    const res = await updatePropertyAvailability(propertyId, {
      start_date: form.value.start_date,
      end_date: form.value.end_date,
      action: form.value.action,
    })

    successNotice.value = res.message || 'Calendario actualizado.'
    await loadData()
    form.value.start_date = ''
    form.value.end_date = ''
  } catch (err: any) {
    errorNotice.value = err.response?.data?.message || errorMessage.value || 'Error al actualizar.'
  }
}

onMounted(() => {
  loadData()
})
</script>

<template>
  <div class="min-h-screen bg-surface-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
      <div class="flex items-center gap-3">
        <RouterLink
          to="/panel/cliente"
          class="p-2 rounded-xl bg-white border border-slate-200 text-surface-600 hover:bg-surface-100 transition"
        >
          <ArrowLeft class="w-4 h-4" />
        </RouterLink>
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Disponibilidad del Alojamiento</span>
          <h1 class="text-2xl font-extrabold text-surface-900 font-display">
            {{ property?.title || 'Gestionar Calendario' }}
          </h1>
        </div>
      </div>

      <div v-if="successNotice" class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm text-emerald-800 flex items-center gap-2">
        <CheckCircle class="w-5 h-5 text-safe-600 flex-shrink-0" />
        <span>{{ successNotice }}</span>
      </div>

      <div v-if="errorNotice" class="p-4 bg-red-50 border border-red-200 rounded-2xl text-xs sm:text-sm text-red-700 flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ errorNotice }}</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Panel de Actualización -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4 md:col-span-1">
          <h3 class="text-sm font-bold text-surface-900 flex items-center gap-2">
            <Calendar class="w-4 h-4 text-brand-600" />
            <span>Bloquear / Liberar Rango</span>
          </h3>

          <form class="space-y-4" @submit.prevent="handleSubmit">
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Fecha de Inicio</label>
              <input
                v-model="form.start_date"
                type="date"
                required
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Fecha de Fin</label>
              <input
                v-model="form.end_date"
                type="date"
                required
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Acción</label>
              <div class="grid grid-cols-2 gap-2">
                <button
                  type="button"
                  :class="[
                    'py-2 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer',
                    form.action === 'block' ? 'bg-red-600 text-white' : 'bg-surface-100 text-surface-700'
                  ]"
                  @click="form.action = 'block'"
                >
                  <Lock class="w-3.5 h-3.5" />
                  <span>Bloquear</span>
                </button>
                <button
                  type="button"
                  :class="[
                    'py-2 px-3 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition cursor-pointer',
                    form.action === 'free' ? 'bg-emerald-600 text-white' : 'bg-surface-100 text-surface-700'
                  ]"
                  @click="form.action = 'free'"
                >
                  <Unlock class="w-3.5 h-3.5" />
                  <span>Liberar</span>
                </button>
              </div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white text-xs font-bold rounded-xl transition cursor-pointer"
            >
              <Loader2 v-if="isLoading" class="w-4 h-4 animate-spin mx-auto" />
              <span v-else>Guardar Cambios</span>
            </button>
          </form>
        </div>

        <!-- Lista de Fechas Bloqueadas -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4 md:col-span-2">
          <h3 class="text-sm font-bold text-surface-900">Fechas No Disponibles</h3>

          <div v-if="availabilities.length === 0" class="py-12 text-center text-xs text-surface-400">
            No tenés fechas bloqueadas. Todas las jornadas se encuentran disponibles para consultas.
          </div>

          <div v-else class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <div
              v-for="item in availabilities"
              :key="item.date"
              class="p-2.5 rounded-xl border border-red-100 bg-red-50/60 text-xs font-semibold text-red-800 flex items-center justify-between"
            >
              <span>{{ item.date }}</span>
              <span class="text-[10px] uppercase font-bold text-red-600 bg-white px-2 py-0.5 rounded-md border border-red-100">
                Bloqueado
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
