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
    class="lg:col-span-8 bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col justify-between gap-space-lg border border-surface-container"
  >
    <div>
      <div class="flex items-center justify-between pb-space-md">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-primary text-[24px]">price_change</span>
          <h2 class="font-headline-md text-headline-md text-primary font-bold">
            Gestión de Oferta y Tarifas Diurnas
          </h2>
        </div>

        <!-- Switch Estado de Oferta -->
        <div
          class="flex items-center gap-space-sm bg-surface-container-low px-space-md py-space-xs rounded-xl border border-surface-container"
        >
          <span class="font-label-sm text-label-sm text-on-surface font-semibold">Visible en Buscador:</span>
          <label class="relative inline-flex items-center cursor-pointer">
            <input v-model="selectedOffer.isListingVisible" type="checkbox" class="sr-only peer" />
            <div
              class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"
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

      <div class="grid grid-cols-1 md:grid-cols-12 gap-space-md pt-space-xs">
        <!-- Property Snapshot with Badge -->
        <div class="md:col-span-5 relative rounded-xl overflow-hidden shadow-sm group">
          <img
            :src="selectedOffer.image"
            :alt="selectedOffer.title"
            width="400"
            height="224"
            loading="lazy"
            decoding="async"
            class="w-full h-56 object-cover object-center group-hover:scale-105 transition-transform duration-500"
          />
          <div class="absolute top-2 left-2 z-10">
            <span
              class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-lowest/95 backdrop-blur-md text-secondary font-label-sm text-label-sm shadow-sm font-bold"
            >
              <span class="material-symbols-outlined text-[14px]">verified</span>
              Verificado In Situ
            </span>
          </div>
          <div
            class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent p-space-sm text-on-primary"
          >
            <p class="font-label-sm text-label-sm text-primary-fixed">{{ selectedOffer.department }}</p>
            <p class="font-headline-sm text-headline-sm truncate text-white font-bold">{{ selectedOffer.subtitle }}</p>
          </div>
        </div>

        <!-- Features homologadas -->
        <div class="md:col-span-7 flex flex-col justify-between gap-space-sm">
          <div class="grid grid-cols-2 gap-space-sm">
            <div
              class="p-space-sm rounded-lg bg-surface-container-low flex items-center gap-space-xs border border-surface-container"
            >
              <div
                class="w-8 h-8 rounded-md bg-surface-container-highest flex items-center justify-center text-primary"
              >
                <span class="material-symbols-outlined text-[18px]">groups</span>
              </div>
              <div>
                <span class="font-label-sm text-label-sm text-on-surface-variant block">Aforo Homologado</span>
                <span class="font-label-lg text-label-lg text-primary font-bold">Hasta 50 personas</span>
              </div>
            </div>

            <div
              class="p-space-sm rounded-lg bg-surface-container-low flex flex-col justify-between gap-space-xs border border-surface-container"
            >
              <!-- Horario de Franja Diurna -->
              <div class="flex items-center gap-space-xs">
                <div
                  class="w-8 h-8 rounded-md bg-surface-container-highest flex items-center justify-center text-tertiary shrink-0"
                >
                  <span class="material-symbols-outlined text-[18px]">schedule</span>
                </div>
                <div class="min-w-0">
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Franja Diurna</span>
                  <span class="font-label-lg text-label-lg text-primary font-bold whitespace-nowrap block">
                    {{ selectedOffer.schedule.start }} a {{ selectedOffer.schedule.end }} hs
                  </span>
                </div>
              </div>

              <!-- Selector rápido de días ofrecidos -->
              <div class="pt-1 border-t border-surface-container/60 flex items-center justify-between gap-1">
                <span class="text-[11px] text-on-surface-variant font-medium">Días ofrecidos:</span>
                <div class="flex items-center gap-1">
                  <button
                    v-for="day in weekDayDefs"
                    :key="day.id"
                    type="button"
                    @click="emit('toggleActiveDay', day.id)"
                    class="w-5 h-5 rounded text-[10px] font-bold flex items-center justify-center transition-all cursor-pointer"
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

              <!-- Botón de configuración debajo de la tarjeta -->
              <button
                type="button"
                @click="emit('openScheduleModal')"
                class="w-full mt-0.5 py-1 px-2 rounded bg-surface-container-highest hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-bold border border-surface-container flex items-center justify-center gap-1 cursor-pointer transition-colors"
                title="Configurar horarios y días que se ofrece"
              >
                <span class="material-symbols-outlined text-[14px]">tune</span>
                <span>Configurar</span>
              </button>
            </div>
          </div>

          <!-- Amenity Badges Parametrizados Dinámicos -->
          <div class="flex flex-wrap gap-1.5">
            <span
              v-for="amenity in selectedOffer.amenities"
              :key="amenity.label"
              class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container"
            >
              <span class="material-symbols-outlined text-secondary text-[14px]">{{ amenity.icon }}</span>
              {{ amenity.label }}
            </span>
          </div>

          <div
            class="bg-surface-container p-space-xs rounded-lg text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5"
          >
            <span class="material-symbols-outlined text-[16px] text-primary">info</span>
            <span>Los límites de aforo y sonido diurno están normados por Ley Provincial de Hospedaje Rural.</span>
          </div>
        </div>
      </div>

      <!-- TARIFAS EDITABLES MULTIMODALES -->
      <form class="mt-space-lg flex flex-col gap-space-md" @submit.prevent="emit('saveTarifas')">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Gestión Integral de Tarifas (ARS)</h3>
            <p class="font-label-sm text-label-sm text-on-surface-variant">
              Configure precios según modalidad: Diurno, Pernocte o Fiestas Nocturnas
            </p>
          </div>

          <!-- Selector de Pestañas de Tarifas -->
          <div
            class="flex items-center gap-1 bg-surface-container-low p-1 rounded-xl border border-surface-container self-start sm:self-auto"
          >
            <button
              type="button"
              @click="emit('update:tariffsTab', 'diurno')"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'diurno'
                  ? 'bg-secondary text-on-secondary shadow-xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[16px]">wb_sunny</span>
              <span>Diurnas</span>
            </button>

            <button
              type="button"
              @click="emit('update:tariffsTab', 'pernocte')"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'pernocte'
                  ? 'bg-primary text-on-primary shadow-xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[16px]">hotel</span>
              <span>Pernocte / Estadía</span>
            </button>

            <button
              type="button"
              @click="emit('update:tariffsTab', 'fiestas')"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
              :class="
                tariffsTab === 'fiestas'
                  ? 'bg-purple-700 text-white shadow-xs'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <span class="material-symbols-outlined text-[16px]">nightlife</span>
              <span>Fiestas &amp; Eventos</span>
            </button>
          </div>
        </div>

        <!-- TAB 1: TARIFAS DIURNAS DE TEMPORADA -->
        <div
          v-if="tariffsTab === 'diurno'"
          class="grid grid-cols-1 sm:grid-cols-3 gap-space-md animate-in fade-in duration-150"
        >
          <!-- Tarifa Diurna 1 -->
          <div
            class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Lunes a Jueves</span>
              <span class="material-symbols-outlined text-[18px] text-on-surface-variant">wb_sunny</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-on-surface-variant font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.lunesJueves"
                type="number"
                step="1000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">
              Jornada base diurna ({{
                calculateHoursDuration(selectedOffer.schedule.start, selectedOffer.schedule.end)
              }}h)
            </span>
          </div>

          <!-- Tarifa Diurna 2 -->
          <div
            class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Viernes y Domingos</span>
              <span class="material-symbols-outlined text-[18px] text-tertiary">celebration</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-on-surface-variant font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.viernesDomingo"
                type="number"
                step="1000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Media demanda diurna</span>
          </div>

          <!-- Tarifa Diurna 3 -->
          <div
            class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Sábados y Feriados</span>
              <span class="material-symbols-outlined text-[18px] text-secondary">star</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-on-surface-variant font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.sabadoFeriados"
                type="number"
                step="1000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-secondary font-bold">Alta demanda fin de semana</span>
          </div>
        </div>

        <!-- TAB 2: TARIFAS DE ESTADÍA Y PERNOCTE -->
        <div
          v-else-if="tariffsTab === 'pernocte'"
          class="grid grid-cols-1 sm:grid-cols-3 gap-space-md animate-in fade-in duration-150"
        >
          <!-- Pernocte 1 -->
          <div class="p-space-sm rounded-xl bg-primary/5 border border-primary/20 flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">x Noche en Camas</span>
              <span class="material-symbols-outlined text-[18px] text-primary">bed</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-primary font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.pernocteCamas"
                type="number"
                step="1000"
                placeholder="65000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Cabaña y dormitorios homologados</span>
          </div>

          <!-- Pernocte 2 -->
          <div class="p-space-sm rounded-xl bg-primary/5 border border-primary/20 flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">x Noche en Carpas (Acampe)</span>
              <span class="material-symbols-outlined text-[18px] text-primary">camping</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-primary font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.pernocteCarpas"
                type="number"
                step="1000"
                placeholder="40000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Predio cerrado y baños habilitados</span>
          </div>

          <!-- Pernocte 3 -->
          <div class="p-space-sm rounded-xl bg-primary/5 border border-primary/20 flex flex-col gap-space-xs">
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-primary font-bold">Finde Completo (Vie-Dom)</span>
              <span class="material-symbols-outlined text-[18px] text-secondary">date_range</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-primary font-label-lg text-label-lg font-bold">$</span>
              <input
                v-model.number="selectedOffer.tariffs.findeCompleto"
                type="number"
                step="1000"
                placeholder="110000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-headline-sm text-headline-sm font-bold focus:outline-none focus:bg-surface-container-high transition-colors border border-surface-container"
              />
            </div>
            <span class="font-label-sm text-label-sm text-secondary font-bold">Paquete 3 días / 2 noches</span>
          </div>
        </div>

        <!-- TAB 3: TARIFAS DE FIESTAS Y EVENTOS GRANDES -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 gap-space-md animate-in fade-in duration-150">
          <div
            class="p-space-sm rounded-xl bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-space-xs border-2 border-purple-300 dark:border-purple-800 shadow-2xs"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">
                Fiesta Nocturna (15 años / Bodas / Cenas)
              </span>
              <span class="material-symbols-outlined text-[18px] text-purple-700 dark:text-purple-300">nightlife</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">
                $
              </span>
              <input
                v-model.number="selectedOffer.tariffs.fiestaNocturna"
                type="number"
                step="1000"
                placeholder="120000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
              />
            </div>
            <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold">
              Franja nocturna hasta 05:00 hs • Potencia y limpieza
            </span>
          </div>

          <div
            class="p-space-sm rounded-xl bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-space-xs border-2 border-purple-300 dark:border-purple-800 shadow-2xs"
          >
            <div class="flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">
                Eventos Especiales Provinciales
              </span>
              <span class="material-symbols-outlined text-[18px] text-purple-700 dark:text-purple-300">festival</span>
            </div>
            <div class="relative">
              <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">
                $
              </span>
              <input
                v-model.number="selectedOffer.tariffs.eventoEspecial"
                type="number"
                step="1000"
                class="w-full pl-7 pr-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
              />
            </div>
            <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold">
              Alta demanda en la zona (FNS / Pernocte masivo)
            </span>
          </div>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-space-sm pt-space-xs">
          <span
            class="font-label-sm text-label-sm flex items-center gap-1 font-semibold"
            :class="tarifaSavedFeedback ? 'text-secondary font-bold' : 'text-on-surface-variant'"
          >
            <span class="material-symbols-outlined text-[16px] text-secondary">
              {{ tarifaSavedFeedback ? 'done_all' : 'check_circle' }}
            </span>
            {{
              tarifaSavedFeedback
                ? '¡Tarifas actualizadas y sincronizadas en la plataforma!'
                : 'Tarifas sincronizadas con la Red Oficial de Prestadores'
            }}
          </span>

          <button
            type="submit"
            class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-all shadow-sm font-bold cursor-pointer"
          >
            <span class="material-symbols-outlined text-[18px]">save</span>
            Guardar y Publicar Tarifas
          </button>
        </div>
      </form>
    </div>
  </section>
</template>
