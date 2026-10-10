<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{
  basePrice: number
  quintaTitle: string
  quintaId: number | string
  hostWhatsapp: string
}>()

const rentDate = ref(new Date().toISOString().slice(0, 10))
const selectedShift = ref<'full_day' | 'evening'>('full_day')
const guestCount = ref(20)

const shiftPrices = computed(() => {
  return {
    full_day: props.basePrice,
    evening: Math.round(props.basePrice * 1.1),
  }
})

const extraGuestFee = 1500
const baseGuestsIncluded = 20

const extraGuestsCount = computed(() => {
  return Math.max(0, guestCount.value - baseGuestsIncluded)
})

const extraGuestsTotal = computed(() => {
  return extraGuestsCount.value * extraGuestFee
})

const currentShiftPrice = computed(() => {
  return shiftPrices.value[selectedShift.value]
})

const totalCalculated = computed(() => {
  return currentShiftPrice.value + extraGuestsTotal.value
})

const formattedTotal = computed(() => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(totalCalculated.value)
})

const incrementGuests = () => {
  if (guestCount.value < 40) guestCount.value++
}

const decrementGuests = () => {
  if (guestCount.value > 1) guestCount.value--
}

const whatsappBookingUrl = computed(() => {
  const cleanPhone = props.hostWhatsapp.replace(/[^0-9]/g, '')
  const shiftText = selectedShift.value === 'full_day' ? 'Día Completo (10:00 a 20:30 hs)' : 'Turno Tarde / Noche (15:00 a 01:00 hs)'
  const message = `¡Hola! Me comunico a través del portal oficial *Turismo Seguro San Juan* para reservar tu quinta o cabaña *${props.quintaTitle}* (ID: ${props.quintaId}).\n\n📅 Fecha: ${rentDate.value}\n⏰ Turno: ${shiftText}\n👥 Cantidad de Personas: ${guestCount.value}\n💵 Presupuesto de Referencia: ${formattedTotal.value}\n\n¿Tienen disponibilidad para coordinar la seña oficial? Muchas gracias!`
  return `https://wa.me/${cleanPhone}?text=${encodeURIComponent(message)}`
})
</script>

<template>
  <aside class="sticky top-24 space-y-space-md">
    <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-xl transition-all border border-surface-container">
      <!-- Card Header Price -->
      <div class="flex items-start justify-between pb-space-sm mb-space-md border-b border-surface-container-high">
        <div>
          <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block font-bold">
            Valor Base Diurno
          </span>
          <div class="flex items-baseline gap-1">
            <span class="font-headline-xl text-headline-xl text-primary font-extrabold">
              ${{ basePrice.toLocaleString('es-AR') }}
            </span>
            <span class="font-body-sm text-body-sm text-on-surface-variant">/ jornada</span>
          </div>
        </div>
        <div class="text-right">
          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[14px] text-secondary">handshake</span>
            Trato Directo
          </span>
          <span class="block text-[11px] text-on-surface-variant mt-1 font-semibold text-secondary">
            0% Comisión al Inquilino
          </span>
        </div>
      </div>

      <form class="space-y-space-md" @submit.prevent>
        <!-- 1. Date Selector -->
        <div>
          <label class="block font-label-lg text-label-lg text-on-surface mb-1.5 flex items-center justify-between font-bold">
            <span>Fecha del Día de Campo:</span>
            <span class="font-label-sm text-label-sm text-secondary font-bold">Disponibilidad Inmediata</span>
          </label>
          <input
            v-model="rentDate"
            type="date"
            class="w-full bg-surface-container-low text-on-surface font-body-md text-body-md px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary shadow-sm"
          />
        </div>

        <!-- 2. Shift Selector -->
        <div>
          <label class="block font-label-lg text-label-lg text-on-surface mb-2 font-bold">
            Selecciona la Franja Horaria:
          </label>
          <div class="grid grid-cols-1 gap-2">
            <!-- Option 1: Día Completo -->
            <label
              :class="[
                'flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border',
                selectedShift === 'full_day'
                  ? 'bg-surface-container-low border-primary/40 shadow-xs'
                  : 'bg-surface-container-lowest border-surface-container hover:bg-surface-container-low'
              ]"
            >
              <div class="flex items-center gap-3">
                <input
                  v-model="selectedShift"
                  type="radio"
                  value="full_day"
                  class="w-4 h-4 text-secondary accent-secondary"
                />
                <div>
                  <div class="flex items-center gap-1.5 font-label-lg text-label-lg text-on-surface font-bold">
                    <span class="material-symbols-outlined text-[18px] text-amber-500">sunny</span>
                    Día Completo Familiar
                  </div>
                  <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">
                    10:00 hs a 20:30 hs (10.5 hs de pileta)
                  </span>
                </div>
              </div>
              <span class="font-label-lg text-label-lg text-primary font-bold">
                ${{ shiftPrices.full_day.toLocaleString('es-AR') }}
              </span>
            </label>

            <!-- Option 2: Tarde / Cena -->
            <label
              :class="[
                'flex items-center justify-between p-3 rounded-xl cursor-pointer transition-all border',
                selectedShift === 'evening'
                  ? 'bg-surface-container-low border-primary/40 shadow-xs'
                  : 'bg-surface-container-lowest border-surface-container hover:bg-surface-container-low'
              ]"
            >
              <div class="flex items-center gap-3">
                <input
                  v-model="selectedShift"
                  type="radio"
                  value="evening"
                  class="w-4 h-4 text-secondary accent-secondary"
                />
                <div>
                  <div class="flex items-center gap-1.5 font-label-lg text-label-lg text-on-surface font-bold">
                    <span class="material-symbols-outlined text-[18px] text-primary">nights_stay</span>
                    Turno Tarde / Cena
                  </div>
                  <span class="font-body-sm text-body-sm text-on-surface-variant text-xs">
                    15:00 hs a 01:00 hs (10 hs quincho)
                  </span>
                </div>
              </div>
              <span class="font-label-lg text-label-lg text-primary font-bold">
                ${{ shiftPrices.evening.toLocaleString('es-AR') }}
              </span>
            </label>
          </div>
        </div>

        <!-- 3. Guest Stepper -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="font-label-lg text-label-lg text-on-surface font-bold">
              Cantidad Total de Personas:
            </label>
            <span class="font-label-sm text-label-sm text-on-surface-variant">
              Base: hasta {{ baseGuestsIncluded }} personas
            </span>
          </div>
          <div class="flex items-center justify-between bg-surface-container-low p-2 rounded-xl">
            <button
              type="button"
              class="w-10 h-10 rounded-lg bg-surface-container-lowest text-primary flex items-center justify-center font-headline-sm hover:bg-surface-container transition-all shadow-sm cursor-pointer"
              @click="decrementGuests"
            >
              <span class="material-symbols-outlined text-[20px]">remove</span>
            </button>
            <div class="text-center">
              <span class="font-headline-md text-headline-md text-primary font-bold">{{ guestCount }}</span>
              <span class="font-body-sm text-body-sm text-on-surface-variant block text-xs">personas</span>
            </div>
            <button
              type="button"
              class="w-10 h-10 rounded-lg bg-surface-container-lowest text-primary flex items-center justify-center font-headline-sm hover:bg-surface-container transition-all shadow-sm cursor-pointer"
              @click="incrementGuests"
            >
              <span class="material-symbols-outlined text-[20px]">add</span>
            </button>
          </div>
          <p class="font-body-sm text-body-sm text-on-surface-variant text-xs mt-1 italic">
            * Adicional de $1.500 por persona extra a partir del invitado {{ baseGuestsIncluded + 1 }}.
          </p>
        </div>

        <!-- 4. Quotation Breakdown -->
        <div class="bg-surface-container-low p-space-md rounded-xl space-y-2 text-sm">
          <div class="flex justify-between text-on-surface-variant font-body-sm text-body-sm">
            <span>Valor del turno seleccionado:</span>
            <span class="font-semibold text-on-surface">${{ currentShiftPrice.toLocaleString('es-AR') }}</span>
          </div>
          <div v-if="extraGuestsCount > 0" class="flex justify-between text-on-surface-variant font-body-sm text-body-sm">
            <span>Adicional invitados extra ({{ extraGuestsCount }}):</span>
            <span class="font-semibold text-on-surface">${{ extraGuestsTotal.toLocaleString('es-AR') }}</span>
          </div>
          <div class="pt-2 border-t border-surface-container-highest flex justify-between items-baseline font-bold">
            <span class="text-on-surface font-label-lg text-label-lg">Total de Referencia de Lista:</span>
            <span class="font-headline-sm text-headline-sm text-primary">{{ formattedTotal }}</span>
          </div>
          <div class="mt-3 pt-3 border-t border-surface-container-highest space-y-2">
            <div class="flex items-start gap-2 text-xs font-label-md text-secondary">
              <span class="material-symbols-outlined text-[16px] shrink-0">check_circle</span>
              <span><strong>Compromiso de Precio:</strong> El anfitrión verificado debe respetar este precio acordado por WhatsApp.</span>
            </div>
            <div class="flex items-start gap-2 text-xs text-on-surface-variant">
              <span class="material-symbols-outlined text-[16px] shrink-0 text-primary">payments</span>
              <span><strong>Trato y pago directo:</strong> Se acuerda y transfiere 100% directo al dueño verificado sin intermediarios ni cargos de servicio.</span>
            </div>
          </div>
        </div>

        <!-- 5. High-Conversion WhatsApp CTA -->
        <div class="space-y-space-xs pt-1">
          <a
            :href="whatsappBookingUrl"
            target="_blank"
            rel="noopener noreferrer"
            class="w-full flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg shadow-lg hover:bg-on-secondary-container transition-all text-center font-bold cursor-pointer"
          >
            <span class="material-symbols-outlined text-[22px]">chat</span>
            <div class="flex flex-col items-start text-left">
              <span>Contactar y Reservar por WhatsApp Verificado</span>
              <span class="text-[11px] font-normal opacity-90">Hablá directo con el anfitrión validado</span>
            </div>
          </a>
        </div>
      </form>
    </div>
  </aside>
</template>
