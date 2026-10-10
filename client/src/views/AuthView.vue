<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Pestaña actual: register vs login
const activeTab = ref<'register' | 'login'>('register')

// Rol seleccionado: owner (propietario) vs renter (inquilino)
const selectedRole = ref<'owner' | 'renter'>('owner')

// Campos del formulario
const form = ref({
  fullname: '',
  email: '',
  phone: '',
  password: '',
  acceptTerms: true,
})

const isSubmitting = ref(false)
const successMessage = ref('')

const handleFormSubmit = () => {
  isSubmitting.value = true
  setTimeout(() => {
    isSubmitting.value = false
    if (activeTab.value === 'register') {
      successMessage.value = selectedRole.value === 'owner'
        ? '¡Cuenta creada! Tu solicitud para el beneficio de 6 meses gratis fue registrada.'
        : '¡Bienvenido a Turismo Seguro San Juan!'
    } else {
      successMessage.value = '¡Sesión iniciada con éxito!'
    }

    setTimeout(() => {
      router.push('/')
    }, 1500)
  }, 800)
}
</script>

<template>
  <main class="w-full bg-background py-space-lg lg:py-space-xl min-h-screen relative overflow-hidden">
    <!-- Subtle Ambient Glow Canvas -->
    <div class="absolute -top-32 -left-20 w-96 h-96 rounded-full bg-secondary-fixed/30 blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-24 w-[32rem] h-[32rem] rounded-full bg-primary-fixed/40 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/3 w-80 h-80 rounded-full bg-tertiary-fixed/30 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
      <!-- Breadcrumb / Header Status Meta -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-space-md">
        <div class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
          <span class="material-symbols-outlined text-secondary text-[18px]" style="font-variation-settings: 'FILL' 1;">
            shield_with_house
          </span>
          <span>Portal Oficial de Autenticación</span>
          <span class="text-outline-variant">•</span>
          <span class="text-secondary font-label-md text-label-md font-bold">Red de Quintas Seguras San Juan</span>
        </div>
        <div class="flex items-center gap-2 bg-surface-container-low px-3 py-1.5 rounded-full shadow-sm">
          <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
          <span class="font-label-sm text-label-sm text-on-surface font-semibold">Auditores Activos: Pocito, Zonda y Médano</span>
        </div>
      </div>

      <!-- Main Layout Grid: 12 Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
        <!-- LEFT COLUMN: Form Card (7 Cols on Desktop) -->
        <div class="lg:col-span-7 flex flex-col gap-space-md">
          <!-- BANNER PROMO LANZAMIENTO -->
          <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-tertiary-container via-primary-container to-primary text-on-primary p-space-md lg:p-space-lg shadow-md">
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
                    CUPOS LIMITADOS DE APERTURA
                  </span>
                  <span class="font-label-sm text-label-sm text-surface-tint font-bold">Faltan 14/50</span>
                </div>
                <h2 class="font-headline-sm text-headline-sm text-on-primary leading-snug font-bold">
                  Beneficio Lanzamiento: Primeras 50 quintas bonificadas
                </h2>
                <p class="font-body-sm text-body-sm text-primary-fixed mt-1 font-medium">
                  6 meses de suscripción 100% gratis con <strong>inspección técnica presencial in situ</strong> sin costo de traslado.
                </p>
                <div class="w-full bg-surface-container-highest/20 rounded-full h-1.5 mt-3 overflow-hidden">
                  <div class="bg-secondary-fixed h-full rounded-full transition-all duration-700" style="width: 72%;"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- CARD FORMULARIO ELEVADO -->
          <div class="bg-surface-container-lowest rounded-xl p-6 sm:p-8 lg:p-10 shadow-lg border border-surface-container">
            <!-- Selector de Modo: Login vs Registro -->
            <div class="flex p-1 bg-surface-container-low rounded-lg mb-space-md">
              <button
                type="button"
                :class="[
                  'flex-1 py-2.5 px-4 rounded-md font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 font-bold cursor-pointer',
                  activeTab === 'register' ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
                ]"
                @click="activeTab = 'register'"
              >
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Crear Cuenta</span>
              </button>
              <button
                type="button"
                :class="[
                  'flex-1 py-2.5 px-4 rounded-md font-label-lg text-label-lg transition-all flex items-center justify-center gap-2 font-bold cursor-pointer',
                  activeTab === 'login' ? 'text-on-primary bg-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
                ]"
                @click="activeTab = 'login'"
              >
                <span class="material-symbols-outlined text-[18px]">login</span>
                <span>Iniciar Sesión</span>
              </button>
            </div>

            <!-- Selector de Rol (Propietario / Inquilino) -->
            <div v-if="activeTab === 'register'" class="mb-space-md">
              <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2 font-bold">
                Selecciona tu perfil en San Juan
              </p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button
                  type="button"
                  :class="[
                    'flex items-start gap-3 p-3.5 rounded-lg text-left transition-all cursor-pointer',
                    selectedRole === 'owner' ? 'bg-surface-container ring-2 ring-secondary' : 'bg-surface-container-low hover:bg-surface-container'
                  ]"
                  @click="selectedRole = 'owner'"
                >
                  <div class="w-9 h-9 rounded-lg bg-secondary text-on-secondary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">villa</span>
                  </div>
                  <div>
                    <div class="font-label-lg text-label-lg text-on-surface flex items-center gap-1.5 font-bold">
                      Soy Propietario
                      <span v-if="selectedRole === 'owner'" class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-tight mt-0.5">Quiero publicar mi quinta o quincho</p>
                  </div>
                </button>

                <button
                  type="button"
                  :class="[
                    'flex items-start gap-3 p-3.5 rounded-lg text-left transition-all cursor-pointer',
                    selectedRole === 'renter' ? 'bg-surface-container ring-2 ring-secondary' : 'bg-surface-container-low hover:bg-surface-container'
                  ]"
                  @click="selectedRole = 'renter'"
                >
                  <div class="w-9 h-9 rounded-lg bg-surface-container-highest text-on-surface flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-[20px]">pool</span>
                  </div>
                  <div>
                    <div class="font-label-lg text-label-lg text-on-surface font-bold">
                      Soy Inquilino
                      <span v-if="selectedRole === 'renter'" class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-tight mt-0.5">Busco alquilar para pasar el día</p>
                  </div>
                </button>
              </div>
            </div>

            <!-- Botón de Acceso Directo con Google -->
            <div class="mb-space-md">
              <button
                type="button"
                class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface font-label-lg text-label-lg shadow-sm transition-all group font-bold cursor-pointer"
                @click="handleFormSubmit"
              >
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                  <path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z" fill="#4285F4"></path>
                  <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z" fill="#34A853"></path>
                  <path d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.04 0 12s.45 3.82 1.25 5.42l4.03-3.15z" fill="#FBBC05"></path>
                  <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z" fill="#EA4335"></path>
                </svg>
                <span>Continuar con Google</span>
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
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
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
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                  />
                </div>
              </div>

              <!-- Campo: WhatsApp (Registro) -->
              <div v-if="activeTab === 'register'" class="flex flex-col gap-1.5">
                <label class="font-label-lg text-label-lg text-on-surface font-bold">WhatsApp de Contacto San Juan</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                    <span class="material-symbols-outlined text-[20px]">phone_android</span>
                  </div>
                  <input
                    v-model="form.phone"
                    type="tel"
                    required
                    placeholder="Ej: 264 411 2233"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                  />
                </div>
              </div>

              <!-- Campo: Contraseña -->
              <div class="flex flex-col gap-1.5">
                <label class="font-label-lg text-label-lg text-on-surface font-bold">Contraseña</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                    <span class="material-symbols-outlined text-[20px]">lock</span>
                  </div>
                  <input
                    v-model="form.password"
                    type="password"
                    required
                    placeholder="Mínimo 8 caracteres"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline-variant"
                  />
                </div>
              </div>

              <!-- Mensaje de éxito -->
              <div v-if="successMessage" class="p-3 bg-secondary-fixed/40 text-on-secondary-fixed rounded-xl font-label-md text-label-md flex items-center gap-2 font-bold">
                <span class="material-symbols-outlined text-secondary text-[20px]">check_circle</span>
                <span>{{ successMessage }}</span>
              </div>

              <!-- Botón Submit -->
              <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full py-3.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-md font-bold cursor-pointer disabled:opacity-50"
              >
                <span v-if="!isSubmitting">
                  {{ activeTab === 'register' ? (selectedRole === 'owner' ? 'Solicitar Auditoría y Registro' : 'Completar Registro') : 'Ingresar a mi Cuenta' }}
                </span>
                <span v-else>Procesando solicitud...</span>
              </button>
            </form>
          </div>
        </div>

        <!-- RIGHT COLUMN: Testimonio y Garantías (5 Cols on Desktop) -->
        <div class="lg:col-span-5 flex flex-col gap-space-md">
          <!-- Testimonio Propietario El Medanito -->
          <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-surface-container">
            <div class="flex items-center gap-3 mb-space-sm">
              <div class="w-12 h-12 rounded-full bg-primary-container text-secondary-fixed font-bold flex items-center justify-center text-lg">
                MO
              </div>
              <div>
                <h4 class="font-label-lg text-label-lg text-primary font-bold">Familia Olivos</h4>
                <span class="font-body-sm text-body-sm text-on-surface-variant">Propietarios en El Medanito, Rawson</span>
              </div>
            </div>
            <p class="font-body-sm text-body-sm text-on-surface-variant italic leading-relaxed">
              "Antes nos clonaban las fotos en Facebook y estafaban a la gente pidiendo señas a nuestro nombre. Desde que nos auditó Turismo Seguro San Juan, los clientes nos contactan directo sabiendo que somos los verdaderos dueños."
            </p>
            <div class="mt-space-md pt-space-xs border-t border-surface-container flex items-center gap-2 text-secondary font-label-sm text-label-sm font-bold">
              <span class="material-symbols-outlined text-[16px]">verified</span>
              <span>Quinta Verificada In Situ • Feb 2026</span>
            </div>
          </div>

          <!-- Pilares de Confianza Sanjuanina -->
          <div class="bg-surface-container-low p-space-lg rounded-2xl space-y-space-sm border border-surface-container">
            <h4 class="font-headline-sm text-headline-sm text-primary font-bold">
              Garantía Oficial Anti-Estafas
            </h4>
            <ul class="space-y-3 font-body-sm text-body-sm text-on-surface-variant">
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                <span><strong>Inspección Presencial:</strong> Un técnico visita la propiedad y constata pileta, cerco y parrillero.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                <span><strong>CBU y DNI en mano:</strong> Validamos la cuenta donde se transfiere la seña para evitar perfiles falsos.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">check_circle</span>
                <span><strong>Trato 100% directo:</strong> Sin comisiones por alquiler a huéspedes ni intermediarios.</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
