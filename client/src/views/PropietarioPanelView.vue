<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, defineAsyncComponent } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

// Composables de Dominio
import { usePropietarioOffers } from '../composables/propietario/usePropietarioOffers'
import { usePropietarioCalendar } from '../composables/propietario/usePropietarioCalendar'
import { useSpecialEvents } from '../composables/propietario/useSpecialEvents'
import { usePropietarioTenants } from '../composables/propietario/usePropietarioTenants'
import { usePropietarioBookings } from '../composables/propietario/usePropietarioBookings'

// Layout & Sections
import PropietarioSidebar from '../components/propietario/layout/PropietarioSidebar.vue'
import PropietarioTopHeader from '../components/propietario/layout/PropietarioTopHeader.vue'
import PropietarioMetricsHeader from '../components/propietario/sections/PropietarioMetricsHeader.vue'
import PropietarioTariffsCard from '../components/propietario/sections/PropietarioTariffsCard.vue'
import PropietarioRevalidacionCard from '../components/propietario/sections/PropietarioRevalidacionCard.vue'
import PropietarioCalendarSection from '../components/propietario/sections/PropietarioCalendarSection.vue'
import PropietarioTenantsSection from '../components/propietario/sections/PropietarioTenantsSection.vue'

// Modales Lazy-Loaded (Code-Splitting optimizado para Lighthouse)
const ModalBookingUnified = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalBookingUnified.vue')
)
const ModalTenantDetail = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalTenantDetail.vue')
)
const ModalAddOffer = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalAddOffer.vue')
)
const ModalScheduleConfig = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalScheduleConfig.vue')
)
const ModalSpecialEvent = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalSpecialEvent.vue')
)
const ModalVacationPause = defineAsyncComponent(
  () => import('../components/propietario/modals/ModalVacationPause.vue')
)
import ModalConfirmDialog from '../components/propietario/modals/ModalConfirmDialog.vue'
import { useConfirmDialog } from '../composables/propietario/useConfirmDialog'

const router = useRouter()
const route = useRoute()
const { isAuthenticated, userRole } = useAuth()

// Diálogo visual unificado de alertas y confirmaciones
const { dialogState, showAlert, handleConfirm, handleCancel } = useConfirmDialog()

// Estado del Modal de Vacaciones / Pausa
const showVacationModal = ref(false)

// 1. Estado y Operaciones de Ofertas
const {
  user,
  myOffers,
  selectedOfferId,
  selectedOffer,
  activeNav,
  tariffsTab,
  tarifaSavedFeedback,
  fetchOffers,
  handleSaveTarifas,
  updateListingVisibility,
  showAddOfferModal,
  newOffer,
  handleAddOffer,
  revalidacionForm,
  handleSolicitarRevalidacion,
  handleLogout,
} = usePropietarioOffers()

const handleConfirmPause = async (payload: { mode: 'indefinite' | 'vacation'; startDate?: string; endDate?: string }) => {
  if (selectedOffer.value) {
    await updateListingVisibility({
      isListingVisible: false,
      pauseMode: payload.mode,
      pauseStartDate: payload.startDate,
      pauseEndDate: payload.endDate,
    })

    if (payload.mode === 'vacation') {
      showAlert({
        title: 'Modo Vacaciones Activado',
        message: `El predio estará en receso desde el ${payload.startDate} hasta el ${payload.endDate}. Se reactivará automáticamente luego de esa fecha.`,
        icon: 'beach_access',
      })
    } else {
      showAlert({
        title: 'Publicación Pausada',
        message: 'El predio ha sido ocultado temporalmente del buscador público. Puede reactivarlo cuando desee.',
        icon: 'visibility_off',
      })
    }
  }
}

const handleReactivateOffer = async () => {
  if (selectedOffer.value) {
    await updateListingVisibility({
      isListingVisible: true,
      pauseMode: undefined,
      pauseStartDate: undefined,
      pauseEndDate: undefined,
    })

    showAlert({
      title: 'Predio Reactivado',
      message: '¡El predio vuelve a ser visible en el buscador público para recibir reservas!',
      icon: 'check_circle',
    })
  }
}

// Período activo de visualización (Octubre 2026 por defecto)
const selectedYear = ref<number>(2026)
const selectedMonth = ref<number>(10)

// Estado del Drawer Móvil para pantallas < 1024px
const isMobileSidebarOpen = ref(false)

// 2. Gestión de Inquilinos
const {
  tenantTab,
  showTenantModal,
  selectedTenant,
  fetchTenants,
  openTenantDetail,
  setTenantRating,
} = usePropietarioTenants(selectedOffer)

watch(selectedOfferId, (newId) => {
  fetchTenants(newId)
})

// 3. Gestión de Reservas y Bloqueos
const {
  showBookingModal,
  customBookedDays,
  internalBlocks,
  newBooking,
  totalGuests,
  isOverCapacity,
  openInternalBlockModal,
  handleAddBooking,
} = usePropietarioBookings(selectedOffer, selectedYear, selectedMonth)

// 4. Eventos Masivos y Provinciales
const {
  specialEventBookings,
  relevantEventsForOffer,
  currentMonthEvents,
  showSpecialEventModal,
  specialEventForm,
  selectedSpecialEventDef,
  openSpecialEventModal,
  applySpecialEventToCalendar,
} = useSpecialEvents(selectedOffer, selectedYear, selectedMonth)

// 5. Lógica de Calendario
const {
  availableYears,
  availableMonths,
  weekDayDefs,
  showScheduleModal,
  prevMonth,
  nextMonth,
  currentMetrics,
  toggleActiveDay,
  setSchedulePreset,
  calculateHoursDuration,
  firstDayOffset,
  calendarDays,
  toggleCellState,
} = usePropietarioCalendar(
  selectedOffer,
  selectedYear,
  selectedMonth,
  customBookedDays,
  internalBlocks,
  specialEventBookings,
  showBookingModal,
  newBooking
)

// Cierre accesible por teclado (Escape)
const handleGlobalKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape') {
    dialogState.value.isOpen = false
    showBookingModal.value = false
    showTenantModal.value = false
    showAddOfferModal.value = false
    showScheduleModal.value = false
    showSpecialEventModal.value = false
    showVacationModal.value = false
    isMobileSidebarOpen.value = false
  }
}


// Sincronización de activeNav con el hash de la URL
const syncNavWithHash = () => {
  const hash = window.location.hash
  if (hash === '#calendario') {
    activeNav.value = 'calendario'
  } else if (hash === '#inquilinos') {
    activeNav.value = 'inquilinos'
  } else if (hash === '#tarifas' || hash === '#mis-ofertas-y-tarifas' || hash === '#resumen-general') {
    activeNav.value = 'tarifas'
  }
}

// Al cambiar activeNav manualmente, sincronizar hash
watch(activeNav, (newNav) => {
  if (window.location.hash !== `#${newNav}`) {
    history.replaceState(null, '', `#${newNav}`)
  }
})

onMounted(() => {
  if (!isAuthenticated.value || (userRole.value !== 'client' && userRole.value !== 'super_admin')) {
    router.push({ path: '/login', query: { role: 'owner', redirect: '/propietario/panel' } })
    return
  }

  window.addEventListener('keydown', handleGlobalKeydown)
  window.addEventListener('hashchange', syncNavWithHash)

  syncNavWithHash()
  fetchOffers()
  fetchTenants()

  if (route.query.action === 'nueva-oferta') {
    showAddOfferModal.value = true
  }
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleGlobalKeydown)
  window.removeEventListener('hashchange', syncNavWithHash)
})

watch(
  () => route.query.action,
  (action) => {
    if (action === 'nueva-oferta') {
      showAddOfferModal.value = true
    }
  }
)
</script>

<template>
  <div class="bg-background font-body-md text-on-surface min-h-screen">
    <!-- 1. ASIDE: SIDEBAR IZQUIERDO (RESPONSIVE OFF-CANVAS) -->
    <PropietarioSidebar
      :my-offers="myOffers"
      :selected-offer-id="selectedOfferId"
      :active-nav="activeNav"
      :user-name="user?.name"
      :is-mobile-open="isMobileSidebarOpen"
      @select-offer="selectedOfferId = $event"
      @update-nav="activeNav = $event"
      @open-add-offer="showAddOfferModal = true"
      @logout="handleLogout"
      @close-mobile="isMobileSidebarOpen = false"
    />

    <!-- 2. CONTENIDO PRINCIPAL CON OFFSET RESPONSIVO (PL-0 en móvil, PL-72 en lg) -->
    <div class="pl-0 lg:pl-72 transition-all duration-200">
      <!-- FIXED TOP SUB-HEADER -->
      <PropietarioTopHeader
        :selected-offer="selectedOffer"
        v-model:selected-month="selectedMonth"
        v-model:selected-year="selectedYear"
        :available-months="availableMonths"
        :available-years="availableYears"
        :user-name="user?.name"
        @toggle-mobile-sidebar="isMobileSidebarOpen = !isMobileSidebarOpen"
        @logout="handleLogout"
      />


      <!-- MAIN PAGE CONTENT (pt-16) -->
      <main class="relative pt-16 bg-background min-h-screen">
        <div class="flex flex-col w-full">
          <div class="p-space-md lg:p-space-lg flex flex-col gap-space-md max-w-[1440px] mx-auto w-full">
            <!-- 1. VISTA INDEPENDIENTE: CONFIGURAR TARIFAS (#tarifas) -->
            <div v-show="activeNav === 'tarifas'" id="tarifas" class="flex flex-col gap-space-md">
              <!-- Métricas Clave y Estado de la Propiedad -->
              <PropietarioMetricsHeader
                :selected-offer="selectedOffer"
                :current-metrics="currentMetrics"
                :current-month-events="currentMonthEvents"
                @open-special-events="openSpecialEventModal"
              />

              <!-- Gestión de Tarifas Diurnas/Pernocte/Fiestas + Revalidación In Situ -->
              <div id="mis-ofertas-y-tarifas" class="grid grid-cols-1 lg:grid-cols-12 gap-space-md items-start">
                <PropietarioTariffsCard
                  :selected-offer="selectedOffer"
                  v-model:tariffs-tab="tariffsTab"
                  :tarifa-saved-feedback="tarifaSavedFeedback"
                  :week-day-defs="weekDayDefs"
                  :calculate-hours-duration="calculateHoursDuration"
                  @save-tarifas="handleSaveTarifas"
                  @open-schedule-modal="showScheduleModal = true"
                  @open-vacation-modal="showVacationModal = true"
                  @toggle-listing-visibility="handleReactivateOffer"
                  @toggle-active-day="toggleActiveDay"
                />

                <PropietarioRevalidacionCard
                  :revalidacion-form="revalidacionForm"
                  @submit="handleSolicitarRevalidacion"
                />
              </div>
            </div>

            <!-- 2. VISTA INDEPENDIENTE: CALENDARIO Y RESERVAS (#calendario) -->
            <div v-show="activeNav === 'calendario'" id="calendario">
              <PropietarioCalendarSection
                v-model:selected-month="selectedMonth"
                v-model:selected-year="selectedYear"
                :available-months="availableMonths"
                :available-years="availableYears"
                :current-month-events="currentMonthEvents"
                :selected-offer-department="selectedOffer.department"
                :calendar-days="calendarDays"
                :first-day-offset="firstDayOffset"
                @prev-month="prevMonth"
                @next-month="nextMonth"
                @open-special-events="openSpecialEventModal"
                @open-internal-blocks="openInternalBlockModal"
                @toggle-cell-state="toggleCellState"
              />
            </div>

            <!-- 3. VISTA INDEPENDIENTE: REGISTRO DE INQUILINOS (#inquilinos) -->
            <div v-show="activeNav === 'inquilinos'" id="inquilinos">
              <PropietarioTenantsSection
                :selected-offer="selectedOffer"
                v-model:tenant-tab="tenantTab"
                @open-detail="openTenantDetail"
                @rate="setTenantRating($event.tenant, $event.rating)"
              />
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- 5. MODALES INTERACTIVOS -->
    <ModalBookingUnified
      v-model="showBookingModal"
      :selected-offer="selectedOffer"
      :new-booking="newBooking"
      :total-guests="totalGuests"
      :is-over-capacity="isOverCapacity"
      @submit="handleAddBooking"
    />

    <ModalTenantDetail
      v-model="showTenantModal"
      :selected-tenant="selectedTenant"
      @rate="setTenantRating($event.tenant, $event.rating)"
    />

    <ModalAddOffer
      v-model="showAddOfferModal"
      :new-offer="newOffer"
      @submit="handleAddOffer"
    />

    <ModalScheduleConfig
      v-model="showScheduleModal"
      :selected-offer="selectedOffer"
      :week-day-defs="weekDayDefs"
      :calculate-hours-duration="calculateHoursDuration"
      @toggle-active-day="toggleActiveDay"
      @set-schedule-preset="setSchedulePreset"
    />

    <ModalSpecialEvent
      v-model="showSpecialEventModal"
      :selected-offer="selectedOffer"
      :relevant-events-for-offer="relevantEventsForOffer"
      :selected-special-event-def="selectedSpecialEventDef"
      :special-event-form="specialEventForm"
      @apply="applySpecialEventToCalendar"
    />

    <!-- Modal para Pausar en Buscador / Modo Vacaciones -->
    <ModalVacationPause
      v-model="showVacationModal"
      :offer-title="selectedOffer.title"
      @confirm-pause="handleConfirmPause"
    />

    <!-- Diálogo de Confirmación y Alerta del Sistema (Visual Stitch Canónico) -->
    <ModalConfirmDialog
      :dialog="dialogState"
      @confirm="handleConfirm"
      @cancel="handleCancel"
    />
  </div>
</template>

