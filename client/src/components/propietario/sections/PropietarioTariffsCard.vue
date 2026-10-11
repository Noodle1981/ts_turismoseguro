<script setup lang="ts">
import type { PropietarioOferta } from '../../../types/propietario'

defineProps<{
  selectedOffer: PropietarioOferta
  tariffsTab: 'diurno' | 'pernocte' | 'fiestas'
  tarifaSavedFeedback: boolean
  weekDayDefs: Array<{ id: number; letter: string; fullName: string }>
  calculateHoursDuration: (start: string, end: string) => string
}>()

const emit = defineEmits<{
  (e: 'update:tariffsTab', tab: 'diurno' | 'pernocte' | 'fiestas'): void
  (e: 'saveTarifas'): void
  (e: 'openScheduleModal'): void
  (e: 'toggleActiveDay', dayId: number): void
}>()
</script>

<template>
  <section
    class="lg:col-span-8 bg-surface-container-lowest rounded-xl p-space-md shadow-xs flex flex-col justify-between gap-space-md border border-surface-container"
  >
    <div>
      <div class="flex items-center justify-between pb-space-xs">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-primary text-[22px]">price_change</span>
          <h2 class="font-headline-sm text-headline-sm text-primary font-bold">
            Gestión de Oferta y Tarifas Diurnas
          </h2>
        </div>

        <!-- Switch Estado de Oferta Compacto -->
        <div
          class="flex items-center gap-space-xs bg-surface-container-low px-space-sm py-1 rounded-lg border border-surface-container"
        >
          <span class="font-label-sm text-label-sm text-on-surface font-semibold">Buscador:</span>
          <label class="relative inline-flex items-center cursor-pointer">
            <input v-model="selectedOffer.isListingVisible" type="checkbox" class="sr-only peer" />
            <div
              class="w-9 h-5 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-secondary"
            ></div>
          </label>
          <span
            class="font-label-sm text-label-sm font-bold"
            :class="selectedOffer.isListingVisible ? 'text-secondary' : 'text-outline'"
          >
            {{ selectedOffer.isListingVisible ? 'Activo' : 'Pausado' }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-12 gap-space-sm pt-space-xs">
        <!-- Property Snapshot Compacto -->
        <div class="md:col-span-4 relative rounded-xl overflow-hidden shadow-xs group self-stretch flex items-center bg-surface-container-low">
          <img
            :src="selectedOffer.image"
            :alt="selectedOffer.title"
            width="400"
            height="224"
            loading="lazy"
            decoding="async"
            class="w-full h-40 md:h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 min-h-[140px]"
          />
          <div class="absolute top-2 left-2 z-10">
            <span
              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-lowest/95 backdrop-blur-md text-secondary font-label-sm text-label-sm shadow-xs font-bold"
            >
              <span class="material-symbols-outlined text-[13px]">verified</span>
              Verificado
            </span>
          </div>
        </div>

        <!-- Features homologadas Compactas -->
        <div class="md:col-span-8 flex flex-col justify-between gap-space-xs">
          <div class="grid grid-cols-2 gap-space-xs">
            <div
              class="p-2 rounded-lg bg-surface-container-low flex items-center gap-2 border border-surface-container"
            >
              <div
                class="w-7 h-7 rounded-md bg-surface-container-highest flex items-center justify-center text-primary shrink-0"
              >
                <span class="material-symbols-outlined text-[16px]">groups</span>
              </div>
              <div>
                <span class="font-label-sm text-label-sm text-on-surface-variant block text-[11px] leading-tight">Aforo Homologado</span>
                <span class="font-label-md text-label-md text-primary font-bold">Hasta 50 personas</span>
              </div>
            </div>

            <div
              class="p-2 rounded-lg bg-surface-container-low flex flex-col justify-between gap-1 border border-surface-container"
            >
              <!-- Horario de Franja Diurna -->
              <div class="flex items-center justify-between gap-1">
                <div class="flex items-center gap-1.5 min-w-0">
                  <div
                    class="w-6 h-6 rounded-md bg-surface-container-highest flex items-center justify-center text-tertiary shrink-0"
                  >
                    <span class="material-symbols-outlined text-[15px]">schedule</span>
                  </div>
                  <div class="truncate">
                    <span class="font-label-md text-label-md text-primary font-bold whitespace-nowrap block">
                      {{ selectedOffer.schedule.start }} a {{ selectedOffer.schedule.end }} hs
                    </span>
                  </div>
                </div>
                <button
                  type="button"
                  @click="emit('openScheduleModal')"
                  class="p-1 rounded bg-surface-container-highest hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-bold border border-surface-container flex items-center justify-center cursor-pointer transition-colors"
                  title="Configurar horarios y días que se ofrece"
                >
                  <span class="material-symbols-outlined text-[14px]">tune</span>
                </button>
              </div>

              <!-- Selector rápido de días ofrecidos -->
              <div class="pt-0.5 border-t border-surface-container/60 flex items-center justify-between gap-1">
                <span class="text-[10px] text-on-surface-variant font-medium">Días:</span>
                <div class="flex items-center gap-0.5">
                  <button
                    v-for="day in weekDayDefs"
                    :key="day.id"
                    type="button"
                    @click="emit('toggleActiveDay', day.id)"
                    class="w-4.5 h-4.5 rounded text-[9px] font-bold flex items-center justify-center transition-all cursor-pointer"
                    :class="
                      selectedOffer.schedule.activeDays.includes(day.id)
                        ? 'bg-primary text-on-primary shadow-2xs'
                        : 'bg-amber-100 text-amber-900 border border-amber-300 dark:bg-amber-950/60 dark:text-amber-200 line-through opacity-75'
                    "
                    :title="
                      day.fullName +
                      (selectedOffer.schedule.activeDays.includes(day.id) ? ' (Habilitado)' : ' (Bloqueado)')
                    "
                  >
                    {{ day.letter }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Amenity Badges Parametrizados Dinámicos -->
          <div class="flex flex-wrap gap-1 py-0.5">
            <span
              v-for="amenity in selectedOffer.amenities"
              :key="amenity.label"
              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container"
            >
              <span class="material-symbols-outlined text-secondary text-[13px]">{{ amenity.icon }}</span>
              {{ amenity.label }}
            </span>
          </div>

          <div
            class="bg-surface-container px-2 py-1 rounded-lg text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5"
          >
            <span class="material-symbols-outlined text-[15px] text-primary shrink-0">info</span>
            <span class="truncate">Aforo y sonido diurno regulados por Ley Provincial de Hospedaje Rural.</span>
          </div>
        </div>
      </div>

      <!-- TARIFAS EDITABLES MULTIMODALES COMPACTAS -->
      <form class="mt-space-sm flex flex-col gap-space-sm" @submit.prevent="emit('saveTarifas')">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Gestión de Tarifas (ARS)</h3>
            <p class="font-label-sm text-label-sm text-on-surface-variant">
              Precios según modalidad: Diurno, Pernocte o Fiestas Nocturnas
            </p>
          </div>

          <!-- Selector de Pestañas de Tarifas Compacto -->
          <div
            class="flex items-center gap-0.5 bg-surface-container-low p-0.5 rounded-lg border border-surface-container self-start sm:self-auto"
          >
            <button
              type="button"
              @click="emit('update:tariffsTab', 'diurno')"
              class="px-2.5 py-1 rounded-md text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'diurno'
                  ? 'bg-secondary text-on-secondary shadow-2xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[15px]">wb_sunny</span>
              <span>Diurnas</span>
            </button>

            <button
              type="button"
              @click="emit('update:tariffsTab', 'pernocte')"
              class="px-2.5 py-1 rounded-md text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'pernocte'
                  ? 'bg-primary text-on-primary shadow-2xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[15px]">hotel</span>
              <span>Pernocte</span>
            </button>

            <button
              type="button"
              @click="emit('update:tariffsTab', 'fiestas')"
              class="px-2.5 py-1 rounded-md text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'fiestas'
                  ? 'bg-purple-700 text-white shadow-2xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[15px]">nightlife</span>
              <span>Fiestas</span>
            </button>
          </div>
        </div>

        <!-- TAB 1: TARIFAS DIURNAS DE TEMPORADA -->
        <div
          v-if="tariffsTab === 'diurno'"
          class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm animate-in fade-in duration-150"
        >
          <!-- Tarifa Diurna 1 -->
          <div
            class="p-2.5 rounded-lg bg-surface-container-low flex flex-col gap-1 border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Lunes a Jueves</span>
              <span class="material-symbols-outlined text-[16px] text-on-surface-variant">wb_sunny</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-on-surface-variant font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.lunesJueves"
                type="number"
                step="1000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant text-[11px]">
              Jornada base diurna ({{
                calculateHoursDuration(selectedOffer.schedule.start, selectedOffer.schedule.end)
              }}h)
            </span>
          </div>

          <!-- Tarifa Diurna 2 -->
          <div
            class="p-2.5 rounded-lg bg-surface-container-low flex flex-col gap-1 border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Viernes y Domingos</span>
              <span class="material-symbols-outlined text-[16px] text-tertiary">celebration</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-on-surface-variant font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.viernesDomingo"
                type="number"
                step="1000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant text-[11px]">Media demanda diurna</span>
          </div>

          <!-- Tarifa Diurna 3 -->
          <div
            class="p-2.5 rounded-lg bg-surface-container-low flex flex-col gap-1 border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Sábados y Feriados</span>
              <span class="material-symbols-outlined text-[16px] text-secondary">star</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-on-surface-variant font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.sabadoFeriados"
                type="number"
                step="1000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-secondary font-bold text-[11px]">Alta demanda fin de semana</span>
          </div>
        </div>

        <!-- TAB 2: TARIFAS DE ESTADÍA Y PERNOCTE -->
        <div
          v-else-if="tariffsTab === 'pernocte'"
          class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm animate-in fade-in duration-150"
        >
          <!-- Pernocte 1 -->
          <div class="p-2.5 rounded-lg bg-primary/5 border border-primary/20 flex flex-col gap-1">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">x Noche en Camas</span>
              <span class="material-symbols-outlined text-[16px] text-primary">bed</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-primary font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.pernocteCamas"
                type="number"
                step="1000"
                placeholder="65000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant text-[11px]">Cabaña y dormitorios homologados</span>
          </div>

          <!-- Pernocte 2 -->
          <div class="p-2.5 rounded-lg bg-primary/5 border border-primary/20 flex flex-col gap-1">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">x Noche en Carpas</span>
              <span class="material-symbols-outlined text-[16px] text-primary">camping</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-primary font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.pernocteCarpas"
                type="number"
                step="1000"
                placeholder="40000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant text-[11px]">Predio cerrado y baños habilitados</span>
          </div>

          <!-- Pernocte 3 -->
          <div class="p-2.5 rounded-lg bg-primary/5 border border-primary/20 flex flex-col gap-1">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">Finde Completo</span>
              <span class="material-symbols-outlined text-[16px] text-secondary">date_range</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-primary font-label-md text-label-md font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.findeCompleto"
                type="number"
                step="1000"
                placeholder="110000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-secondary font-bold text-[11px]">Paquete 3 días / 2 noches</span>
          </div>
        </div>

        <!-- TAB 3: TARIFAS DE FIESTAS Y EVENTOS GRANDES -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm animate-in fade-in duration-150">
          <div
            class="p-2.5 rounded-lg bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-1 border-2 border-purple-300 dark:border-purple-800 shadow-2xs"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">
                Fiesta Nocturna (15 años / Bodas)
              </span>
              <span class="material-symbols-outlined text-[16px] text-purple-700 dark:text-purple-300">nightlife</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-purple-800 dark:text-purple-300 font-label-md text-label-md font-bold">
                $
              </span>
              <input
                v-model.number="selectedOffer.tariffs.fiestaNocturna"
                type="number"
                step="1000"
                placeholder="120000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
              />
            </div>
            <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold text-[11px]">
              Franja nocturna hasta 05:00 hs • Potencia y limpieza
            </span>
          </div>

          <div
            class="p-2.5 rounded-lg bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-1 border-2 border-purple-300 dark:border-purple-800 shadow-2xs"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">
                Eventos Especiales Provinciales
              </span>
              <span class="material-symbols-outlined text-[16px] text-purple-700 dark:text-purple-300">festival</span>
            </div>
            <div class="relative">
              <span class="absolute left-2.5 top-2 text-purple-800 dark:text-purple-300 font-label-md text-label-md font-bold">
                $
              </span>
              <input
                v-model.number="selectedOffer.tariffs.eventoEspecial"
                type="number"
                step="1000"
                class="w-full pl-6 pr-2 py-1.5 rounded-md bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
              />
            </div>
            <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold text-[11px]">
              Alta demanda en la zona (FNS / Pernocte masivo)
            </span>
          </div>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
          <span
            class="font-label-sm text-label-sm flex items-center gap-1 font-semibold text-[11px]"
            :class="tarifaSavedFeedback ? 'text-secondary font-bold' : 'text-on-surface-variant'"
          >
            <span class="material-symbols-outlined text-[15px] text-secondary">
              {{ tarifaSavedFeedback ? 'done_all' : 'check_circle' }}
            </span>
            {{
              tarifaSavedFeedback
                ? '¡Tarifas actualizadas y sincronizadas!'
                : 'Tarifas sincronizadas con la Red Oficial'
            }}
          </span>

          <button
            type="submit"
            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-all shadow-xs font-bold cursor-pointer"
          >
            <span class="material-symbols-outlined text-[16px]">save</span>
            Guardar Tarifas
          </button>
        </div>
      </form>
    </div>
  </section>
</template>
