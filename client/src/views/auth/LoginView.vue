<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { ShieldCheck, Mail, Lock, ArrowRight, AlertCircle, Loader2 } from 'lucide-vue-next'

const router = useRouter()
const { login, isLoading, errorMessage } = useAuth()

const email = ref('')
const password = ref('')
const formError = ref<string | null>(null)

const setDemoCredentials = (role: string) => {
  switch (role) {
    case 'admin':
      email.value = 'admin@turismoseguro.com.ar'
      password.value = 'password123'
      break
    case 'supervisor':
      email.value = 'supervisor@turismoseguro.com.ar'
      password.value = 'password123'
      break
    case 'validador':
      email.value = 'validador@turismoseguro.com.ar'
      password.value = 'password123'
      break
    case 'anfitrion':
      email.value = 'anfitrion@turismoseguro.com.ar'
      password.value = 'password123'
      break
    case 'turista':
      email.value = 'carlos.sanjuanino@gmail.com'
      password.value = 'password123'
      break
  }
}

const handleSubmit = async () => {
  formError.value = null
  try {
    const res = await login({
      email: email.value,
      password: password.value,
    })

    // Redirección según rol
    const role = res.user.role
    if (role === 'super_admin') {
      router.push('/panel/admin')
    } else if (role === 'supervisor') {
      router.push('/panel/supervisor')
    } else if (role === 'validator') {
      router.push('/panel/validador')
    } else if (role === 'client') {
      router.push('/panel/cliente')
    } else {
      router.push('/')
    }
  } catch (err: any) {
    formError.value = err.response?.data?.message || errorMessage.value || 'Error al iniciar sesión.'
  }
}
</script>

<template>
  <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-slate-200/80">
      <!-- Header -->
      <div class="text-center space-y-2">
        <div class="inline-flex p-3 rounded-2xl bg-brand-50 text-brand-700">
          <ShieldCheck class="w-8 h-8" />
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-surface-900 font-display">
          Iniciar Sesión
        </h2>
        <p class="text-sm text-surface-500">
          Accedé a tu cuenta en Turismo Seguro San Juan
        </p>
      </div>

      <!-- Quick demo login selector -->
      <div class="bg-surface-50 p-3 rounded-xl border border-slate-200 text-xs space-y-1.5">
        <span class="font-semibold text-surface-700 block">Autocompletar cuentas demo de prueba:</span>
        <div class="flex flex-wrap gap-1.5">
          <button type="button" @click="setDemoCredentials('turista')" class="px-2 py-1 rounded bg-white border border-slate-200 hover:bg-brand-50 hover:text-brand-700 font-medium cursor-pointer">
            Turista (Rent)
          </button>
          <button type="button" @click="setDemoCredentials('anfitrion')" class="px-2 py-1 rounded bg-white border border-slate-200 hover:bg-brand-50 hover:text-brand-700 font-medium cursor-pointer">
            Anfitrión (Client)
          </button>
          <button type="button" @click="setDemoCredentials('validador')" class="px-2 py-1 rounded bg-white border border-slate-200 hover:bg-brand-50 hover:text-brand-700 font-medium cursor-pointer">
            Validador
          </button>
          <button type="button" @click="setDemoCredentials('supervisor')" class="px-2 py-1 rounded bg-white border border-slate-200 hover:bg-brand-50 hover:text-brand-700 font-medium cursor-pointer">
            Supervisor
          </button>
          <button type="button" @click="setDemoCredentials('admin')" class="px-2 py-1 rounded bg-white border border-slate-200 hover:bg-brand-50 hover:text-brand-700 font-medium cursor-pointer">
            Admin
          </button>
        </div>
      </div>

      <!-- Error Alert -->
      <div v-if="formError" role="alert" class="p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl flex items-center gap-2">
        <AlertCircle class="w-5 h-5 flex-shrink-0" />
        <span>{{ formError }}</span>
      </div>

      <!-- Form -->
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">
              Correo Electrónico
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                <Mail class="w-5 h-5" />
              </div>
              <input
                id="email"
                v-model="email"
                type="email"
                required
                autocomplete="email"
                placeholder="tu@correo.com"
                class="w-full pl-10 pr-3 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white text-sm transition-all"
              />
            </div>
          </div>

          <div>
            <div class="flex items-center justify-between mb-1">
              <label for="password" class="block text-xs font-semibold text-surface-700 uppercase tracking-wider">
                Contraseña
              </label>
              <RouterLink to="/recuperar-password" class="text-xs text-brand-700 hover:underline">
                ¿Olvidaste tu contraseña?
              </RouterLink>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-surface-400">
                <Lock class="w-5 h-5" />
              </div>
              <input
                id="password"
                v-model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full pl-10 pr-3 py-2.5 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white text-sm transition-all"
              />
            </div>
          </div>
        </div>

        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-sm font-bold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 shadow-md transition-all duration-200 disabled:opacity-50 cursor-pointer"
          >
            <Loader2 v-if="isLoading" class="w-5 h-5 animate-spin" />
            <span v-else>Entrar a mi Cuenta</span>
            <ArrowRight v-if="!isLoading" class="w-4 h-4" />
          </button>
        </div>
      </form>

      <!-- Footer Links -->
      <div class="border-t border-slate-100 pt-6 text-center text-xs space-y-2 text-surface-500">
        <p>
          ¿Buscás alojamientos o actividades?
          <RouterLink to="/registro/alquilar" class="font-semibold text-brand-700 hover:underline">
            Crear cuenta de Huésped
          </RouterLink>
        </p>
        <p>
          ¿Sos dueño de una cabaña o quinta?
          <RouterLink to="/registro/anfitrion" class="font-semibold text-accent-600 hover:underline">
            Registrate como Anfitrión
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>
