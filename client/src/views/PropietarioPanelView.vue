<script setup lang="ts">
import { ref, onMounted, watch, defineAsyncComponent } from 'vue'
import { useRoute } from 'vue-router'

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

const route = useRoute()

// Período activo de visualización (Octubre 2026 por defecto)
const selectedYear = ref<number>(2026)
const selectedMonth = ref<number>(10)

// 1. Estado y Operaciones de Ofertas
const {
  user,
  myOffers,
  selectedOfferId,
  selectedOffer,
  activeNav,
  tariffsTab,
  tarifaSavedFeedback,
  handleSaveTarifas,
  showAddOfferModal,
  newOffer,
  handleAddOffer,
  revalidacionForm,
  handleSolicitarRevalidacion,
  handleLogout,
} = usePropietarioOffers()

// 2. Gestión de Inquilinos
const {
  tenantTab,
  showTenantModal,
  selectedTenant,
  openTenantDetail,
  setTenantRating,
} = usePropietarioTenants()

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

onMounted(() => {
  if (route.query.action === 'nueva-oferta') {
    showAddOfferModal.value = true
  }
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
    <!-- 1. ASIDE: SIDEBAR IZQUIERDO -->
    <PropietarioSidebar
      :my-offers="myOffers"
      :selected-offer-id="selectedOfferId"
      :active-nav="activeNav"
      :user-name="user?.name"
      @select-offer="selectedOfferId = $event"
      @update-nav="activeNav = $event"
      @open-add-offer="showAddOfferModal = true"
      @logout="handleLogout"
    />

    <!-- 2. CONTENIDO PRINCIPAL CON OFFSET PL-72 -->
    <div class="pl-72">
      <!-- FIXED TOP SUB-HEADER -->
      <PropietarioTopHeader
        :selected-offer="selectedOffer"
        v-model:selected-month="selectedMonth"
        v-model:selected-year="selectedYear"
        :available-months="availableMonths"
        :available-years="availableYears"
      />

      <!-- MAIN PAGE CONTENT (pt-16) -->
      <main class="relative pt-16 bg-background min-h-screen">
        <div class="flex flex-col w-full">
          <div class="p-space-lg lg:p-margin flex flex-col gap-space-lg max-w-[1440px] mx-auto w-full">
            <!-- 1. HEADER: ESTADO DE LA PROPIEDAD Y MÉTRICAS CLAVE -->
            <PropietarioMetricsHeader
              :selected-offer="selectedOffer"
              :current-metrics="currentMetrics"
              :current-month-events="currentMonthEvents"
              @open-special-events="openSpecialEventModal"
            />

            <!-- 2. GESTIÓN DE OFERTA Y TARIFAS + REVALIDACIÓN IN SITU -->
            <div id="mis-ofertas-y-tarifas" class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg scroll-mt-20">
              <PropietarioTariffsCard
                :selected-offer="selectedOffer"
                v-model:tariffs-tab="tariffsTab"
                :tarifa-saved-feedback="tarifaSavedFeedback"
                :week-day-defs="weekDayDefs"
                :calculate-hours-duration="calculateHoursDuration"
                @save-tarifas="handleSaveTarifas"
                @open-schedule-modal="showScheduleModal = true"
                @toggle-active-day="toggleActiveDay"
              />

              <PropietarioRevalidacionCard
                :revalidacion-form="revalidacionForm"
                @submit="handleSolicitarRevalidacion"
              />
            </div>

            <!-- 3. ALMANAQUE / CALENDARIO INTERACTIVO DIURNO -->
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

            <!-- 4. TABLA DETALLADA DE INQUILINOS Y PÓLIZAS -->
            <PropietarioTenantsSection
              :selected-offer="selectedOffer"
              v-model:tenant-tab="tenantTab"
              @open-detail="openTenantDetail"
              @rate="setTenantRating($event.tenant, $event.rating)"
            />
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
  </div>
</template>
