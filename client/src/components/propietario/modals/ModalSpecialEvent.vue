<script setup lang="ts">
import type { ProvincialEvent, PropietarioOferta } from '../../../types/propietario'

defineProps<{
  modelValue: boolean
  selectedOffer: PropietarioOferta
  relevantEventsForOffer: ProvincialEvent[]
  selectedSpecialEventDef?: ProvincialEvent
  specialEventForm: {
    selectedEventId: number
    startDate: string
    endDate: string
    rate: number
    allowsOvernight: boolean
  }
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'apply'): void
}>()

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 bg-primary/40 backdrop-blur-xs z-50 flex items-center justify-center p-space-md overflow-y-auto"
    @click.self="close"
  >
    <div
      class="bg-surface-container-lowest rounded-2xl max-w-xl w-full border border-purple-300 dark:border-purple-800 shadow-2xl flex flex-col max-h-[92vh] overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150"
    >
      <!-- Header del Modal -->
      <div
        class="p-space-lg border-b border-surface-container flex items-center justify-between bg-purple-50 dark:bg-purple-950/40"
      >
        <div class="flex items-center gap-space-sm">
          <div class="w-10 h-10 rounded-xl bg-purple-700 text-white flex items-center justify-center shadow-xs">
            <span class="material-symbols-outlined text-[22px]">festival</span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-purple-950 dark:text-purple-100 font-bold">
              Reservar para Eventos Especiales de San Juan
            </h3>
            <p class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300">
              Tarifa diferenciada y gestión de pernocte por alta demanda provincial
            </p>
          </div>
        </div>
        <button
          type="button"
          aria-label="Cerrar modal de eventos especiales"
          class="w-8 h-8 rounded-lg flex items-center justify-center text-purple-900 dark:text-purple-300 hover:bg-purple-200/50 transition-colors cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="p-space-lg overflow-y-auto flex flex-col gap-space-md">
        <!-- Selector de Evento Provincial -->
        <div>
          <label class="block font-label-md text-label-md text-primary font-bold mb-1">
            Seleccionar Evento Provincial Oficial:
          </label>
          <select
            v-model="specialEventForm.selectedEventId"
            class="w-full px-3 py-2.5 rounded-lg bg-surface-container-low text-primary font-label-md text-label-md font-bold focus:outline-none focus:ring-2 focus:ring-purple-600 border border-surface-container cursor-pointer"
          >
            <option v-for="ev in relevantEventsForOffer" :key="ev.id" :value="ev.id">
              {{ ev.title }} ({{ ev.startDate }} al {{ ev.endDate }}) • Impacto: {{ ev.impactDepartments.join(', ') }}
            </option>
          </select>
          <span class="font-body-xs text-[11px] text-on-surface-variant block mt-1">
            Mostrando eventos configurados institucionalmente que impactan en {{ selectedOffer.department }} o toda la
            provincia.
          </span>
        </div>

        <!-- Tarjeta Informativa del Evento Seleccionado -->
        <div
          v-if="selectedSpecialEventDef"
          class="p-space-sm rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-1.5"
        >
          <div class="flex items-center justify-between">
            <span class="font-label-md text-label-md font-bold text-primary">{{ selectedSpecialEventDef.title }}</span>
            <span
              class="px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-200 text-xs font-bold border border-purple-200 dark:border-purple-800"
            >
              {{ selectedSpecialEventDef.venue }}
            </span>
          </div>
          <p class="text-xs text-on-surface-variant leading-relaxed">
            {{ selectedSpecialEventDef.description }}
          </p>
          <div
            class="text-[11px] text-purple-800 dark:text-purple-300 font-medium pt-1 border-t border-surface-container/60 flex items-center gap-1"
          >
            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
            <span>
              Fechas fijadas: {{ selectedSpecialEventDef.startDate }} hasta {{ selectedSpecialEventDef.endDate }}
            </span>
          </div>
        </div>

        <!-- Rango de Fechas a Vincular -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
          <div>
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Desde</label>
            <input
              v-model="specialEventForm.startDate"
              type="date"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container"
            />
          </div>
          <div>
            <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Hasta</label>
            <input
              v-model="specialEventForm.endDate"
              type="date"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container"
            />
          </div>
        </div>

        <!-- Tarifa Especial y Pernocte -->
        <div
          class="p-space-md rounded-xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-200 dark:border-purple-800/60 flex flex-col gap-space-sm"
        >
          <div>
            <label class="block font-label-md text-label-md text-purple-950 dark:text-purple-200 font-bold mb-1">
              Tarifa Especial por Jornada / Noche (ARS):
            </label>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">
                $
              </span>
              <input
                v-model.number="specialEventForm.rate"
                type="number"
                step="1000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-600 border border-purple-200 dark:border-purple-700"
              />
            </div>
            <span class="font-body-xs text-[11px] text-purple-800 dark:text-purple-300 block mt-1">
              Tarifa sugerida con multiplicador provincial aplicado según demanda.
            </span>
          </div>

          <!-- Opción de Pernocte / Camas para dormir tras el evento -->
          <label
            class="flex items-start gap-2.5 cursor-pointer pt-2 border-t border-purple-200/60 dark:border-purple-800/40 select-none"
          >
            <input
              v-model="specialEventForm.allowsOvernight"
              type="checkbox"
              class="mt-1 w-4 h-4 rounded text-purple-700 focus:ring-purple-500 border-surface-container"
            />
            <div>
              <span
                class="font-label-md text-label-md font-bold text-purple-950 dark:text-purple-200 flex items-center gap-1"
              >
                <span class="material-symbols-outlined text-[16px]">bed</span>
                Habilitar Pernocte (Camas para dormir tras el evento)
              </span>
              <span class="font-body-xs text-[11px] text-on-surface-variant block">
                Permite al visitante pernoctar después del festival, recital o carrera del Villicum en lugar de limitarse a jornada diurna.
              </span>
            </div>
          </label>
        </div>

        <div
          class="p-space-sm rounded-lg bg-surface-container flex items-start gap-2 text-xs text-on-surface-variant"
        >
          <span class="material-symbols-outlined text-primary text-[18px] shrink-0">info</span>
          <span>
            Al vincular, los días del rango se destacarán en
            <strong class="text-purple-800 dark:text-purple-300">color morado</strong> en el calendario y el buscador
            mostrará esta tarifa diferenciada a quienes busquen hospedaje para esas fechas.
          </span>
        </div>
      </div>

      <!-- Footer del Modal -->
      <div
        class="p-space-md border-t border-surface-container flex items-center justify-end gap-space-sm bg-surface-container-low"
      >
        <button
          type="button"
          class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold transition-colors cursor-pointer"
          @click="close"
        >
          Cancelar
        </button>
        <button
          type="button"
          class="inline-flex items-center gap-1.5 px-space-lg py-space-sm rounded-lg bg-purple-700 text-white font-label-lg text-label-lg hover:bg-purple-800 transition-all font-bold cursor-pointer shadow-sm"
          @click="emit('apply')"
        >
          <span class="material-symbols-outlined text-[18px]">check</span>
          Vincular al Calendario
        </button>
      </div>
    </div>
  </div>
</template>
