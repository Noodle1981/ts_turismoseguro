<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuth } from '../../composables/useAuth'
import { usePermissions } from '../../composables/usePermissions'

const router = useRouter()
const { user, isAuthenticated, logout } = useAuth()
const { isStaff, currentRole, isViewingAsUser, toggleViewAsUser } = usePermissions()

// Etiqueta legible del rol de staff
const staffRoleLabel = computed(() => {
  switch (currentRole.value) {
    case 'validator':
      return 'Validador Matriculado'
    case 'supervisor':
      return 'Supervisor de Calidad'
    case 'admin':
      return 'Administración Provincial'
    case 'super_admin':
      return 'Super Administrador'
    default:
      return 'Staff Oficial'
  }
})

// Ruta del panel según el rol interno
const staffDashboardRoute = computed(() => {
  if (currentRole.value === 'validator') {
    return '/validador/bandeja'
  }
  return '/'
})

const handleToggleView = () => {
  toggleViewAsUser()
  if (!isViewingAsUser.value) {
    router.push(staffDashboardRoute.value)
  }
}

const handleLogout = async () => {
  const wasStaff = isStaff.value
  await logout()
  if (wasStaff) {
    router.push('/staff/login')
  } else {
    router.push('/')
  }
}
</script>

<template>
  <div class="w-full">
    <!-- Main Stitch Header -->
    <header class="w-full bg-surface-container-lowest/90 backdrop-blur-md shadow-[0_1px_8px_rgba(15,41,66,0.05)] border-b border-surface-container">
      <div class="h-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex items-center justify-between gap-space-md">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-space-md">
          <RouterLink to="/" class="flex items-center gap-space-sm">
            <div class="w-9 h-9 rounded-xl bg-primary text-secondary-fixed flex items-center justify-center font-bold shadow-xs">
              <span class="material-symbols-outlined text-[24px]">shield</span>
            </div>
            <span class="font-headline-sm text-headline-sm text-primary tracking-tight hidden sm:inline-block">
              Turismo Seguro <span class="font-headline-sm text-headline-sm text-secondary">San Juan</span>
            </span>
          </RouterLink>

          <!-- Pill de Modo Staff cuando está autenticado como personal -->
          <div v-if="isAuthenticated && isStaff" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container border border-surface-container">
            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
            <span class="font-label-sm text-label-sm text-primary font-bold">
              {{ isViewingAsUser ? 'Modo Exploración (Vista Usuario)' : 'Portal Operativo Staff' }}
            </span>
          </div>
        </div>

        <!-- 1. CASO PERSONAL STAFF AUTENTICADO (Validador, Supervisor, Admin, Super Admin) -->
        <template v-if="isAuthenticated && isStaff">
          <!-- Si NO está simulando vista de usuario, se oculta el menú público y se muestra barra staff -->
          <nav v-if="!isViewingAsUser" class="hidden xl:flex items-center gap-2">
            <RouterLink
              v-if="currentRole === 'validator'"
              to="/validador/bandeja"
              class="px-3 py-1.5 rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors font-bold flex items-center gap-1.5"
              active-class="bg-surface-container text-primary shadow-xs"
            >
              <span class="material-symbols-outlined text-[18px]">inbox</span>
              <span>Bandeja de Solicitudes</span>
            </RouterLink>

            <RouterLink
              v-if="currentRole === 'validator'"
              to="/validador/mapa"
              class="px-3 py-1.5 rounded-lg font-label-md text-label-md text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors font-bold flex items-center gap-1.5"
              active-class="bg-surface-container text-primary shadow-xs"
            >
              <span class="material-symbols-outlined text-[18px]">map</span>
              <span>Mapa de Inspecciones</span>
            </RouterLink>
          </nav>

          <!-- Si el personal activó "Ver como Usuario", le permitimos navegar el catálogo temporalmente -->
          <nav v-else class="hidden lg:flex items-center gap-space-md">
            <RouterLink
              to="/explorar"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
              active-class="text-primary font-bold"
            >
              Explorar Quintas y Cabañas
            </RouterLink>
            <RouterLink
              to="/eventos"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
              active-class="text-primary font-bold"
            >
              Eventos &amp; Experiencias
            </RouterLink>
          </nav>

          <!-- Controles de Staff a la Derecha: Badge de sesión + Toggle Vista Usuario + Logout -->
          <div class="flex items-center gap-2 sm:gap-3">
            <!-- Botón para alternar vista (Ver como Usuario vs Volver al Panel Staff) -->
            <button
              type="button"
              class="inline-flex items-center gap-1.5 font-label-sm text-label-sm px-3 py-2 rounded-xl transition-all font-bold cursor-pointer border"
              :class="isViewingAsUser
                ? 'bg-primary text-on-primary border-primary shadow-xs'
                : 'bg-surface-container hover:bg-surface-dim text-primary border-surface-container'"
              :title="isViewingAsUser ? 'Regresar al Panel Técnico' : 'Explorar el catálogo como si fueras un inquilino sin cerrar sesión'"
              @click="handleToggleView"
            >
              <span class="material-symbols-outlined text-[17px]">
                {{ isViewingAsUser ? 'admin_panel_settings' : 'travel_explore' }}
              </span>
              <span class="hidden sm:inline">
                {{ isViewingAsUser ? 'Volver a Panel Staff' : 'Ver como Usuario' }}
              </span>
            </button>

            <!-- Card de Sesión Staff Activa -->
            <div class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-xl bg-surface-container-low border border-surface-container max-w-[240px]">
              <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px]">engineering</span>
              </div>
              <div class="hidden sm:flex flex-col min-w-0 leading-tight">
                <span class="font-label-sm text-label-sm text-primary font-bold truncate">
                  {{ user?.name ?? 'Matías Fernández (Validador)' }}
                </span>
                <div class="flex items-center gap-1 text-[11px] text-secondary font-semibold">
                  <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                  <span>{{ staffRoleLabel }}</span>
                </div>
              </div>
            </div>

            <!-- Botón Salir -->
            <button
              type="button"
              class="p-2 rounded-xl text-on-surface-variant hover:text-error hover:bg-surface-container transition-colors cursor-pointer border border-surface-container"
              title="Cerrar sesión Staff"
              @click="handleLogout"
            >
              <span class="material-symbols-outlined text-[18px]">logout</span>
            </button>
          </div>
        </template>

        <!-- 2. CASO PÚBLICO: PROPIETARIO O INQUILINO AUTENTICADO O VISITANTE ANÓNIMO -->
        <template v-else>
          <!-- Navigation Links según tipo de usuario -->
          <!-- A) SI ES PROPIETARIO AUTENTICADO: Navegación del Panel Interno -->
          <nav v-if="isAuthenticated && currentRole === 'client'" class="hidden lg:flex items-center gap-space-md">
            <RouterLink
              to="/propietario/panel#mis-ofertas"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold flex items-center gap-1.5"
            >
              <span class="material-symbols-outlined text-[18px] text-primary">villa</span>
              <span>Mis Ofertas</span>
            </RouterLink>

            <button
              type="button"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold flex items-center gap-1.5 cursor-pointer"
              @click="router.push('/propietario/panel?action=nueva-oferta')"
            >
              <span class="material-symbols-outlined text-[18px] text-secondary">add_business</span>
              <span>Agregar Oferta</span>
            </button>

            <RouterLink
              to="/propietario/panel#pagos"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold flex items-center gap-1.5"
            >
              <span class="material-symbols-outlined text-[18px] text-secondary">payments</span>
              <span>Pagos</span>
            </RouterLink>
          </nav>

          <!-- B) VISITANTE ANÓNIMO O INQUILINO: Catálogo Público -->
          <nav v-else class="hidden lg:flex items-center gap-space-md">
            <RouterLink
              to="/explorar"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
              active-class="text-primary font-bold"
            >
              Explorar Quintas y Cabañas
            </RouterLink>
            <RouterLink
              to="/eventos"
              class="px-space-sm py-2 transition-colors font-label-lg text-label-lg text-on-surface-variant hover:text-primary font-semibold"
              active-class="text-primary font-bold"
            >
              Eventos &amp; Experiencias
            </RouterLink>
            <a
              href="#protocolo"
              class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary px-space-sm py-2 transition-colors font-semibold"
            >
              Cómo Validamos
            </a>
          </nav>

          <!-- Zona de Usuario a la Derecha -->
          <div class="flex items-center gap-space-sm">
            <!-- Si el Propietario o Inquilino está autenticado -->
            <div v-if="isAuthenticated" class="flex items-center gap-2 sm:gap-3">
              <RouterLink
                :to="currentRole === 'client' ? '/propietario/panel' : '/inquilino/panel'"
                class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 rounded-xl bg-surface-container-low hover:bg-surface-container border border-surface-container max-w-[220px] transition-colors group cursor-pointer"
                :title="currentRole === 'client' ? 'Ir al Panel de Propietario' : 'Ir a Mis Alquileres'"
              >
                <div
                  class="w-8 h-8 rounded-full flex items-center justify-center shrink-0"
                  :class="currentRole === 'client' ? 'bg-secondary text-on-secondary' : 'bg-primary text-on-primary'"
                >
                  <span class="material-symbols-outlined text-[18px]">
                    {{ currentRole === 'client' ? 'villa' : 'person' }}
                  </span>
                </div>
                <div class="hidden sm:flex flex-col min-w-0 leading-tight">
                  <span class="font-label-sm text-label-sm text-primary font-bold truncate group-hover:underline">
                    {{ user?.name ?? 'Mi Cuenta' }}
                  </span>
                  <span class="text-[11px] font-semibold" :class="currentRole === 'client' ? 'text-secondary' : 'text-on-surface-variant'">
                    {{ currentRole === 'client' ? 'Panel Propietario' : 'Mis Alquileres' }}
                  </span>
                </div>
              </RouterLink>

              <button
                type="button"
                class="p-2 rounded-xl text-on-surface-variant hover:text-error hover:bg-surface-container transition-colors cursor-pointer border border-surface-container"
                title="Cerrar sesión"
                @click="handleLogout"
              >
                <span class="material-symbols-outlined text-[18px]">logout</span>
              </button>
            </div>

            <!-- Si NO está autenticado (Visitante) -->
            <template v-else>
              <a
                href="#anfitriones"
                class="hidden md:inline-flex items-center justify-center font-label-md text-label-md px-space-md py-2.5 rounded-lg bg-primary text-on-primary hover:bg-primary-container transition-colors font-bold shadow-xs"
              >
                Publicar Oferta
              </a>
              <RouterLink
                to="/login"
                class="inline-flex items-center gap-1.5 font-label-md text-label-md px-space-md py-2.5 rounded-lg bg-surface-container hover:bg-surface-dim text-on-surface transition-colors shadow-xs font-bold"
              >
                <span class="material-symbols-outlined text-[18px] text-primary">login</span>
                <span>Iniciar Sesión</span>
              </RouterLink>
            </template>
          </div>
        </template>
      </div>
    </header>
  </div>
</template>
