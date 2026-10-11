<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

// Pestaña actual: register vs login (sincronizada con la ruta inicial)
const activeTab = ref<'register' | 'login'>(
  route.path.includes('login') ? 'login' : 'register'
)

// Rol seleccionado: owner (propietario) vs renter (inquilino)
const selectedRole = ref<'owner' | 'renter'>(
  route.query.role === 'renter' ? 'renter' : 'owner'
)

// Campos del formulario (autocompletado inicial según rol si está en login)
const form = ref({
  fullname: '',
  email: activeTab.value === 'login'
    ? (selectedRole.value === 'owner' ? 'anfitrion@turismoseguro.com.ar' : 'carlos.sanjuanino@gmail.com')
    : '',
  phone: '',
  password: activeTab.value === 'login' ? 'password123' : '',
  acceptTerms: true,
})

// Cambiar de rol y autocompletar inmediatamente en Login
const selectRole = (role: 'owner' | 'renter') => {
  selectedRole.value = role
  if (activeTab.value === 'login') {
    if (role === 'owner') {
      form.value.email = 'anfitrion@turismoseguro.com.ar'
      form.value.password = 'password123'
    } else {
      form.value.email = 'carlos.sanjuanino@gmail.com'
      form.value.password = 'password123'
    }
  }
}

// Al alternar entre Crear Cuenta e Iniciar Sesión
const switchTab = (tab: 'register' | 'login') => {
  activeTab.value = tab
  if (tab === 'login') {
    if (selectedRole.value === 'owner') {
      form.value.email = 'anfitrion@turismoseguro.com.ar'
      form.value.password = 'password123'
    } else {
      form.value.email = 'carlos.sanjuanino@gmail.com'
      form.value.password = 'password123'
    }
  } else {
    form.value.fullname = ''
    form.value.email = ''
    form.value.phone = ''
    form.value.password = ''
  }
}

const isSubmitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const showForgotPassword = ref(false)
const forgotPasswordEmail = ref('')
const forgotPasswordSent = ref(false)

const handleSendResetEmail = async () => {
  if (!forgotPasswordEmail.value) return
  try {
    await authStore.forgotPassword({ email: forgotPasswordEmail.value })
    forgotPasswordSent.value = true
  } catch {
    forgotPasswordSent.value = true
  }
}

// Credenciales demo para agilizar pruebas
const fillDemoCredentials = () => {
  if (selectedRole.value === 'owner') {
    form.value.email = 'anfitrion@turismoseguro.com.ar'
    form.value.password = 'password123'
  } else {
    form.value.email = 'carlos.sanjuanino@gmail.com'
    form.value.password = 'password123'
  }
}

const submitButtonLabel = computed(() => {
  if (isSubmitting.value) return 'Procesando solicitud...'
  if (activeTab.value === 'register') {
    return selectedRole.value === 'owner'
      ? 'Solicitar Auditoría y Registrarme como Propietario'
      : 'Registrarme como Inquilino'
  } else {
    return selectedRole.value === 'owner'
      ? 'Ingresar al Panel de Propietario'
      : 'Ingresar como Inquilino'
  }
})

const handleFormSubmit = async () => {
  isSubmitting.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    if (activeTab.value === 'login') {
      const res = await authStore.login({
        email: form.value.email,
        password: form.value.password,
      })

      successMessage.value = res.user.role === 'client'
        ? `¡Bienvenido ${res.user.name}! Ingresando al panel de propietario...`
        : `¡Bienvenido ${res.user.name}! Ingresando como inquilino...`

      setTimeout(() => {
        if (res.user.role === 'client') {
          router.push('/propietario/panel')
        } else if (res.user.role === 'rent') {
          router.push('/inquilino/panel')
        } else {
          router.push('/')
        }
      }, 700)
    } else {
      // Registro
      if (selectedRole.value === 'owner') {
        const res = await authStore.registerClient({
          name: form.value.fullname,
          email: form.value.email,
          password: form.value.password,
          password_confirmation: form.value.password,
          phone: form.value.phone,
        })
        successMessage.value = `¡Cuenta creada para ${res.user.name}! Tu solicitud para los 6 meses bonificados fue registrada.`
        setTimeout(() => {
          router.push('/propietario/panel')
        }, 800)
      } else {
        const res = await authStore.registerRent({
          name: form.value.fullname,
          email: form.value.email,
          password: form.value.password,
          password_confirmation: form.value.password,
          phone: form.value.phone,
          is_local_resident: true,
          origin_province: 'San Juan',
          origin_city: 'Capital',
        })
        successMessage.value = `¡Bienvenido ${res.user.name}! Cuenta creada exitosamente.`
        setTimeout(() => {
          router.push('/inquilino/panel')
        }, 800)
      }
    }
  } catch (err: any) {
    errorMessage.value = err.response?.data?.message || authStore.errorMessage || 'Hubo un error al procesar tu solicitud.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <main class="w-full bg-background py-space-lg lg:py-space-xl min-h-screen relative overflow-hidden flex flex-col justify-center">
    <!-- Subtle Ambient Glow Canvas -->
    <div class="absolute -top-32 -left-20 w-96 h-96 rounded-full bg-secondary-fixed/30 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-24 w-[32rem] h-[32rem] rounded-full bg-primary-fixed/40 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/3 w-80 h-80 rounded-full bg-tertiary-fixed/30 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 w-full">
      <!-- Logo Oficial de Turismo Seguro -->
      <div class="text-center mb-6">
        <RouterLink to="/" class="inline-flex items-center gap-2.5 group" title="Volver al inicio">
          <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
            <span class="material-symbols-outlined text-[24px] text-secondary">verified_user</span>
          </div>
          <div class="text-left">
            <span class="font-headline-sm text-headline-sm text-primary font-extrabold block leading-none">Turismo Seguro</span>
            <span class="text-secondary font-label-sm text-label-sm font-bold tracking-wider uppercase text-[10px]">San Juan Verificado</span>
          </div>
        </RouterLink>
      </div>

      <!-- Main Centered Layout -->
      <div class="max-w-xl mx-auto flex flex-col gap-space-md w-full">
        <!-- BANNER PROMO LANZAMIENTO (SOLO PARA PROPIETARIOS) -->
        <div
          v-if="selectedRole === 'owner'"
          class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-tertiary-container via-primary-container to-primary text-on-primary p-space-md lg:p-space-lg shadow-md transition-all duration-300"
        >
          <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <span class="material-symbols-outlined text-[180px]">card_giftcard</span>
          </div>
          <div class="relative z-10 flex items-start gap-space-md">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-tertiary-fixed text-on-tertiary-fixed flex items-center justify-center shadow-sm">
              <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1;">stars</span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <span class="inline-block bg-tertiary-fixed/20 text-tertiary-fixed font-label-sm text-label-sm px-2.5 py-0.5 rounded-full font-bold">
                  BENEFICIO PARA PROPIETARIOS
                </span>
                <span class="font-label-sm text-label-sm text-surface-tint font-bold">Faltan 14/50 cupos</span>
              </div>
              <h2 class="font-headline-sm text-headline-sm text-on-primary leading-snug font-bold">
                Primeras 50 quintas y cabañas bonificadas
              </h2>
              <p class="font-body-sm text-body-sm text-primary-fixed mt-1 font-medium">
                6 meses de suscripción 100% gratis con <strong>inspección técnica presencial in situ</strong> y certificado oficial con QR anti-estafas.
              </p>
              <div class="w-full bg-surface-container-highest/20 rounded-full h-1.5 mt-3 overflow-hidden">
                <div class="bg-secondary-fixed h-full rounded-full transition-all duration-700" style="width: 72%;"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- CARD FORMULARIO PRINCIPAL CENTRADO -->
        <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 lg:p-10 shadow-lg border border-surface-container">
          <!-- 1. Selector de Acción: Login vs Registro -->
          <div class="flex p-1 bg-surface-container-low rounded-xl mb-space-md">
            <button
              type="button"
              :class="[
                'flex-1 py-2.5 px-4 rounded-lg font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 font-bold cursor-pointer',
                activeTab === 'register' ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
              ]"
              @click="switchTab('register')"
            >
              <span class="material-symbols-outlined text-[18px]">person_add</span>
              <span>Crear Cuenta</span>
            </button>
            <button
              type="button"
              :class="[
                'flex-1 py-2.5 px-4 rounded-lg font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 font-bold cursor-pointer',
                activeTab === 'login' ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
              ]"
              @click="switchTab('login')"
            >
              <span class="material-symbols-outlined text-[18px]">login</span>
              <span>Iniciar Sesión</span>
            </button>
          </div>

          <!-- 2. SELECTOR DE ROL: PROPIETARIO vs INQUILINO (ACTIVO EN LOGIN Y REGISTRO) -->
          <div class="mb-space-md p-3.5 bg-surface-container-low rounded-xl border border-surface-container">
            <div class="flex items-center justify-between mb-2">
              <span class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider font-bold">
                {{ activeTab === 'login' ? '¿Cómo deseas ingresar?' : '¿Cómo deseas registrarte?' }}
              </span>
              <span class="font-label-sm text-label-sm text-secondary font-bold">
                {{ selectedRole === 'owner' ? 'Perfil Propietario' : 'Perfil Inquilino' }}
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <!-- Opción: Propietario -->
              <button
                type="button"
                :class="[
                  'flex items-start gap-3 p-3.5 rounded-xl text-left transition-all cursor-pointer border',
                  selectedRole === 'owner'
                    ? 'bg-surface-container-lowest border-secondary ring-2 ring-secondary/40 shadow-xs'
                    : 'bg-surface-container-lowest/60 border-surface-container hover:bg-surface-container-lowest'
                ]"
                @click="selectRole('owner')"
              >
                <div
                  :class="[
                    'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors',
                    selectedRole === 'owner' ? 'bg-secondary text-on-secondary' : 'bg-surface-container-high text-on-surface'
                  ]"
                >
                  <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">villa</span>
                </div>
                <div>
                  <div class="font-label-lg text-label-lg text-on-surface flex items-center gap-1.5 font-bold">
                    Soy Propietario
                    <span v-if="selectedRole === 'owner'" class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                  </div>
                  <p class="font-body-sm text-body-sm text-on-surface-variant leading-tight mt-0.5">
                    {{ activeTab === 'login' ? 'Administrar mis quintas y reservas' : 'Quiero publicar mi quinta o cabaña' }}
                  </p>
                </div>
              </button>

              <!-- Opción: Inquilino -->
              <button
                type="button"
                :class="[
                  'flex items-start gap-3 p-3.5 rounded-xl text-left transition-all cursor-pointer border',
                  selectedRole === 'renter'
                    ? 'bg-surface-container-lowest border-primary ring-2 ring-primary/40 shadow-xs'
                    : 'bg-surface-container-lowest/60 border-surface-container hover:bg-surface-container-lowest'
                ]"
                @click="selectRole('renter')"
              >
                <div
                  :class="[
                    'w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors',
                    selectedRole === 'renter' ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface'
                  ]"
                >
                  <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">person</span>
                </div>
                <div>
                  <div class="font-label-lg text-label-lg text-on-surface flex items-center gap-1.5 font-bold">
                    Soy Inquilino
                    <span v-if="selectedRole === 'renter'" class="material-symbols-outlined text-primary text-[16px]">check_circle</span>
                  </div>
                  <p class="font-body-sm text-body-sm text-on-surface-variant leading-tight mt-0.5">
                    {{ activeTab === 'login' ? 'Acceder a mis favoritos y consultas' : 'Busco alquilar para pasar el día' }}
                  </p>
                </div>
              </button>
            </div>
          </div>

          <!-- Botón de Acceso Directo con Google -->
          <div class="mb-space-md">
            <button
              type="button"
              class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg shadow-sm transition-all group font-bold cursor-pointer border border-surface-container"
              @click="handleFormSubmit"
            >
              <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                <path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z" fill="#4285F4"></path>
                <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z" fill="#34A853"></path>
                <path d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.04 0 12s.45 3.82 1.25 5.42l4.03-3.15z" fill="#FBBC05"></path>
                <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z" fill="#EA4335"></path>
              </svg>
              <span>Continuar con Google como {{ selectedRole === 'owner' ? 'Propietario' : 'Inquilino' }}</span>
            </button>
          </div>

          <!-- Separador Visual -->
          <div class="relative flex items-center justify-center my-6">
            <div class="w-full h-px bg-surface-container-highest"></div>
            <span class="absolute bg-surface-container-lowest px-4 font-label-sm text-label-sm text-outline uppercase tracking-wider font-semibold">
              o continúa con tu correo electrónico
            </span>
          </div>

          <!-- Formulario Principal -->
          <form class="space-y-4" @submit.prevent="handleFormSubmit">
            <!-- Campo: Nombre Completo (Registro) -->
            <div v-if="activeTab === 'register'" class="flex flex-col gap-1.5">
              <label class="font-label-lg text-label-lg text-on-surface font-bold">Nombre y Apellido</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                  <span class="material-symbols-outlined text-[20px]">badge</span>
                </div>
                <input
                  v-model="form.fullname"
                  type="text"
                  required
                  placeholder="Ej: Marcos Agustín Gioja"
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                />
              </div>
            </div>

            <!-- Campo: Correo Electrónico -->
            <div class="flex flex-col gap-1.5">
              <label class="font-label-lg text-label-lg text-on-surface font-bold">Correo Electrónico</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                  <span class="material-symbols-outlined text-[20px]">mail</span>
                </div>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  placeholder="tuemail@ejemplo.com"
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                />
              </div>
            </div>

            <!-- Campo: WhatsApp (Registro) -->
            <div v-if="activeTab === 'register'" class="flex flex-col gap-1.5">
              <label class="font-label-lg text-label-lg text-on-surface font-bold">
                {{ selectedRole === 'owner' ? 'WhatsApp Oficial de la Quinta/Cabaña' : 'WhatsApp de Contacto' }}
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                  <span class="material-symbols-outlined text-[20px]">phone_android</span>
                </div>
                <input
                  v-model="form.phone"
                  type="tel"
                  required
                  placeholder="Ej: 264 411 2233"
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                />
              </div>
            </div>

            <!-- Campo: Contraseña -->
            <div class="flex flex-col gap-1.5">
              <div class="flex items-center justify-between">
                <label class="font-label-lg text-label-lg text-on-surface font-bold">Contraseña</label>
                <button
                  v-if="activeTab === 'login'"
                  type="button"
                  class="font-label-sm text-label-sm text-secondary hover:text-primary transition-colors font-bold cursor-pointer"
                  @click="showForgotPassword = true"
                >
                  ¿Olvidaste tu contraseña?
                </button>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                  <span class="material-symbols-outlined text-[20px]">lock</span>
                </div>
                <input
                  v-model="form.password"
                  type="password"
                  required
                  placeholder="Mínimo 8 caracteres"
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                />
              </div>
            </div>

            <!-- Mensaje de éxito -->
            <div v-if="successMessage" class="p-3 bg-secondary-fixed/40 text-on-secondary-fixed rounded-xl font-label-md text-label-md flex items-center gap-2 font-bold">
              <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
              <span>{{ successMessage }}</span>
            </div>

            <!-- Mensaje de error -->
            <div v-if="errorMessage" class="p-3 bg-error-container text-on-error-container rounded-xl font-label-md text-label-md flex items-center gap-2 font-bold">
              <span class="material-symbols-outlined text-[20px]">error</span>
              <span>{{ errorMessage }}</span>
            </div>

            <!-- Atajo Demo para pruebas rápidas -->
            <div v-if="activeTab === 'login'" class="flex items-center justify-between text-xs pt-1">
              <span class="text-on-surface-variant">¿Probando la plataforma?</span>
              <button
                type="button"
                class="text-secondary font-bold hover:underline cursor-pointer flex items-center gap-1"
                @click="fillDemoCredentials"
              >
                <span class="material-symbols-outlined text-[14px]">auto_fix_high</span>
                <span>Completar credenciales de {{ selectedRole === 'owner' ? 'Propietario' : 'Inquilino' }}</span>
              </button>
            </div>

            <!-- Botón Submit Dinámico con el Rol -->
            <button
              type="submit"
              :disabled="isSubmitting"
              class="w-full py-3.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-md font-bold cursor-pointer disabled:opacity-50"
            >
              <span>{{ submitButtonLabel }}</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- MODAL RECUPERACIÓN DE CONTRASEÑA -->
    <div
      v-if="showForgotPassword"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
    >
      <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-surface-container space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[24px]">lock_reset</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Recuperar Contraseña</h3>
          </div>
          <button
            type="button"
            class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer"
            @click="showForgotPassword = false; forgotPasswordSent = false"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <p class="font-body-sm text-body-sm text-on-surface-variant">
          Ingresá el correo electrónico registrado con tu cuenta de {{ selectedRole === 'owner' ? 'propietario' : 'inquilino' }}. Te enviaremos un enlace seguro para restablecer tu clave.
        </p>

        <div v-if="!forgotPasswordSent" class="space-y-4">
          <div class="flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-bold">Correo Electrónico</label>
            <input
              v-model="forgotPasswordEmail"
              type="email"
              required
              placeholder="tuemail@ejemplo.com"
              class="w-full px-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary border border-surface-container"
            />
          </div>

          <div class="flex gap-2 pt-2">
            <button
              type="button"
              class="flex-1 py-2.5 px-4 rounded-xl bg-surface-container text-on-surface font-label-md text-label-md hover:bg-surface-dim transition-colors font-bold cursor-pointer"
              @click="showForgotPassword = false"
            >
              Cancelar
            </button>
            <button
              type="button"
              class="flex-1 py-2.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-container transition-all font-bold cursor-pointer shadow-sm"
              @click="handleSendResetEmail"
            >
              Enviar Enlace
            </button>
          </div>
        </div>

        <div v-else class="space-y-3 py-2 text-center">
          <div class="w-12 h-12 rounded-full bg-secondary-fixed/50 text-secondary mx-auto flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">mark_email_read</span>
          </div>
          <h4 class="font-label-lg text-label-lg text-primary font-bold">¡Enlace enviado con éxito!</h4>
          <p class="font-body-sm text-body-sm text-on-surface-variant">
            Revisá tu bandeja de entrada en <strong>{{ forgotPasswordEmail }}</strong> para continuar con el restablecimiento.
          </p>
          <button
            type="button"
            class="w-full py-2.5 px-4 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold mt-2 cursor-pointer"
            @click="showForgotPassword = false; forgotPasswordSent = false"
          >
            Volver al Inicio de Sesión
          </button>
        </div>
      </div>
    </div>
  </main>
</template>
