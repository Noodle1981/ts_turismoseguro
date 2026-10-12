<script setup lang="ts">
import type { DocketItem } from '../../composables/supervisor/useSupervisorDictamenes'

defineProps<{
  dockets: DocketItem[]
  selectedDocketId?: string
}>()

const emit = defineEmits<{
  (e: 'select', id: string): void
  (e: 'resetFilter'): void
}>()
</script>

<template>
  <div class="flex flex-col gap-3">
    <div class="flex items-center justify-between px-1">
      <span class="font-label-lg text-label-lg text-primary font-bold">
        Expedientes en Base de Datos ({{ dockets.length }} registros)
      </span>
      <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">
        Orden: Prioridad y Fecha
      </span>
    </div>

    <!-- Si no hay expedientes -->
    <div
      v-if="dockets.length === 0"
      class="p-8 rounded-2xl bg-surface-container-lowest border border-surface-container text-center flex flex-col items-center justify-center gap-3"
    >
      <div class="w-12 h-12 rounded-xl bg-surface-container flex items-center justify-center text-outline">
        <span class="material-symbols-outlined text-[28px]">folder_off</span>
      </div>
      <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
        No hay expedientes en este estado
      </h3>
      <p class="text-xs text-on-surface-variant max-w-sm">
        La base de datos no contiene registros en la pestaña seleccionada. Podés cambiar el filtro a "Ver Todos" o esperar nuevas solicitudes de propietarios.
      </p>
      <button
        type="button"
        class="mt-1 px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-bold cursor-pointer"
        @click="emit('resetFilter')"
      >
        Ver Todos los Expedientes
      </button>
    </div>

    <!-- Lista de tarjetas de expedientes -->
    <div
      v-for="docket in dockets"
      :key="docket.id"
      class="p-4 rounded-2xl bg-surface-container-lowest border transition-all cursor-pointer relative overflow-hidden"
      :class="selectedDocketId === docket.id
        ? 'border-secondary shadow-md bg-gradient-to-r from-emerald-50/40 via-surface-container-lowest to-surface-container-lowest dark:from-emerald-950/20'
        : 'border-surface-container hover:border-surface-container-high hover:shadow-xs'"
      @click="emit('select', docket.id)"
    >
      <!-- Franja activa de selección -->
      <div
        v-if="selectedDocketId === docket.id"
        class="absolute left-0 top-0 bottom-0 w-1.5 bg-secondary"
      ></div>

      <div class="flex items-start justify-between gap-3">
        <div class="flex flex-col gap-1">
          <div class="flex flex-wrap items-center gap-2">
            <span class="font-label-md text-label-md font-mono text-primary font-bold bg-surface-container-high px-2 py-0.5 rounded-lg">
              {{ docket.code }}
            </span>

            <!-- Badge según Status en DB -->
            <span
              v-if="docket.status === 'approved'"
              class="px-2.5 py-0.5 rounded-full bg-secondary-container/40 text-on-secondary-container font-label-sm text-label-sm font-bold flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[14px]">check_circle</span>
              Aprobado Definitivo
            </span>
            <span
              v-else-if="docket.status === 'under_supervision'"
              class="px-2.5 py-0.5 rounded-full bg-tertiary-fixed/60 text-on-tertiary-container font-label-sm text-label-sm font-bold flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[14px]">warning</span>
              Propuesta de Modificación
            </span>
            <span
              v-else-if="docket.status === 'rejected'"
              class="px-2.5 py-0.5 rounded-full bg-error-container text-on-error-container font-label-sm text-label-sm font-bold flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[14px]">cancel</span>
              Rechazado
            </span>
            <span
              v-else
              class="px-2.5 py-0.5 rounded-full bg-amber-500/20 text-amber-800 dark:text-amber-300 font-label-sm text-label-sm font-bold flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
              Esperando Firma Supervisor
            </span>

            <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">
              {{ docket.department }}
            </span>
          </div>

          <h2 class="font-headline-sm text-headline-sm text-primary font-bold mt-1">
            {{ docket.propertyTitle }}
          </h2>

          <div class="flex items-center gap-2 font-body-sm text-body-sm text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px] text-outline">person_pin</span>
            <span>Titular: <strong>{{ docket.ownerName }}</strong></span>
            <span>•</span>
            <span class="font-mono text-on-surface text-xs">DNI {{ docket.ownerDni }}</span>
          </div>
        </div>

        <div class="text-right shrink-0 flex flex-col items-end">
          <span
            class="px-2.5 py-1 rounded-lg font-label-sm text-label-sm font-bold"
            :class="docket.status === 'approved'
              ? 'bg-secondary-fixed/50 text-on-secondary-fixed'
              : docket.status === 'rejected'
              ? 'bg-error-container text-on-error-container'
              : 'bg-amber-500/20 text-amber-900 dark:text-amber-200'"
          >
            {{ docket.status === 'approved' ? '100% Conforme' : docket.status === 'rejected' ? 'Desestimado' : 'Por Resolver' }}
          </span>
          <span class="font-label-sm text-label-sm text-outline mt-1 font-mono">
            {{ docket.hoursAgo }}
          </span>
        </div>
      </div>

      <!-- Footer del item: Validador y Estado Bancario -->
      <div class="mt-3 pt-3 border-t border-surface-container flex flex-wrap items-center justify-between gap-2 bg-surface-container-low/60 p-2.5 rounded-xl">
        <div class="flex items-center gap-2">
          <span class="w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-sm text-[10px] font-bold">
            {{ docket.validator_name ? docket.validator_name.charAt(0) : 'TS' }}
          </span>
          <span class="font-label-sm text-label-sm text-on-surface font-medium">
            Validador: {{ docket.validator_name || 'Sin asignar' }}
          </span>
        </div>

        <div
          class="flex items-center gap-1.5 font-label-sm text-label-sm font-bold"
          :class="docket.payout_account_number ? 'text-secondary' : 'text-outline'"
        >
          <span class="material-symbols-outlined text-[16px]">
            {{ docket.payout_account_number ? (docket.payout_verified ? 'verified_user' : 'account_balance_wallet') : 'credit_card_off' }}
          </span>
          <span>
            <template v-if="docket.payout_account_number">
              <template v-if="docket.payout_account_type === 'billetera_virtual' || docket.payout_account_type === 'cvu'">
                Billetera Virtual registrada{{ docket.payout_bank_name ? ` (${docket.payout_bank_name})` : '' }}
              </template>
              <template v-else>
                CBU registrado{{ docket.payout_bank_name ? ` (${docket.payout_bank_name})` : '' }}
              </template>
            </template>
            <template v-else>
              CBU o Billetera Virtual pendiente
            </template>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
