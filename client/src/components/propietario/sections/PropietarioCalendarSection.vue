<script setup lang="ts">
import type { CalendarDay, ProvincialEvent } from '../../../types/propietario'

defineProps<{
  selectedMonth: number
  selectedYear: number
  availableMonths: Array<{ value: number; label: string }>
  availableYears: number[]
  currentMonthEvents: ProvincialEvent[]
  selectedOfferDepartment?: string
  calendarDays: CalendarDay[]
  firstDayOffset: number
}>()

const emit = defineEmits<{
  (e: 'update:selectedMonth', value: number): void
  (e: 'update:selectedYear', value: number): void
  (e: 'prevMonth'): void
  (e: 'nextMonth'): void
  (e: 'openSpecialEvents'): void
  (e: 'openInternalBlocks'): void
  (e: 'toggleCellState', day: number): void
}>()
</script>

<template>
  <section
    id="almanaque"
    class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-space-md">
      <div>
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-primary text-[24px]">calendar_month</span>
          <h2 class="font-headline-md text-headline-md text-primary font-bold">Calendario y Disponibilidad Diurna</h2>
        </div>
        <p class="font-body-sm text-body-sm text-on-surface-variant">
          Haga clic en cualquier
          <strong class="text-secondary font-bold">día disponible</strong> para agendar directamente la reserva
          (Diurna, Multidía o Fiesta) con la fecha preseleccionada.
        </p>
      </div>

      <!-- Acciones rápidas del calendario -->
      <div class="flex items-center gap-space-sm flex-wrap">
        <!-- Navegación Universal de Mes y Año con Flechas -->
        <div
          class="flex items-center gap-1 bg-surface-container-low px-2 py-1 rounded-lg border border-surface-container"
        >
          <button
            type="button"
            @click="emit('prevMonth')"
            class="w-7 h-7 rounded flex items-center justify-center text-primary hover:bg-surface-container-high transition-colors cursor-pointer"
            title="Mes anterior"
            aria-label="Mes anterior"
          >
            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
          </button>

          <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-primary text-[16px]">calendar_month</span>
            <select
              :value="selectedMonth"
              @change="emit('update:selectedMonth', Number(($event.target as HTMLSelectElement).value))"
              aria-label="Seleccionar mes del calendario"
              class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
            >
              <option v-for="m in availableMonths" :key="m.value" :value="m.value">
                {{ m.label }}
              </option>
            </select>
            <select
              :value="selectedYear"
              @change="emit('update:selectedYear', Number(($event.target as HTMLSelectElement).value))"
              aria-label="Seleccionar año del calendario"
              class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
            >
              <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>

          <button
            type="button"
            @click="emit('nextMonth')"
            class="w-7 h-7 rounded flex items-center justify-center text-primary hover:bg-surface-container-high transition-colors cursor-pointer"
            title="Mes siguiente"
            aria-label="Mes siguiente"
          >
            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
          </button>
        </div>

        <!-- Botón Reservar Eventos Especiales -->
        <button
          v-if="currentMonthEvents.length > 0"
          type="button"
          class="inline-flex items-center gap-1 px-space-md py-space-xs rounded-lg bg-purple-700 text-white hover:bg-purple-800 text-label-md font-label-md transition-all font-bold cursor-pointer shadow-xs animate-in fade-in duration-150"
          title="Vincular fechas de este mes a eventos provinciales con tarifa diferenciada"
          @click="emit('openSpecialEvents')"
        >
          <span class="material-symbols-outlined text-[18px]">festival</span>
          <span>Reservar Eventos Especiales</span>
        </button>

        <!-- Botón Bloquear Fechas -->
        <button
          type="button"
          class="inline-flex items-center gap-1 px-space-md py-space-xs rounded-lg bg-surface-container-low hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors font-semibold cursor-pointer border border-surface-container"
          title="Bloquear fechas para Uso Familiar, Mantenimiento o Reservas Particulares Fuera de Plataforma"
          @click="emit('openInternalBlocks')"
        >
          <span class="material-symbols-outlined text-[18px]">event_busy</span>
          Bloquear Fechas / Uso Particular
        </button>
      </div>
    </div>

    <!-- BANNER DE EVENTOS PROVINCIALES EN EL MES SELECCIONADO -->
    <div
      v-if="currentMonthEvents.length > 0"
      class="p-space-sm rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm"
    >
      <div class="flex items-center gap-space-sm">
        <div class="w-9 h-9 rounded-lg bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-xs">
          <span class="material-symbols-outlined text-[20px]">festival</span>
        </div>
        <div>
          <div class="flex items-center gap-1.5 flex-wrap">
            <span class="font-label-md text-label-md font-bold text-purple-950 dark:text-purple-100">
              Eventos Provinciales con Alta Demanda en este Mes:
            </span>
            <span
              v-for="ev in currentMonthEvents"
              :key="ev.id"
              class="px-2 py-0.5 rounded-full bg-purple-200/70 dark:bg-purple-900/60 text-purple-900 dark:text-purple-200 text-xs font-bold"
            >
              {{ ev.title }} ({{ ev.startDate.split('-')[2] }}/{{ ev.startDate.split('-')[1] }} al
              {{ ev.endDate.split('-')[2] }}/{{ ev.endDate.split('-')[1] }})
            </span>
          </div>
          <p class="font-body-xs text-[11px] text-purple-800 dark:text-purple-300">
            Impactan sobre {{ selectedOfferDepartment || 'San Juan' }}. Puede bloquearlos para alquiler especial con
            pernocte y tarifa diferenciada.
          </p>
        </div>
      </div>

      <button
        type="button"
        @click="emit('openSpecialEvents')"
        class="px-space-md py-1.5 rounded-lg bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold shrink-0 cursor-pointer shadow-xs"
      >
        Configurar Fechas del Evento
      </button>
    </div>

    <!-- Leyenda de colores del calendario -->
    <div
      class="flex items-center gap-space-md flex-wrap p-space-sm bg-surface-container-low rounded-lg text-label-sm font-label-sm border border-surface-container"
    >
      <div class="flex items-center gap-1.5">
        <div class="w-3.5 h-3.5 rounded-full bg-secondary-container flex items-center justify-center">
          <span class="material-symbols-outlined text-on-secondary-container text-[10px]">check</span>
        </div>
        <span class="text-on-surface">Disponible para Alquiler</span>
      </div>
      <div class="flex items-center gap-1.5">
        <div class="w-3.5 h-3.5 rounded-full bg-primary-container flex items-center justify-center">
          <span class="material-symbols-outlined text-on-primary text-[10px]">person</span>
        </div>
        <span class="text-on-surface">Reservado / Alquilado</span>
      </div>
      <!-- Morado: Evento Especial -->
      <div class="flex items-center gap-1.5">
        <div class="w-3.5 h-3.5 rounded-full bg-purple-600 flex items-center justify-center shadow-2xs">
          <span class="material-symbols-outlined text-white text-[10px]">festival</span>
        </div>
        <span class="text-on-surface font-bold text-purple-900 dark:text-purple-200">Evento Especial</span>
      </div>
      <div class="flex items-center gap-1.5">
        <div class="w-3.5 h-3.5 rounded-full bg-amber-200 border border-amber-400 flex items-center justify-center">
          <span class="material-symbols-outlined text-amber-950 text-[10px]">block</span>
        </div>
        <span class="text-on-surface font-semibold text-amber-900 dark:text-amber-300">Bloqueado</span>
      </div>
      <div class="flex items-center gap-1.5">
        <div class="w-3.5 h-3.5 rounded-full bg-tertiary-fixed-dim flex items-center justify-center">
          <span class="material-symbols-outlined text-tertiary text-[10px]">cottage</span>
        </div>
        <span class="text-on-surface">Uso Familiar / Mantenimiento</span>
      </div>
    </div>

    <!-- GRID DEL CALENDARIO -->
    <div class="w-full overflow-x-auto">
      <div class="min-w-[760px]">
        <!-- Encabezados de días -->
        <div
          class="grid grid-cols-7 gap-2 pb-2 text-center font-label-md text-label-md text-on-surface-variant font-bold"
        >
          <span>Lun</span>
          <span>Mar</span>
          <span>Mié</span>
          <span>Jue</span>
          <span>Vie</span>
          <span class="text-secondary font-bold">Sáb</span>
          <span class="text-secondary font-bold">Dom</span>
        </div>

        <!-- Cuadrícula de días -->
        <div class="grid grid-cols-7 gap-2">
          <div
            v-for="cDay in calendarDays"
            :key="cDay.day"
            :style="cDay.day === 1 ? { gridColumnStart: firstDayOffset } : {}"
            class="calendar-cell h-24 p-2 rounded-xl flex flex-col justify-between transition-all select-none"
            :class="{
              'bg-surface-variant/40 text-on-surface-variant/70 border border-surface-container opacity-60 cursor-not-allowed':
                cDay.isPast &&
                cDay.status !== 'booked' &&
                cDay.status !== 'maintenance' &&
                cDay.status !== 'blocked' &&
                cDay.status !== 'special_event',
              'bg-purple-100 text-purple-950 border-2 border-purple-400 dark:bg-purple-950/60 dark:text-purple-200 dark:border-purple-600 hover:bg-purple-200/90 cursor-pointer shadow-xs':
                cDay.status === 'special_event',
              'bg-primary text-on-primary shadow-xs hover:opacity-95 cursor-pointer': cDay.status === 'booked',
              'bg-secondary-container/50 text-on-surface hover:bg-secondary-container cursor-pointer':
                cDay.status === 'available' && !cDay.isPast,
              'bg-amber-100 text-amber-950 border border-amber-300 dark:bg-amber-950/40 dark:text-amber-200 dark:border-amber-800 hover:bg-amber-200/80 cursor-pointer':
                cDay.status === 'unconfigured',
              'bg-tertiary-fixed text-tertiary-container hover:bg-tertiary-fixed-dim cursor-pointer':
                cDay.status === 'maintenance',
              'bg-surface-variant text-on-surface-variant hover:bg-surface-container-high cursor-pointer':
                cDay.status === 'blocked',
            }"
            @click="emit('toggleCellState', cDay.day)"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-lg text-label-lg font-bold">{{ cDay.day }}</span>
              <span
                class="material-symbols-outlined text-[16px]"
                :class="{
                  'text-purple-700 dark:text-purple-300 font-bold': cDay.status === 'special_event',
                  'text-secondary': cDay.status === 'available' && !cDay.isPast,
                  'text-amber-700 dark:text-amber-300': cDay.status === 'unconfigured',
                  'text-tertiary': cDay.status === 'maintenance',
                }"
              >
                {{
                  cDay.icon ||
                  (cDay.status === 'special_event'
                    ? 'festival'
                    : cDay.status === 'available'
                      ? 'check_circle'
                      : cDay.status === 'unconfigured'
                        ? 'block'
                        : cDay.isPast
                          ? 'schedule'
                          : 'event')
                }}
              </span>
            </div>
            <div class="text-[11px] leading-tight truncate">
              <p
                class="font-bold truncate"
                :class="{
                  'text-purple-900 dark:text-purple-100': cDay.status === 'special_event',
                  'text-amber-900 dark:text-amber-200': cDay.status === 'unconfigured',
                }"
              >
                {{
                  cDay.title ||
                  (cDay.status === 'special_event'
                    ? 'Evento FNS'
                    : cDay.isPast
                      ? 'Concluido'
                      : cDay.status === 'available'
                        ? 'Disponible'
                        : cDay.status === 'unconfigured'
                          ? 'Bloqueado'
                          : 'Bloqueado')
                }}
              </p>
              <p
                v-if="cDay.subtitle"
                class="opacity-90 truncate text-[10px]"
                :class="{ 'text-purple-800 dark:text-purple-300 font-bold': cDay.status === 'special_event' }"
              >
                {{ cDay.subtitle }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
