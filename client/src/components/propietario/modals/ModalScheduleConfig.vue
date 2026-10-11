<script setup lang="ts">
import type { PropietarioOferta } from '../../../types/propietario'

defineProps<{
  modelValue: boolean
  selectedOffer: PropietarioOferta
  weekDayDefs: Array<{ id: number; letter: string; fullName: string }>
  calculateHoursDuration: (start: string, end: string) => string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'toggleActiveDay', dayId: number): void
  (e: 'setSchedulePreset', preset: 'all' | 'thu-sun' | 'fri-sun'): void
}>()

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-scrim/40 backdrop-blur-xs"
    @click.self="close"
  >
    <div
      class="w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-xl border border-surface-container overflow-hidden flex flex-col max-h-[90vh]"
    >
      <!-- Encabezado del modal -->
      <div
        class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low"
      >
        <div class="flex items-center gap-space-xs">
          <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">tune</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Configurar Franja Diurna y Días</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant truncate max-w-xs">{{ selectedOffer.title }}</p>
          </div>
        </div>
        <button
          type="button"
          aria-label="Cerrar modal de configuración"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="p-space-md overflow-y-auto flex flex-col gap-space-md">
        <!-- Franja Horaria -->
        <div>
          <label class="block font-label-md text-label-md text-primary font-bold mb-2">
            Horario de Atención Diurna
          </label>
          <div class="grid grid-cols-2 gap-space-sm">
            <div>
              <span class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Apertura (Check-in)</span>
              <input
                v-model="selectedOffer.schedule.start"
                type="time"
                required
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container font-semibold"
              />
            </div>
            <div>
              <span class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Cierre (Check-out)</span>
              <input
                v-model="selectedOffer.schedule.end"
                type="time"
                required
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container font-semibold"
              />
            </div>
          </div>
          <p class="mt-1.5 font-label-sm text-label-sm text-on-surface-variant">
            Duración total de jornada:
            <span class="font-bold text-primary">
              {{ calculateHoursDuration(selectedOffer.schedule.start, selectedOffer.schedule.end) }} horas
            </span>
            por turno homologado.
          </p>
        </div>

        <!-- Días Ofrecidos -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <label class="block font-label-md text-label-md text-primary font-bold">
              Días de la Semana Habilitados
            </label>
            <div class="flex items-center gap-1">
              <button
                type="button"
                @click="emit('setSchedulePreset', 'all')"
                class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
              >
                Todos
              </button>
              <span class="text-outline text-xs">•</span>
              <button
                type="button"
                @click="emit('setSchedulePreset', 'thu-sun')"
                class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
              >
                Jue-Dom
              </button>
              <span class="text-outline text-xs">•</span>
              <button
                type="button"
                @click="emit('setSchedulePreset', 'fri-sun')"
                class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
              >
                Vie-Dom
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            <button
              v-for="day in weekDayDefs"
              :key="day.id"
              type="button"
              @click="emit('toggleActiveDay', day.id)"
              class="p-2.5 rounded-lg border text-left flex items-center justify-between transition-all cursor-pointer"
              :class="
                selectedOffer.schedule.activeDays.includes(day.id)
                  ? 'bg-primary-container/20 border-primary text-primary font-bold'
                  : 'bg-surface-container-low border-surface-container text-on-surface-variant opacity-75'
              "
            >
              <span class="text-xs">{{ day.fullName }}</span>
              <span class="material-symbols-outlined text-[16px]">
                {{ selectedOffer.schedule.activeDays.includes(day.id) ? 'check_circle' : 'do_not_disturb_on' }}
              </span>
            </button>
          </div>
        </div>

        <!-- Aviso de impacto en calendario -->
        <div
          class="p-space-sm rounded-lg bg-amber-50 border border-amber-300 dark:bg-amber-950/40 dark:border-amber-800 flex items-start gap-space-xs text-xs text-amber-950 dark:text-amber-200"
        >
          <span class="material-symbols-outlined text-amber-700 dark:text-amber-300 text-[18px] shrink-0 mt-0.5">
            info
          </span>
          <div>
            <p class="font-bold">Impacto directo en el Calendario:</p>
            <p>
              Los días que no estén habilitados se mostrarán automáticamente con el estado
              <span class="font-bold text-amber-900 dark:text-amber-200 underline">Bloqueado</span> (color naranja) en
              el almanaque. Los días con reservas previas o fechas ya concluidas mantendrán su registro legal intacto.
            </p>
          </div>
        </div>
      </div>

      <!-- Acciones del modal -->
      <div
        class="p-space-md border-t border-surface-container flex items-center justify-end gap-space-sm bg-surface-container-low"
      >
        <button
          type="button"
          class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold transition-colors cursor-pointer"
          @click="close"
        >
          Cerrar
        </button>
        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-space-lg py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container hover:text-on-primary-container transition-all font-bold cursor-pointer shadow-sm"
          @click="close"
        >
          <span class="material-symbols-outlined text-[18px]">check</span>
          Aplicar y Sincronizar
        </button>
      </div>
    </div>
  </div>
</template>
