<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { Mail, Lock, AlertCircle, CheckCircle, Loader2, KeyRound } from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const { resetPassword, isLoading, errorMessage } = useAuth()

const form = ref({
  token: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const successMessage = ref<string | null>(null)
const formError = ref<string | null>(null)

onMounted(() => {
  if (route.query.token) {
    form.value.token = String(route.query.token)
  }
  if (route.query.email) {
    form.value.email = String(route.query.email)
  }
})

const handleSubmit = async () => {
  formError.value = null
  successMessage.value = null

  if (!form.value.token) {
    formError.value = 'El token de restablecimiento es requerido. Asegurate de ingresar mediante el enlace que recibiste por correo.'
    return
  }

  try {
    const res = await resetPassword({
      token: form.value.token,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation,
    })

    successMessage.value = res.message || 'Contraseña restablecida exitosamente. Redirigiendo al inicio de sesión...'
    setTimeout(() => {
      router.push('/login')
    }, 2500)
  } catch (err: any) {
    formError.value = err.response?.data?.message || errorMessage.value || 'Error al restablecer la contraseña.'
  }
}
</script>

<template>
  <div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600 mb-1">
          <KeyRound class="w-6 h-6" />
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Nueva Contraseña
        </h2>
        <p class="text-sm text-surface-500">
          Definí tu nueva credencial de acceso
        </p>
      </div>

      <div v-if="successMessage" role="alert" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-start gap-2">
        <CheckCircle class="w-5 h-5 flex-shrink-0 text-safe-600 mt-0.5" />
        <span>{{ successMessage }}</span>
      </div>

      <div v-if="formError" role="alert" class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ formError }}</span>
      </div>

      <form v-if="!successMessage" class="space-y-4" @submit.prevent="handleSubmit">
        <!-- Token -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Token de Validación</label>
          <input
            v-model="form.token"
            type="text"
            required
            placeholder="Token recibido"
            class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
          />
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
              placeholder="tu@correo.com"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Nueva Contraseña -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Nueva Contraseña</label>
          <div class="relative">
            <Lock class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.password"
              type="password"
              required
              minlength="8"
              placeholder="Mínimo 8 caracteres"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <!-- Confirmar Contraseña -->
        <div>
          <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Confirmar Contraseña</label>
          <div class="relative">
            <Lock class="w-4 h-4 text-surface-400 absolute left-3 top-3" />
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              minlength="8"
              placeholder="Repetí tu nueva contraseña"
              class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
            />
          </div>
        </div>

        <button
          type="submit"
          :disabled="isLoading"
          class="w-full mt-2 py-3 px-4 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 text-white font-semibold rounded-xl shadow-lg shadow-brand-500/20 transition duration-150 flex items-center justify-center gap-2 cursor-pointer"
        >
          <Loader2 v-if="isLoading" class="w-5 h-5 animate-spin" />
          <span v-else>Guardar Nueva Contraseña</span>
        </button>
      </form>

      <div class="text-center pt-2 border-t border-slate-100">
        <RouterLink to="/login" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
          Volver a Iniciar Sesión
        </RouterLink>
      </div>
    </div>
  </div>
</template>
