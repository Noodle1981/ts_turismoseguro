<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useClientProperties } from '../../composables/useClientProperties'
import { useCatalog } from '../../composables/useCatalog'
import type { Department } from '../../types/catalog'
import {
  Building2,
  ArrowLeft,
  Loader2,
  AlertCircle,
  Plus,
  Trash2,
} from 'lucide-vue-next'

const router = useRouter()
const { createProperty, isLoading, errorMessage } = useClientProperties()
const { getDepartments } = useCatalog()

const departments = ref<Department[]>([])
const form = ref({
  title: '',
  department_id: '' as unknown as number,
  property_type: 'Cabaña',
  location: '',
  address: '',
  description: '',
  price: 35000,
  price_type: '/noche',
  capacity: 4,
  bedrooms: 2,
  bathrooms: 1,
  host_whatsapp: '',
  host_phone: '',
  amenities: ['WiFi', 'Parrilla'],
  rules: ['No se permiten fiestas ni ruidos molestos después de las 23 hs.'],
})

const newAmenity = ref('')
const newRule = ref('')
const formError = ref<string | null>(null)

const propertyTypes = ['Cabaña', 'Quinta con Piscina', 'Departamento', 'Casa', 'Posada']

const addAmenity = () => {
  if (newAmenity.value.trim() && !form.value.amenities.includes(newAmenity.value.trim())) {
    form.value.amenities.push(newAmenity.value.trim())
    newAmenity.value = ''
  }
}

const removeAmenity = (index: number) => {
  form.value.amenities.splice(index, 1)
}

const addRule = () => {
  if (newRule.value.trim()) {
    form.value.rules.push(newRule.value.trim())
    newRule.value = ''
  }
}

const removeRule = (index: number) => {
  form.value.rules.splice(index, 1)
}

const handleSubmit = async () => {
  formError.value = null
  if (!form.value.department_id) {
    formError.value = 'Debés seleccionar un departamento de San Juan.'
    return
  }

  try {
    await createProperty({
      ...form.value,
      department_id: Number(form.value.department_id),
    })

    router.push('/panel/cliente')
  } catch (err: any) {
    formError.value = err.response?.data?.message || errorMessage.value || 'Error al guardar la propiedad.'
  }
}

onMounted(async () => {
  try {
    departments.value = await getDepartments()
  } catch {}
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
          <span class="text-xs font-bold uppercase tracking-wider text-brand-600">Registro de Alojamiento</span>
          <h1 class="text-2xl font-extrabold text-surface-900 font-display">Publicar Propiedad en Turismo Seguro</h1>
        </div>
      </div>

      <div v-if="formError" class="p-4 bg-red-50 border border-red-200 rounded-2xl text-xs sm:text-sm text-red-700 flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ formError }}</span>
      </div>

      <form class="space-y-6" @submit.prevent="handleSubmit">
        <!-- 1. Datos Principales -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
          <h3 class="text-base font-bold text-surface-900 flex items-center gap-2">
            <Building2 class="w-4 h-4 text-brand-600" />
            <span>Información Básica</span>
          </h3>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Título de la Propiedad</label>
              <input
                v-model="form.title"
                type="text"
                required
                placeholder="Ej: Cabaña Las Moras con Piscina"
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Departamento</label>
              <select
                v-model="form.department_id"
                required
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              >
                <option value="">Seleccioná un departamento</option>
                <option v-for="d in departments" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Tipo de Propiedad</label>
              <select
                v-model="form.property_type"
                required
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              >
                <option v-for="t in propertyTypes" :key="t" :value="t">{{ t }}</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Localidad / Zona</label>
              <input
                v-model="form.location"
                type="text"
                required
                placeholder="Ej: Barreal, Calingasta"
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Dirección Exacta</label>
              <input
                v-model="form.address"
                type="text"
                placeholder="Ej: Calle San Martín 450"
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>

            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Descripción Completa</label>
              <textarea
                v-model="form.description"
                rows="4"
                required
                placeholder="Detallá los espacios, vistas, tranquilidad y comodidades..."
                class="w-full px-3.5 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- 2. Capacidad y Precios -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
          <h3 class="text-base font-bold text-surface-900">Capacidad y Precios</h3>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Precio (ARS)</label>
              <input
                v-model.number="form.price"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Modalidad</label>
              <select
                v-model="form.price_type"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              >
                <option value="/noche">Por noche</option>
                <option value="por día">Por día (quinta)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Capacidad Max.</label>
              <input
                v-model.number="form.capacity"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Dormitorios</label>
              <input
                v-model.number="form.bedrooms"
                type="number"
                min="0"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>
          </div>
        </div>

        <!-- 3. Comodidades y Reglas -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
          <h3 class="text-base font-bold text-surface-900">Servicios y Reglas</h3>

          <!-- Amenities tag list -->
          <div>
            <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-2">Comodidades</label>
            <div class="flex gap-2 mb-3">
              <input
                v-model="newAmenity"
                type="text"
                placeholder="Ej: Piscina, Aire Acondicionado..."
                class="flex-1 px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"
                @keyup.enter.prevent="addAmenity"
              />
              <button
                type="button"
                class="px-3 py-2 bg-brand-600 text-white rounded-xl text-xs font-semibold hover:bg-brand-700 cursor-pointer"
                @click="addAmenity"
              >
                <Plus class="w-4 h-4" />
              </button>
            </div>
            <div class="flex flex-wrap gap-2">
              <span
                v-for="(am, idx) in form.amenities"
                :key="am"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-brand-50 text-brand-800"
              >
                <span>{{ am }}</span>
                <button type="button" class="text-brand-400 hover:text-brand-800 cursor-pointer" @click="removeAmenity(idx)">×</button>
              </span>
            </div>
          </div>

          <!-- Rules list -->
          <div>
            <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-2">Reglas de Convivencia</label>
            <div class="flex gap-2 mb-3">
              <input
                v-model="newRule"
                type="text"
                placeholder="Ej: No se permite fumar en el interior..."
                class="flex-1 px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-brand-500"
                @keyup.enter.prevent="addRule"
              />
              <button
                type="button"
                class="px-3 py-2 bg-brand-600 text-white rounded-xl text-xs font-semibold hover:bg-brand-700 cursor-pointer"
                @click="addRule"
              >
                <Plus class="w-4 h-4" />
              </button>
            </div>
            <ul class="space-y-1.5 text-xs text-surface-600">
              <li v-for="(rule, idx) in form.rules" :key="idx" class="flex items-center justify-between p-2 rounded-lg bg-surface-50">
                <span>{{ rule }}</span>
                <button type="button" class="text-red-500 hover:text-red-700 cursor-pointer" @click="removeRule(idx)">
                  <Trash2 class="w-3.5 h-3.5" />
                </button>
              </li>
            </ul>
          </div>
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full py-3.5 px-4 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white font-bold rounded-2xl shadow-lg transition flex items-center justify-center gap-2 cursor-pointer"
        >
          <Loader2 v-if="isLoading" class="w-5 h-5 animate-spin" />
          <span v-else>Guardar Alojamiento (Borrador)</span>
        </button>
      </form>
    </div>
  </div>
</template>
