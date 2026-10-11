<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

// Rol interno seleccionado
const selectedStaffRole = ref<'validator' | 'supervisor' | 'admin' | 'super_admin'>('validator')

const form = ref({
  email: 'validador@turismoseguro.com.ar',
  password: 'password123',
  pin2fa: '',
})

const isSubmitting = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

// Accesos rápidos de desarrollo / prueba
const setDemoCredentials = (role: 'validator' | 'supervisor' | 'admin' | 'super_admin') => {
  selectedStaffRole.value = role
  if (role === 'validator') {
    form.value.email = 'validador@turismoseguro.com.ar'
  } else if (role === 'supervisor') {
    form.value.email = 'supervisor@turismoseguro.com.ar'
  } else if (role === 'admin') {
    form.value.email = 'administracion@turismoseguro.com.ar'
  } else {
    form.value.email = 'admin@turismoseguro.com.ar'
  }
  form.value.password = 'password123'
}

const handleLogin = async () => {
  isSubmitting.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const res = await authStore.login({
      email: form.value.email,
      password: form.value.password,
    })

    const roleLabels: Record<string, string> = {
      validator: 'Auditor de Terreno',
      supervisor: 'Supervisor de Calidad',
      admin: 'Administración Provincial / Pagos',
      super_admin: 'Super Administrador Global / TI',
    }

    const currentRole = res.user.role || selectedStaffRole.value
    successMessage.value = `¡Identidad confirmada! Accediendo como ${roleLabels[currentRole] || 'Staff'}...`

    setTimeout(() => {
      if (currentRole === 'validator') {
        router.push('/validador/bandeja')
      } else {
        router.push('/')
      }
    }, 700)
  } catch (err: any) {
    errorMessage.value = err.response?.data?.message || authStore.errorMessage || 'Credenciales inválidas para acceso interno.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <main class="w-full bg-surface min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 relative overflow-hidden">
    <!-- Ambient Shield Glow -->
    <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-secondary-fixed/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-primary-fixed/20 blur-3xl pointer-events-none"></div>

    <div class="max-w-lg w-full relative z-10 flex flex-col gap-6">
      <!-- Header de Marca Institucional -->
      <div class="text-center">
        <RouterLink to="/" class="inline-flex items-center gap-2.5 group mb-3" title="Volver al Portal Público">
          <div class="w-12 h-12 rounded-xl bg-primary text-on-primary flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
            <span class="material-symbols-outlined text-[28px] text-secondary">shield</span>
          </div>
          <div class="text-left">
            <span class="font-headline-sm text-headline-sm text-primary font-extrabold block leading-none">Turismo Seguro</span>
            <span class="text-secondary font-label-sm text-label-sm font-bold tracking-wider uppercase text-[10px]">Portal de Operaciones &amp; Auditoría</span>
          </div>
        </RouterLink>

        <h1 class="font-headline-md text-headline-md text-primary font-bold tracking-tight">
          Acceso Staff &amp; Inspectores
        </h1>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">
          Uso exclusivo para validadores matriculados, supervisores y administración central/regional.
        </p>
      </div>

      <!-- Selector de Rol de Staff -->
      <div class="bg-surface-container-low p-1.5 rounded-xl border border-surface-container grid grid-cols-2 sm:grid-cols-4 gap-1">
        <button
          type="button"
          :class="[
            'py-2 px-2 rounded-lg font-label-sm text-label-sm font-bold transition-all flex items-center justify-center gap-1 cursor-pointer text-xs',
            selectedStaffRole === 'validator' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
          ]"
          @click="setDemoCredentials('validator')"
        >
          <span class="material-symbols-outlined text-[15px]">engineering</span>
          <span>Validador</span>
        </button>

        <button
          type="button"
          :class="[
            'py-2 px-2 rounded-lg font-label-sm text-label-sm font-bold transition-all flex items-center justify-center gap-1 cursor-pointer text-xs',
            selectedStaffRole === 'supervisor' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
          ]"
          @click="setDemoCredentials('supervisor')"
        >
          <span class="material-symbols-outlined text-[15px]">verified</span>
          <span>Supervisor</span>
        </button>

        <button
          type="button"
          :class="[
            'py-2 px-2 rounded-lg font-label-sm text-label-sm font-bold transition-all flex items-center justify-center gap-1 cursor-pointer text-xs',
            selectedStaffRole === 'admin' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
          ]"
          @click="setDemoCredentials('admin')"
        >
          <span class="material-symbols-outlined text-[15px]">payments</span>
          <span>Admin</span>
        </button>

        <button
          type="button"
          :class="[
            'py-2 px-2 rounded-lg font-label-sm text-label-sm font-bold transition-all flex items-center justify-center gap-1 cursor-pointer text-xs',
            selectedStaffRole === 'super_admin' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
          ]"
          @click="setDemoCredentials('super_admin')"
        >
          <span class="material-symbols-outlined text-[15px]">admin_panel_settings</span>
          <span>Super Admin</span>
        </button>
      </div>

      <!-- Card de Login -->
      <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 shadow-xl border border-surface-container">
        <form class="space-y-4" @submit.prevent="handleLogin">
          <!-- Email Institucional -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-bold flex items-center justify-between">
              <span>Legajo / Correo Oficial</span>
              <span class="font-mono text-[11px] text-secondary font-semibold">@turismoseguro.com.ar</span>
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                <span class="material-symbols-outlined text-[20px]">badge</span>
              </div>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container"
              />
            </div>
          </div>

          <!-- Contraseña -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-bold">Contraseña de Seguridad</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                <span class="material-symbols-outlined text-[20px]">key</span>
              </div>
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container"
              />
            </div>
          </div>

          <!-- PIN de Auditor / Token 2FA Opcional -->
          <div class="flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
              <label class="font-label-md text-label-md text-on-surface font-bold">PIN de Seguridad Terreno</label>
              <span class="text-[11px] text-on-surface-variant">Opcional para inspección GPS</span>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                <span class="material-symbols-outlined text-[20px]">pin</span>
              </div>
              <input
                v-model="form.pin2fa"
                type="text"
                maxlength="6"
                placeholder="6 dígitos (ej: 044812)"
                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container font-mono"
              />
            </div>
          </div>

          <!-- Mensajes de feedback -->
          <div v-if="successMessage" class="p-3 bg-secondary-fixed/40 text-on-secondary-fixed rounded-xl font-label-md text-label-md flex items-center gap-2 font-bold">
            <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
            <span>{{ successMessage }}</span>
          </div>

          <div v-if="errorMessage" class="p-3 bg-error-container text-on-error-container rounded-xl font-label-md text-label-md flex items-center gap-2 font-bold">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Botón de Acceso -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full py-3.5 px-4 rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-all shadow-md font-bold cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2"
          >
            <span class="material-symbols-outlined text-[20px]">lock_open</span>
            <span v-if="!isSubmitting">
              Ingresar como {{
                selectedStaffRole === 'validator' ? 'Validador Matriculado' :
                selectedStaffRole === 'supervisor' ? 'Supervisor de Calidad' :
                selectedStaffRole === 'admin' ? 'Administrador Provincial' : 'Super Administrador'
              }}
            </span>
            <span v-else>Verificando credenciales...</span>
          </button>
        </form>

        <!-- Certificado de Encriptación -->
        <div class="mt-6 pt-4 border-t border-surface-container flex items-center justify-between text-xs text-on-surface-variant font-medium">
          <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-secondary text-[16px]">verified_user</span>
            Conexión Encriptada TLS 1.3
          </span>
          <span class="font-mono text-[11px]">Nodo San Juan #SJ-01</span>
        </div>
      </div>

      <!-- Enlace para volver al sitio público -->
      <div class="text-center">
        <RouterLink
          to="/"
          class="inline-flex items-center gap-1.5 text-on-surface-variant hover:text-primary font-label-md text-label-md font-bold transition-colors"
        >
          <span class="material-symbols-outlined text-[18px]">arrow_back</span>
          <span>Volver al Portal Público de Alquileres</span>
        </RouterLink>
      </div>
    </div>
  </main>
</template>
