<script setup lang="ts">
import type { PropietarioOferta, NewBookingPayload } from '../../../types/propietario'

defineProps<{
  modelValue: boolean
  selectedOffer: PropietarioOferta
  newBooking: NewBookingPayload
  totalGuests: number
  isOverCapacity: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'submit'): void
}>()

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
    @click.self="close"
  >
    <div
      class="bg-surface-container-lowest rounded-xl shadow-xl max-w-2xl w-full p-space-lg flex flex-col gap-space-md my-8 border border-surface-container animate-in fade-in duration-200"
    >
      <!-- Modal Header -->
      <div class="flex items-center justify-between pb-space-sm border-b border-surface-container">
        <div class="flex items-center gap-space-xs">
          <div
            class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
            :class="
              newBooking.modality === 'bloqueo'
                ? newBooking.blockType === 'externo'
                  ? 'bg-rose-100 dark:bg-rose-950/50 text-rose-700'
                  : 'bg-amber-100 dark:bg-amber-950/50 text-amber-700'
                : 'bg-secondary/20 text-secondary'
            "
          >
            <span class="material-symbols-outlined">
              {{
                newBooking.modality === 'bloqueo'
                  ? newBooking.blockType === 'externo'
                    ? 'no_crash'
                    : newBooking.blockType === 'familiar'
                      ? 'cottage'
                      : 'construction'
                  : 'person_add'
              }}
            </span>
          </div>
          <div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
              {{
                newBooking.modality === 'bloqueo'
                  ? 'Bloquear Fecha / Indisponibilidad'
                  : 'Agendar Nueva Reserva de Inquilino'
              }}
            </h3>
            <span class="font-label-sm text-label-sm text-on-surface-variant">
              {{ selectedOffer.title }} ({{ selectedOffer.legajo }})
            </span>
          </div>
        </div>
        <button
          type="button"
          aria-label="Cerrar modal de reserva"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition-colors cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Form Content -->
      <form class="flex flex-col gap-space-md" @submit.prevent="emit('submit')">
        <!-- 1. MODALIDAD DE ESTADÍAS, HORARIOS Y AFORO / BLOQUEO (4 Opciones) -->
        <div class="flex flex-col gap-space-xs">
          <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
            1. Modalidad de Estadías, Horarios y Aforo
          </span>

          <!-- Selector de 4 Modalidades -->
          <div
            class="grid grid-cols-2 sm:grid-cols-4 gap-2 p-1.5 bg-surface-container-low rounded-xl border border-surface-container"
          >
            <!-- Opción 1: Diurno -->
            <button
              type="button"
              @click="newBooking.modality = 'diurno'"
              class="p-2 rounded-lg flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
              :class="
                newBooking.modality === 'diurno'
                  ? 'bg-secondary text-on-secondary shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[17px]">sunny</span>
                <span class="text-xs font-bold">Jornada Diurna</span>
              </div>
              <span class="text-[10px] opacity-85">10 a 20 hs • Sin pernocte</span>
            </button>

            <!-- Opción 2: Varios Días con Pernocte -->
            <button
              type="button"
              @click="newBooking.modality = 'multidia'"
              class="p-2 rounded-lg flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
              :class="
                newBooking.modality === 'multidia'
                  ? 'bg-primary text-on-primary shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[17px]">hotel</span>
                <span class="text-xs font-bold">Varios Días</span>
              </div>
              <span class="text-[10px] opacity-85">Estadía • Camas / Carpas</span>
            </button>

            <!-- Opción 3: Fiesta / Evento Nocturno -->
            <button
              type="button"
              @click="newBooking.modality = 'fiesta_nocturna'"
              class="p-2 rounded-lg flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
              :class="
                newBooking.modality === 'fiesta_nocturna'
                  ? 'bg-purple-700 text-white shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[17px]">nightlife</span>
                <span class="text-xs font-bold">Fiesta Nocturna</span>
              </div>
              <span class="text-[10px] opacity-85">Franja especial nocturna</span>
            </button>

            <!-- Opción 4: Bloquear Fecha / Indisponibilidad -->
            <button
              type="button"
              @click="newBooking.modality = 'bloqueo'"
              class="p-2 rounded-lg flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
              :class="
                newBooking.modality === 'bloqueo'
                  ? 'bg-amber-600 text-white shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'
              "
            >
              <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[17px]">event_busy</span>
                <span class="text-xs font-bold">Bloquear Fecha</span>
              </div>
              <span class="text-[10px] opacity-85">Familia, Mantenimiento, Externa</span>
            </button>
          </div>
        </div>

        <!-- SECCIÓN SI MODALIDAD ES BLOQUEAR FECHA -->
        <template v-if="newBooking.modality === 'bloqueo'">
          <!-- Tipo de Bloqueo / Motivo General -->
          <div class="flex flex-col gap-space-xs pt-space-xs">
            <span
              class="font-label-md text-label-md uppercase tracking-wider text-amber-700 dark:text-amber-400 font-bold"
            >
              2. Motivo del Bloqueo e Indisponibilidad
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
              <!-- Bloqueo 1: Uso Familiar -->
              <button
                type="button"
                @click="
                  newBooking.blockType = 'familiar';
                  newBooking.reason = 'Uso Familiar'
                "
                class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                :class="
                  newBooking.blockType === 'familiar'
                    ? 'bg-amber-100/70 border-amber-500 text-amber-950 font-bold ring-2 ring-amber-400/50 dark:bg-amber-950/50 dark:text-amber-200'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'
                "
              >
                <span class="material-symbols-outlined text-[20px] text-amber-700 dark:text-amber-300">cottage</span>
                <span class="text-xs font-bold">Uso Familiar</span>
                <span class="text-[10px] text-amber-800 dark:text-amber-300">Privado</span>
              </button>

              <!-- Bloqueo 2: Mantenimiento -->
              <button
                type="button"
                @click="
                  newBooking.blockType = 'mantenimiento';
                  newBooking.reason = 'Mantenimiento del Predio'
                "
                class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                :class="
                  newBooking.blockType === 'mantenimiento'
                    ? 'bg-primary/10 border-primary text-primary font-bold ring-2 ring-primary/30'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'
                "
              >
                <span class="material-symbols-outlined text-[20px] text-primary">construction</span>
                <span class="text-xs font-bold">Mantenimiento</span>
                <span class="text-[10px] text-on-surface-variant">Arreglos / Poda</span>
              </button>

              <!-- Bloqueo 3: Reserva Externa Particular -->
              <button
                type="button"
                @click="
                  newBooking.blockType = 'externo';
                  newBooking.reason = 'Reserva Particular Fuera de Plataforma'
                "
                class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                :class="
                  newBooking.blockType === 'externo'
                    ? 'bg-rose-100/80 border-rose-500 text-rose-950 font-bold ring-2 ring-rose-400/50 dark:bg-rose-950/50 dark:text-rose-200'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'
                "
              >
                <span class="material-symbols-outlined text-[20px] text-rose-700 dark:text-rose-300">no_crash</span>
                <span class="text-xs font-bold">Reserva Externa</span>
                <span class="text-[10px] text-rose-800 dark:text-rose-300">Fuera de Plataforma</span>
              </button>
            </div>
          </div>

          <!-- Rango de Fechas a Bloquear -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm pt-space-xs">
            <div>
              <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                Fecha Desde (o Día Único)
              </label>
              <input
                v-model="newBooking.date"
                type="date"
                required
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-semibold"
              />
            </div>
            <div>
              <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                Fecha Hasta (Opcional para rango de varios días)
              </label>
              <input
                v-model="newBooking.endDate"
                type="date"
                :min="newBooking.date"
                placeholder="Mismo día si queda vacío"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container"
              />
            </div>
          </div>

          <!-- Título / Detalle en Calendario -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
            <div>
              <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                Título en Almanaque
              </label>
              <select
                v-if="newBooking.blockType === 'familiar'"
                v-model="newBooking.reason"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-semibold"
              >
                <option value="Uso Familiar">Uso Familiar Privado</option>
                <option value="Cumpleaños Familiar">Cumpleaños Familiar / Íntimo</option>
                <option value="Finde de Descanso">Fin de Semana de Descanso Anfitrión</option>
                <option value="Reunión Privada">Reunión Privada de Propietario</option>
              </select>
              <select
                v-else-if="newBooking.blockType === 'mantenimiento'"
                v-model="newBooking.reason"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-semibold"
              >
                <option value="Limpieza &amp; Pileta">Filtrado y Mantenimiento de Pileta</option>
                <option value="Corte de Césped">Corte de Césped y Poda de Parque</option>
                <option value="Pintura &amp; Quincho">Pintura y Reparaciones de Quincho</option>
                <option value="Fumigación">Fumigación / Mantenimiento Sanitario</option>
              </select>
              <select
                v-else
                v-model="newBooking.reason"
                class="w-full px-3 py-2 rounded-lg bg-rose-50/50 dark:bg-rose-950/20 text-rose-950 dark:text-rose-100 font-body-sm text-body-sm focus:outline-none border border-rose-300 dark:border-rose-800 font-bold"
              >
                <option value="Alquiler Particular">Alquiler Particular Directo (Sin Plataforma)</option>
                <option value="Compromiso Previo">Compromiso Social Previo</option>
                <option value="Reserva Telefónica">Reserva Telefónica Externa</option>
              </select>
            </div>

            <div>
              <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1">
                Subtítulo / Notas
              </label>
              <input
                v-model="newBooking.notes"
                type="text"
                :placeholder="
                  newBooking.blockType === 'externo'
                    ? 'Ej: Sin cobertura de Turismo Seguro'
                    : 'Ej: Asado familiar / Reparación de bomba'
                "
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container"
              />
            </div>
          </div>

          <!-- Alerta legal para bloqueos externos -->
          <div
            v-if="newBooking.blockType === 'externo'"
            class="p-space-sm rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-900 dark:text-rose-200 flex flex-col gap-1.5 animate-in fade-in duration-150"
          >
            <div class="flex items-center gap-1.5 font-bold">
              <span class="material-symbols-outlined text-[18px] text-rose-700 dark:text-rose-400">gavel</span>
              <span>Constancia Legal y Deslinde de Responsabilidad:</span>
            </div>
            <p class="text-[11px] leading-relaxed">
              Al declarar esta fecha como <strong>Bloqueada por Reserva Externa</strong>, queda registrado fehacientemente que el inmueble <strong>no estuvo disponible para alquiler dentro del sistema</strong>. Ante cualquier incidente, siniestro o reclamo de terceros, la plataforma y la aseguradora contarán con la constancia de que la estadía fue acordada fuera de Turismo Seguro, eximiendo de validación, certificación o amparo de póliza.
            </p>
          </div>
          <div
            v-else
            class="p-space-sm rounded-lg bg-surface-container text-xs text-on-surface-variant flex items-start gap-2"
          >
            <span class="material-symbols-outlined text-primary text-[18px] shrink-0">info</span>
            <span>
              Al bloquear la(s) fecha(s), el buscador público y los turistas verán los días como no disponibles, preservando su predio para uso exclusivo.
            </span>
          </div>
        </template>

        <!-- SECCIÓN SI ES RESERVA DE INQUILINO -->
        <template v-else>
          <!-- 2. Datos personales del inquilino titular -->
          <div class="flex flex-col gap-space-xs">
            <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
              2. Datos Personales del Inquilino Titular
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Nombre y Apellido
                </label>
                <input
                  v-model="newBooking.tenantName"
                  type="text"
                  required
                  placeholder="Ej: Marcela González"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">DNI / CUIL</label>
                <input
                  v-model="newBooking.tenantDni"
                  type="text"
                  required
                  placeholder="Ej: 33.123.456"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Celular con WhatsApp
                </label>
                <input
                  v-model="newBooking.tenantPhone"
                  type="tel"
                  required
                  placeholder="Ej: +54 9 264 455-8822"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Localidad de Origen
                </label>
                <select
                  v-model="newBooking.tenantCity"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                >
                  <option value="rawson">Rawson, San Juan</option>
                  <option value="capital">Capital, San Juan</option>
                  <option value="pocito">Pocito, San Juan</option>
                  <option value="rivadavia">Rivadavia, San Juan</option>
                  <option value="chimbas">Chimbas, San Juan</option>
                  <option value="santalucia">Santa Lucía, San Juan</option>
                  <option value="otro">Otro departamento / Turista</option>
                </select>
              </div>
            </div>
          </div>

          <!-- 3. Configuración de Fechas y Aforo según Modalidad -->
          <div class="flex flex-col gap-space-sm pt-space-xs">
            <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
              3. Fechas, Horarios y Aforo
            </span>

            <!-- CASO 1: JORNADA DIURNA -->
            <div
              v-if="newBooking.modality === 'diurno'"
              class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm animate-in fade-in duration-150"
            >
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Fecha de la Jornada
                </label>
                <input
                  v-model="newBooking.date"
                  type="date"
                  required
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Turno Homologado
                </label>
                <input
                  type="text"
                  disabled
                  :value="`Diurno (${selectedOffer.schedule.start} a ${selectedOffer.schedule.end} hs)`"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Tipo de Evento
                </label>
                <select
                  v-model="newBooking.reason"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container font-semibold"
                >
                  <optgroup label="Eventos Familiares &amp; Sociales">
                    <option value="Cumpleaños familiar / infantil">🎂 Cumpleaños familiar / infantil</option>
                    <option value="Asado familiar / Amigos">🥩 Asado campestre / Amigos</option>
                    <option value="Bautismo / Comunión">🕊️ Bautismo / Primera Comunión</option>
                    <option value="Festejo de 15 años diurno">✨ Festejo de 15 años diurno</option>
                  </optgroup>
                  <optgroup label="Uso Recreativo">
                    <option value="Día de pileta y parque recreativo">🏊 Día recreativo de parque y pileta</option>
                    <option value="Día de campo en la precordillera">⛰️ Día de descanso en precordillera</option>
                  </optgroup>
                </select>
              </div>
            </div>

            <!-- CASO 2: VARIOS DÍAS (ESTADÍA CON PERNOCTE) -->
            <div
              v-else-if="newBooking.modality === 'multidia'"
              class="p-space-sm rounded-xl bg-primary/5 border border-primary/20 flex flex-col gap-space-sm animate-in fade-in duration-150"
            >
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
                <div>
                  <label class="block font-label-sm text-label-sm text-primary font-bold mb-1">
                    Check-in (Entrada)
                  </label>
                  <input
                    v-model="newBooking.date"
                    type="date"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-bold"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-primary font-bold mb-1">
                    Check-out (Salida)
                  </label>
                  <input
                    v-model="newBooking.endDate"
                    type="date"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-bold"
                  />
                </div>
              </div>

              <!-- Habilitación y Tipo de Pernocte -->
              <div class="pt-2 border-t border-primary/10">
                <label class="block font-label-sm text-label-sm text-on-surface font-semibold mb-1.5">
                  Modalidad de Pernocte Autorizada por el Anfitrión:
                </label>
                <div class="grid grid-cols-3 gap-2">
                  <label
                    class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors"
                    :class="
                      newBooking.overnightType === 'camas'
                        ? 'border-primary text-primary font-bold bg-primary/10'
                        : 'border-surface-container text-on-surface-variant'
                    "
                  >
                    <input type="radio" v-model="newBooking.overnightType" value="camas" class="text-primary" />
                    <span class="text-xs flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">bed</span>
                      En Camas / Habitaciones
                    </span>
                  </label>
                  <label
                    class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors"
                    :class="
                      newBooking.overnightType === 'carpas'
                        ? 'border-primary text-primary font-bold bg-primary/10'
                        : 'border-surface-container text-on-surface-variant'
                    "
                  >
                    <input type="radio" v-model="newBooking.overnightType" value="carpas" class="text-primary" />
                    <span class="text-xs flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">camping</span>
                      En Carpas / Acampe
                    </span>
                  </label>
                  <label
                    class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors"
                    :class="
                      newBooking.overnightType === 'ambas'
                        ? 'border-primary text-primary font-bold bg-primary/10'
                        : 'border-surface-container text-on-surface-variant'
                    "
                  >
                    <input type="radio" v-model="newBooking.overnightType" value="ambas" class="text-primary" />
                    <span class="text-xs flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px]">cottage</span>
                      Ambas (Mixto)
                    </span>
                  </label>
                </div>
                <span class="text-[11px] text-on-surface-variant block mt-1">
                  El validador audita la disponibilidad de camas y predio cerrado. Las fechas intermedias se bloquearán automáticamente en el almanaque.
                </span>
              </div>
            </div>

            <!-- CASO 3: FIESTA O EVENTO NOCTURNO -->
            <div
              v-else-if="newBooking.modality === 'fiesta_nocturna'"
              class="p-space-sm rounded-xl bg-purple-50/60 dark:bg-purple-950/30 border border-purple-300 dark:border-purple-800 flex flex-col gap-space-sm animate-in fade-in duration-150"
            >
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm">
                <div>
                  <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">
                    Fecha del Festejo
                  </label>
                  <input
                    v-model="newBooking.date"
                    type="date"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700 font-bold"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">
                    Hora Inicio
                  </label>
                  <input
                    v-model="newBooking.nightStart"
                    type="time"
                    class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">
                    Hora Cierre
                  </label>
                  <input
                    v-model="newBooking.nightEnd"
                    type="time"
                    class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700"
                  />
                </div>
              </div>

              <div>
                <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">
                  Tipo de Festejo Nocturno
                </label>
                <select
                  v-model="newBooking.reason"
                  class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700 font-semibold"
                >
                  <option value="Fiesta de 15 años nocturna">✨ Fiesta de 15 Años Nocturna</option>
                  <option value="Casamiento / Boda nocturna">💍 Casamiento / Fiesta de Bodas</option>
                  <option value="Cumpleaños adultos nocturno">🎂 Fiesta de Cumpleaños Nocturna</option>
                  <option value="Cierre de año corporativo">🏢 Cena y Fiesta de Fin de Año Empresarial</option>
                  <option value="Reunión nocturna con sonido">🎶 Encuentro social nocturno con música</option>
                </select>
                <span class="text-[11px] text-purple-800 dark:text-purple-300 block mt-1">
                  Normado con límite de decibeles de la ordenanza municipal departamental y póliza extendida.
                </span>
              </div>
            </div>

            <!-- Asistentes: Adultos + Niños con cálculo interactivo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm mt-space-xs">
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Adultos</label>
                <input
                  v-model.number="newBooking.adults"
                  type="number"
                  min="1"
                  max="60"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Niños (hasta 12 años)
                </label>
                <input
                  v-model.number="newBooking.children"
                  type="number"
                  min="0"
                  max="40"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                />
              </div>
            </div>

            <!-- Total aforo con feedback dinámico -->
            <div
              class="p-space-sm rounded-lg flex items-center justify-between border"
              :class="
                isOverCapacity
                  ? 'bg-error-container border-error/30'
                  : 'bg-surface-container-low border-surface-container'
              "
            >
              <div class="flex items-center gap-space-xs">
                <span
                  class="material-symbols-outlined text-[20px]"
                  :class="isOverCapacity ? 'text-error' : 'text-secondary'"
                >
                  {{ isOverCapacity ? 'warning' : 'check_circle' }}
                </span>
                <span class="font-body-sm text-body-sm text-on-surface">
                  Total declarados: <strong>{{ totalGuests }} personas</strong> (Tope Homologado: 50)
                </span>
              </div>
              <span
                class="font-label-sm text-label-sm font-bold"
                :class="isOverCapacity ? 'text-error' : 'text-secondary'"
              >
                {{ isOverCapacity ? '¡Exceso de Aforo!' : 'Aforo Permitido' }}
              </span>
            </div>
          </div>

          <!-- 4. Seguro y Estado de Cobertura -->
          <div class="flex flex-col gap-space-xs pt-space-xs">
            <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
              4. Seguro y Cobertura Obligatoria
            </span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Póliza Aplicada
                </label>
                <select
                  v-model="newBooking.insurance"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                >
                  <option value="mensual">Amparado en mi Póliza Mensual Triunfo #9482 (Vigente)</option>
                  <option value="microseguro">Microseguro emitido por el inquilino (#TS-SEG)</option>
                  <option value="emitir">Póliza de Jornada Homologada Oficial</option>
                </select>
              </div>
              <div>
                <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">
                  Estado de Verificación de Inquilino
                </label>
                <div
                  class="px-3 py-2 rounded-lg bg-surface-container-low text-secondary font-body-sm text-body-sm border border-surface-container flex items-center gap-1.5 font-bold"
                >
                  <span class="material-symbols-outlined text-[16px]">verified</span>
                  DNI y Domicilio Validado Renaper
                </div>
              </div>
            </div>
          </div>
        </template>

        <!-- Modal Actions -->
        <div class="flex items-center justify-end gap-space-sm pt-space-sm border-t border-surface-container">
          <button
            type="button"
            class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors cursor-pointer font-semibold"
            @click="close"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg font-label-lg text-label-lg transition-all shadow-sm font-bold cursor-pointer"
            :class="
              newBooking.modality === 'bloqueo'
                ? newBooking.blockType === 'externo'
                  ? 'bg-rose-700 text-white hover:bg-rose-800'
                  : 'bg-amber-700 text-white hover:bg-amber-800'
                : 'bg-secondary text-on-secondary hover:bg-on-secondary-container'
            "
          >
            <span class="material-symbols-outlined text-[20px]">
              {{ newBooking.modality === 'bloqueo' ? 'event_busy' : 'how_to_reg' }}
            </span>
            {{ newBooking.modality === 'bloqueo' ? 'Confirmar Bloqueo de Fechas' : 'Confirmar y Bloquear Fecha' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
