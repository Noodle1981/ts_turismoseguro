<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { User, Mail, Lock, Phone, AlertCircle, Loader2, ArrowRight, ShieldCheck, Building2 } from 'lucide-vue-next'

const router = useRouter()
const { registerClient, isLoading, errorMessage } = useAuth()

const form = ref({
  name: '',
  email: '',
  phone: '',
  password: '',
  password_confirmation: '',
})

const formError = ref<string | null>(null)

const handleSubmit = async () => {
  formError.value = null
  if (!form.value.phone.trim()) {
    formError.value = 'El teléfono de contacto es obligatorio para propietarios.'
    return
  }

  try {
    await registerClient({
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
    })

    router.push('/dashboard')
  } catch (err: any) {
    formError.value = err.response?.data?.message || errorMessage.value || 'Error al completar el registro.'
  }
}
</script>

<template>
  <div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600 mb-1">
          <Building2 class="w-6 h-6" />
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Registrarme como Anfitrión
        </h2>
        <p class="text-sm text-surface-500">
          Publicá tus alojamientos o experiencias con verificación oficial de San Juan
        </p>
      </div>

      <!-- Beneficios del anfitrión certificado -->
      <div class="p-4 bg-emerald-50/70 border border-emerald-100 rounded-xl space-y-2 text-xs text-emerald-800">
        <div class="flex items-center gap-2 font-semibold text-emerald-900">
          <ShieldCheck class="w-4 h-4 text-safe-600 flex-shrink-0" />
          <span>Sello de Verificación y Respaldo Oficial</span>
        </div>
        <p>
          Tus publicaciones serán auditadas por inspectores acreditados para otorgarte el distintivo QR de Turismo Seguro.
        </p>
      </div>

      <div v-if="formError" role="alert" class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ formError }}</span>
      </div>

      <form class="space-y-4" @submit.prevent="handleSubmit">
        <!-- Nombre -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Nombre Completo / Razón Social</label>
          <div class="relative">
            <User class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Juan Pérez o Cabañas El Portezuelo"
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
              placeholder="anfitrion@ejemplo.com"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Teléfono (Obligatorio para Client) -->
        <div>
          <div class="flex justify-between items-center mb-1">
            <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider">Teléfono de Contacto (WhatsApp)</label>
            <span class="text-[11px] text-brand-600 font-medium">Requerido</span>
          </div>
          <div class="relative">
            <Phone class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.phone"
              type="tel"
              required
              placeholder="+54 9 264 1234567"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
          <p class="text-[11px] text-surface-500 mt-1">
            Será utilizado por los validadores acreditados para coordinar la inspección in situ o remota.
          </p>
        </div>

        <!-- Contraseñas -->
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
                placeholder="••••••••"
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
                placeholder="••••••••"
                class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              />
            </div>
          </div>
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full mt-2 py-3 px-4 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/20 transition duration-150 flex items-center justify-center gap-2 group cursor-pointer"
        >
          <Loader2 v-if="isLoading" class="w-5 h-5 animate-spin" />
          <span v-else>Comenzar a Publicar</span>
          <ArrowRight v-if="!isLoading" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
        </button>
      </form>

      <div class="text-center text-xs text-surface-500 space-y-2 pt-2 border-t border-slate-100">
        <p>
          ¿Buscás alquilar en vez de publicar?
          <RouterLink to="/register-rent" class="font-semibold text-brand-600 hover:text-brand-700">
            Registrate como huésped
          </RouterLink>
        </p>
        <p>
          ¿Ya tenés cuenta?
          <RouterLink to="/login" class="font-semibold text-brand-600 hover:text-brand-700">
            Iniciá sesión aquí
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>
