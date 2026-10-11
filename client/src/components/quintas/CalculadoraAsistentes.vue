<script setup lang="ts">
import { ref, computed } from 'vue'

const guestCount = ref(25)
const baseDailyRate = 45000

const costPerPerson = computed(() => {
  return Math.round(baseDailyRate / guestCount.value)
})

const incrementGuests = () => {
  if (guestCount.value < 80) guestCount.value += 5
}

const decrementGuests = () => {
  if (guestCount.value > 10) guestCount.value -= 5
}
</script>

<template>
  <section class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl w-full" aria-label="Calculadora de costo por persona">
    <div class="bg-surface-container-high rounded-3xl p-space-lg lg:p-space-xl overflow-hidden relative">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
        <!-- Columna 1: Calculador interactivo -->
        <div class="lg:col-span-7 flex flex-col gap-space-md">
          <div class="inline-flex items-center gap-space-xs text-secondary font-label-sm text-label-sm font-semibold uppercase tracking-wider">
            <span class="material-symbols-outlined text-[18px]">calculate</span>
            Calculadora Estimativa para Festejos Diurnos
          </div>
          <h2 class="font-headline-lg text-headline-lg text-primary font-bold">
            Calculá el costo por persona para tu asado o evento
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Alquilar una quinta o cabaña para pasar el día en grupo es más económico y seguro que un salón tradicional. Ajustá la cantidad de invitados:
          </p>

          <div class="bg-surface-container-lowest p-space-md rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-space-md">
            <div>
              <span class="font-label-sm text-label-sm text-on-surface-variant block font-bold">Cantidad de Asistentes</span>
              <div class="flex items-center gap-space-md mt-1">
                <button
                  type="button"
                  aria-label="Disminuir cantidad de asistentes en 5 personas"
                  class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary hover:bg-surface-dim font-headline-sm text-headline-sm font-bold cursor-pointer transition-colors"
                  @click="decrementGuests"
                >
                  -
                </button>
                <span class="font-headline-lg text-headline-lg text-primary min-w-[50px] text-center font-extrabold" aria-live="polite">
                  {{ guestCount }}
                </span>
                <button
                  type="button"
                  aria-label="Aumentar cantidad de asistentes en 5 personas"
                  class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary hover:bg-surface-dim font-headline-sm text-headline-sm font-bold cursor-pointer transition-colors"
                  @click="incrementGuests"
                >
                  +
                </button>
                <span class="font-label-md text-label-md text-on-surface-variant font-medium">personas</span>
              </div>
            </div>

            <div class="sm:text-right w-full sm:w-auto p-space-sm bg-surface-container-low rounded-xl">
              <span class="font-label-sm text-label-sm text-on-surface-variant block font-semibold">Estimado por invitado:</span>
              <span class="font-headline-md text-headline-md text-secondary font-extrabold" aria-live="polite">
                ${{ costPerPerson.toLocaleString('es-AR') }} / pers.
              </span>
              <span class="block font-label-sm text-label-sm text-on-surface-variant font-medium">
                Base promedio $45.000 día
              </span>
            </div>
          </div>

          <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-secondary text-[16px]">info</span>
            Incluye uso total de piscina, quincho o cabaña, asadores y freezers de refrigeración.
          </div>
        </div>

        <!-- Columna 2: Protocolo de Reserva Verificada -->
        <div class="lg:col-span-5 bg-surface-container-lowest p-space-lg rounded-2xl shadow-md">
          <h3 class="font-headline-sm text-headline-sm text-primary mb-space-sm flex items-center gap-2 font-bold">
            <span class="material-symbols-outlined text-secondary text-[22px]">policy</span>
            Protocolo de Reserva Verificada
          </h3>
          <ul class="space-y-space-sm">
            <li class="flex items-start gap-space-xs text-on-surface">
              <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">chat</span>
              <div>
                <strong class="font-label-md text-label-md block">1. Contacto Directo por WhatsApp:</strong>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                  Hablás directamente con el propietario autenticado, sin chats robóticos ni intermediarios que encarezcan.
                </span>
              </div>
            </li>
            <li class="flex items-start gap-space-xs text-on-surface">
              <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">account_balance</span>
              <div>
                <strong class="font-label-md text-label-md block">2. CBU Cotejado en Mano:</strong>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                  Te brindamos el Alias/CBU validado en persona del titular catastral para que transfieras tu seña con total tranquilidad.
                </span>
              </div>
            </li>
            <li class="flex items-start gap-space-xs text-on-surface">
              <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">payments</span>
              <div>
                <strong class="font-label-md text-label-md block">3. Cero Comisiones al Inquilino:</strong>
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                  El dinero va 100% al dueño. Nosotros cobramos una suscripción fija de validación al anfitrión para certificar su predio.
                </span>
              </div>
            </li>
          </ul>
          <div class="mt-space-md pt-space-sm">
            <a
              href="https://wa.me/5492644000000?text=Deseo%20conocer%20como%20auditan%20las%20cuentas"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Conocer cómo auditamos las cuentas bancarias por WhatsApp (se abre en nueva ventana)"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-container transition-colors shadow-sm font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">verified_user</span>
              Conocer cómo auditamos las cuentas bancarias
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
