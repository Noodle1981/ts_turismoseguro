<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { User, Mail, Lock, Phone, MapPin, AlertCircle, Loader2, ArrowRight } from 'lucide-vue-next'

const router = useRouter()
const { registerRent, isLoading, errorMessage } = useAuth()

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
  is_local_resident: false,
  origin_country: 'Argentina',
  origin_province: '',
  origin_city: '',
})

const sanJuanDepartments = [
  'Capital', 'Rawson', 'Rivadavia', 'Chimbas', 'Santa Lucía', 'Pocito',
  'Caucete', 'Albardón', 'Sarmiento', 'Jáchal', '25 de Mayo', 'San Martín',
  'Calingasta', 'Valle Fértil', 'Iglesia', 'Angaco', '9 de Julio', 'Ullum', 'Zonda',
]

const formError = ref<string | null>(null)

const handleLocalChange = () => {
  if (form.value.is_local_resident) {
    form.value.origin_country = 'Argentina'
    form.value.origin_province = 'San Juan'
    form.value.origin_city = 'Capital'
  } else {
    form.value.origin_province = ''
    form.value.origin_city = ''
  }
}

const handleSubmit = async () => {
  formError.value = null
  try {
    await registerRent({
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone || undefined,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
      is_local_resident: form.value.is_local_resident,
      origin_country: form.value.origin_country || undefined,
      origin_province: form.value.origin_province || undefined,
      origin_city: form.value.origin_city || undefined,
    })

    router.push('/')
  } catch (err: any) {
    formError.value = err.response?.data?.message || errorMessage.value || 'Error al completar el registro.'
  }
}
</script>

<template>
  <div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <div class="text-center space-y-2">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Crear Cuenta de Huésped
        </h2>
        <p class="text-sm text-surface-500">
          Explorá y alquilá con el respaldo de propiedades certificadas en San Juan
        </p>
      </div>

      <div v-if="formError" role="alert" class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ formError }}</span>
      </div>

      <form class="space-y-4" @submit.prevent="handleSubmit">
        <!-- Nombre -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Nombre Completo</label>
          <div class="relative">
            <User class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Juan Pérez"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Correo Electrónico</label>
          <div class="relative">
            <Mail class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="juan@correo.com"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Teléfono -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Teléfono / WhatsApp (Opcional)</label>
          <div class="relative">
            <Phone class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.phone"
              type="tel"
              placeholder="+54 9 264 123-4567"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Procedencia Geográfica -->
        <div class="p-4 bg-brand-50/60 rounded-xl border border-brand-100 space-y-3">
          <span class="block text-xs font-bold text-brand-900 uppercase tracking-wider flex items-center gap-1.5">
            <MapPin class="w-4 h-4 text-brand-600" />
            ¿De dónde nos visitás?
          </span>

          <div class="flex items-center gap-4 text-sm">
            <label class="flex items-center gap-2 cursor-pointer text-surface-800">
              <input
                v-model="form.is_local_resident"
                type="radio"
                :value="true"
                @change="handleLocalChange"
                class="text-brand-600 focus:ring-brand-500"
              />
              <span>Vivo en San Juan</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer text-surface-800">
              <input
                v-model="form.is_local_resident"
                type="radio"
                :value="false"
                @change="handleLocalChange"
                class="text-brand-600 focus:ring-brand-500"
              />
              <span>Soy Turista / Otra Provincia</span>
            </label>
          </div>

          <!-- Si es residente local -->
          <div v-if="form.is_local_resident" class="space-y-1">
            <label class="block text-xs font-medium text-surface-600">Departamento donde vivís</label>
            <select
              v-model="form.origin_city"
              class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
            >
              <option v-for="dept in sanJuanDepartments" :key="dept" :value="dept">{{ dept }}</option>
            </select>
          </div>

          <!-- Si es turista foráneo -->
          <div v-else class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-xs font-medium text-surface-600">Provincia / Estado</label>
              <input
                v-model="form.origin_province"
                type="text"
                placeholder="Ej: Buenos Aires, Córdoba"
                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>
            <div>
              <label class="block text-xs font-medium text-surface-600">Ciudad de origen</label>
              <input
                v-model="form.origin_city"
                type="text"
                placeholder="Ej: Rosario, La Plata"
                class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none"
              />
            </div>
          </div>
        </div>

        <!-- Contraseña y confirmación -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Contraseña</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
              <input
                v-model="form.password"
                type="password"
                required
                minlength="8"
                placeholder="Mínimo 8 caract."
                class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              />
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Confirmar</label>
            <div class="relative">
              <Lock class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                minlength="8"
                placeholder="Repetir clave"
                class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              />
            </div>
          </div>
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 shadow-md transition-all duration-200 disabled:opacity-50 cursor-pointer mt-6"
        >
          <Loader2 v-if="isLoading" class="w-5 h-5 animate-spin" />
          <span v-else>Crear Cuenta</span>
          <ArrowRight v-if="!isLoading" class="w-4 h-4" />
        </button>
      </form>

      <div class="border-t border-slate-100 pt-4 text-center text-xs text-surface-500">
        ¿Ya tenés una cuenta?
        <RouterLink to="/login" class="font-semibold text-brand-700 hover:underline">
          Iniciá sesión aquí
        </RouterLink>
      </div>
    </div>
  </div>
</template>
