<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const { user } = useAuth()

// Tab de reservas: Próximas vs Historial
const activeTab = ref<'proximas' | 'historial'>('proximas')

// Métricas del Inquilino
const metrics = ref({
  proximaFecha: 'Sábado 15 Feb',
  proximaQuinta: 'Solo Día Quinta Los Olivos',
  proximaZona: 'Médano de Oro, Rawson',
  polizaVigente: 'Triunfo Seguros #TS-9482',
  seguroEstado: 'Activo y Verificado',
  totalAlquileres: 3,
  favoritosCount: 5,
})

// Reservas del inquilino
interface Booking {
  id: string
  code: string
  propertyTitle: string
  department: string
  date: string
  shift: string
  guestsCount: number
  totalPrice: number
  depositPaid: number
  balanceDue: number
  cbuVerified: boolean
  insuranceCode: string
  status: 'confirmed' | 'completed' | 'cancelled'
  hostName: string
  hostPhone: string
  imageUrl: string
}

const bookings = ref<Booking[]>([
  {
    id: 'RES-2026-081',
    code: 'RES-2026-081',
    propertyTitle: 'Solo Día Quinta Los Olivos',
    department: 'Rawson (Médano de Oro)',
    date: 'Sábado 15 de Febrero, 2026',
    shift: 'Turno Diurno (10:00 a 20:00 hs)',
    guestsCount: 30,
    totalPrice: 45000,
    depositPaid: 22500,
    balanceDue: 22500,
    cbuVerified: true,
    insuranceCode: 'POL-TRIUNFO-9482',
    status: 'confirmed',
    hostName: 'Elena Morales',
    hostPhone: '+54 9 264 458-9211',
    imageUrl: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 'RES-2026-034',
    code: 'RES-2026-034',
    propertyTitle: 'Cabaña El Zonda Sol y Sombras',
    department: 'Zonda',
    date: 'Domingo 18 de Enero, 2026',
    shift: 'Día Completo (10:00 a 21:00 hs)',
    guestsCount: 20,
    totalPrice: 38000,
    depositPaid: 38000,
    balanceDue: 0,
    cbuVerified: true,
    insuranceCode: 'POL-TRIUNFO-8911',
    status: 'completed',
    hostName: 'Jorge Albarracín',
    hostPhone: '+54 9 264 411-2200',
    imageUrl: 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
  },
])

// Modal de Póliza / Voucher QR
const showVoucherModal = ref(false)
const selectedBooking = ref<Booking | null>(null)

const openVoucher = (booking: Booking) => {
  selectedBooking.value = booking
  showVoucherModal.value = true
}
</script>

<template>
  <main class="w-full bg-surface min-h-screen py-8 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto flex flex-col gap-8">

      <!-- 1. BIENVENIDA Y ESTADO DE CONFIANZA INQUILINO -->
      <section class="bg-surface-container-low rounded-2xl p-6 sm:p-8 shadow-sm border border-surface-container relative overflow-hidden flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="absolute -right-16 -top-16 w-72 h-72 rounded-full bg-secondary-fixed/20 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col gap-2 max-w-2xl">
          <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm shadow-xs font-bold">
              <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
              Identidad Validada • San Juan Seguro
            </span>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container-lowest text-secondary font-label-sm text-label-sm shadow-xs font-bold">
              <span class="material-symbols-outlined text-[15px]">security</span>
              Garantía Cero Estafas Activa
            </span>
          </div>

          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
            ¡Hola {{ user?.name ?? 'Carlos' }}! Gestioná tus días de pileta y salidas seguras
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
            Tus señas se transfieren directo al CBU verificado del dueño auditado in situ. Disfrutá con tu familia sin intermediarios, comisiones sorpresas ni perfiles clonados.
          </p>
        </div>

        <!-- Badges de Estado del Huésped -->
        <div class="relative z-10 flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
          <div class="flex items-center gap-3 bg-surface-container-lowest p-3.5 rounded-xl shadow-xs border border-surface-container">
            <div class="w-10 h-10 rounded-lg bg-secondary/15 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[24px]">verified_user</span>
            </div>
            <div>
              <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block font-bold">Nivel de Confianza</span>
              <span class="font-label-md text-label-md text-secondary font-bold">Inquilino Verificado (DNI cotejado)</span>
            </div>
          </div>

          <div class="flex items-center gap-3 bg-surface-container-lowest p-3.5 rounded-xl shadow-xs border border-surface-container">
            <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center">
              <span class="material-symbols-outlined text-[22px]">health_and_safety</span>
            </div>
            <div>
              <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block font-bold">Protección Diurna</span>
              <span class="font-label-md text-label-md text-primary font-bold">Microseguro San Juan Activo</span>
            </div>
          </div>
        </div>
      </section>

      <!-- 2. BENTO CARDS DE MÉTRICAS -->
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between text-on-surface-variant">
            <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">Próxima Salida</span>
            <span class="material-symbols-outlined text-secondary text-[22px]">event_available</span>
          </div>
          <div class="my-2">
            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-900 mb-1">Confirmada</span>
            <div class="font-headline-sm text-headline-sm text-primary font-bold">{{ metrics.proximaFecha }}</div>
            <p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">{{ metrics.proximaQuinta }}</p>
          </div>
          <span class="font-label-sm text-label-sm text-secondary font-semibold">{{ metrics.proximaZona }}</span>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between text-on-surface-variant">
            <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">Póliza Diurna</span>
            <span class="material-symbols-outlined text-secondary text-[22px]">health_and_safety</span>
          </div>
          <div class="my-2">
            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-secondary-fixed/50 text-secondary mb-1">Cobertura In Situ</span>
            <div class="font-headline-sm text-headline-sm text-primary font-bold">{{ metrics.polizaVigente }}</div>
            <p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-0.5">Resp. Civil + Accidentes Diurnos</p>
          </div>
          <span class="font-label-sm text-label-sm text-secondary font-semibold">Emitida para el titular y asistentes</span>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between text-on-surface-variant">
            <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">Señas Auditadas</span>
            <span class="material-symbols-outlined text-secondary text-[22px]">account_balance</span>
          </div>
          <div class="my-2">
            <span class="font-headline-xl text-headline-xl text-secondary font-extrabold">100%</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-1">transferencias seguras</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">CBU verificado contra titular en DNI</p>
          </div>
          <span class="font-label-sm text-label-sm text-secondary font-semibold">Cero intermediarios</span>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between text-on-surface-variant">
            <span class="font-label-sm text-label-sm uppercase tracking-wider font-bold">Exploración Rápida</span>
            <span class="material-symbols-outlined text-primary text-[22px]">search</span>
          </div>
          <div class="my-2">
            <div class="font-headline-sm text-headline-sm text-primary font-bold">19 Departamentos</div>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Quintas, cabañas y quinchos auditados in situ</p>
          </div>
          <RouterLink
            to="/explorar"
            class="inline-flex items-center gap-1 font-label-sm text-label-sm text-primary hover:text-secondary font-bold"
          >
            <span>Buscar nueva quinta</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
          </RouterLink>
        </div>
      </section>

      <!-- 3. MIS RESERVAS Y PÓLIZAS -->
      <section class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 shadow-sm border border-surface-container flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-surface-container">
          <div>
            <h2 class="font-headline-md text-headline-md text-primary font-bold">
              Mis Reservas y Certificados de Seña
            </h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              Comprobantes oficiales de contratación diurna, pólizas de seguro por día y contacto directo verificado.
            </p>
          </div>

          <div class="flex items-center gap-1.5 bg-surface-container-low p-1 rounded-xl">
            <button
              type="button"
              :class="[
                'px-4 py-2 rounded-lg font-label-md text-label-md font-bold transition-all cursor-pointer',
                activeTab === 'proximas' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
              ]"
              @click="activeTab = 'proximas'"
            >
              Próximas Salidas
            </button>
            <button
              type="button"
              :class="[
                'px-4 py-2 rounded-lg font-label-md text-label-md font-bold transition-all cursor-pointer',
                activeTab === 'historial' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
              ]"
              @click="activeTab = 'historial'"
            >
              Historial
            </button>
          </div>
        </div>

        <!-- Cards de Reservas -->
        <div class="grid grid-cols-1 gap-4">
          <div
            v-for="booking in bookings"
            :key="booking.id"
            class="p-5 rounded-2xl border border-surface-container bg-surface-container-low flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 hover:shadow-xs transition-shadow"
          >
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 flex-1">
              <img
                :src="booking.imageUrl"
                :alt="booking.propertyTitle"
                class="w-full sm:w-36 h-28 rounded-xl object-cover shrink-0"
              />

              <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                  <span class="font-mono text-xs font-bold text-primary bg-surface-container px-2.5 py-0.5 rounded-full">
                    {{ booking.code }}
                  </span>
                  <span class="inline-flex items-center gap-1 text-secondary text-xs font-bold bg-secondary-fixed/50 px-2.5 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[13px]">verified</span> CBU Dueño Verificado
                  </span>
                </div>

                <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
                  {{ booking.propertyTitle }}
                </h3>

                <p class="font-body-sm text-body-sm text-on-surface font-semibold flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px] text-secondary">calendar_today</span>
                  {{ booking.date }} • {{ booking.shift }}
                </p>

                <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-[16px]">groups</span>
                  Aforo declarado: {{ booking.guestsCount }} personas • Anfitrión: {{ booking.hostName }}
                </p>
              </div>
            </div>

            <!-- Precios y Acciones -->
            <div class="flex flex-row lg:flex-col items-center lg:items-end justify-between w-full lg:w-auto gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-surface-container">
              <div class="text-left lg:text-right">
                <span class="font-label-sm text-label-sm text-on-surface-variant block">Seña transferida</span>
                <span class="font-headline-sm text-headline-sm text-secondary font-extrabold">
                  ${{ booking.depositPaid.toLocaleString('es-AR') }} ARS
                </span>
                <span v-if="booking.balanceDue > 0" class="font-label-sm text-label-sm text-on-surface-variant block">
                  Saldo en el predio: ${{ booking.balanceDue.toLocaleString('es-AR') }}
                </span>
                <span v-else class="font-label-sm text-label-sm text-secondary font-bold block">
                  100% Abonado
                </span>
              </div>

              <div class="flex items-center gap-2">
                <a
                  :href="`https://wa.me/${booking.hostPhone.replace(/[^0-9]/g, '')}`"
                  target="_blank"
                  class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-dim text-primary font-label-md text-label-md font-bold flex items-center gap-1 transition-colors border border-surface-container"
                  title="Contactar al dueño directo"
                >
                  <span class="material-symbols-outlined text-[16px] text-secondary">chat</span>
                  <span>WhatsApp Dueño</span>
                </a>

                <button
                  type="button"
                  class="px-4 py-2 rounded-xl bg-primary text-on-primary hover:bg-primary-container font-label-md text-label-md font-bold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer"
                  @click="openVoucher(booking)"
                >
                  <span class="material-symbols-outlined text-[18px] text-secondary">qr_code_2</span>
                  <span>Ver Póliza &amp; QR</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

    </div>

    <!-- MODAL VOUCHER & QR OFICIAL -->
    <div
      v-if="showVoucherModal && selectedBooking"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
    >
      <div class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-surface-container flex flex-col gap-5">
        <div class="flex items-center justify-between pb-3 border-b border-surface-container">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[24px]">verified</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Voucher y Cobertura Oficial</h3>
          </div>
          <button
            type="button"
            class="p-1 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer"
            @click="showVoucherModal = false"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <div class="p-4 rounded-xl bg-surface-container-low border border-surface-container flex flex-col items-center text-center gap-2">
          <div class="w-32 h-32 bg-white p-2 rounded-xl border border-surface-container flex items-center justify-center shadow-xs">
            <span class="material-symbols-outlined text-[96px] text-primary">qr_code_2</span>
          </div>
          <span class="font-mono text-xs font-bold text-secondary bg-secondary-fixed/50 px-3 py-0.5 rounded-full mt-1">
            {{ selectedBooking.insuranceCode }}
          </span>
          <span class="font-body-sm text-body-sm text-on-surface-variant">
            Exhibí este código al ingresar a la quinta o ante la guardia de inspección.
          </span>
        </div>

        <div class="space-y-2 text-sm">
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Inmueble:</span>
            <strong class="text-primary font-bold">{{ selectedBooking.propertyTitle }}</strong>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Fecha:</span>
            <span class="text-primary font-semibold">{{ selectedBooking.date }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Anfitrión:</span>
            <span class="text-secondary font-bold">{{ selectedBooking.hostName }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Seguro Diario:</span>
            <span class="text-secondary font-bold">Vigente (Triunfo Seguros)</span>
          </div>
        </div>

        <button
          type="button"
          class="w-full py-3 rounded-xl bg-primary text-on-primary font-label-md text-label-md font-bold hover:bg-primary-container transition-colors cursor-pointer"
          @click="showVoucherModal = false"
        >
          Entendido
        </button>
      </div>
    </div>
  </main>
</template>
