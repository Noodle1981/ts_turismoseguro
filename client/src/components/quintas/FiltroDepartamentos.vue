<script setup lang="ts">
const props = defineProps<{
  zone: string
  occasion: string
  capacity: string
  shift: string
  pills?: string[]
}>()

const emit = defineEmits<{
  (e: 'update:zone', value: string): void
  (e: 'update:occasion', value: string): void
  (e: 'update:capacity', value: string): void
  (e: 'update:shift', value: string): void
  (e: 'update:pills', value: string[]): void
  (e: 'search'): void
}>()

const togglePill = (id: string) => {
  const current = props.pills || []
  if (current.includes(id)) {
    emit('update:pills', current.filter(p => p !== id))
  } else {
    emit('update:pills', [...current, id])
  }
}

const resetPills = () => {
  emit('update:pills', [])
}
</script>

<template>
  <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-xl">
    <div class="flex items-center justify-between pb-space-md mb-space-md">
      <div class="flex items-center gap-space-xs">
        <span class="material-symbols-outlined text-secondary text-[24px]">wb_sunny</span>
        <span class="font-headline-sm text-headline-sm text-primary">Buscador para pasar el día en San Juan</span>
      </div>
      <span class="font-label-sm text-label-sm text-on-surface-variant bg-surface-container px-space-sm py-1 rounded-full hidden sm:inline-block font-semibold">
        Precios fijados en pesos sanjuaninos
      </span>
    </div>

    <!-- Search Fields Grid -->
    <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md" @submit.prevent="emit('search')">
      <!-- Field 1: Zona / Departamento -->
      <div class="flex flex-col gap-1.5">
        <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-secondary text-[16px]">pin_drop</span>
          Zona / Departamento
        </label>
        <div class="relative">
          <select
            :value="zone"
            class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
            @change="emit('update:zone', ($event.target as HTMLSelectElement).value)"
          >
            <option value="todos">Todos los Departamentos</option>
            <option value="pocito">Pocito (Calle 11 a 14 / Quinto Cuartel)</option>
            <option value="rawson">Rawson / El Medanito</option>
            <option value="zonda">Zonda (Calle Las Moras / Valle)</option>
            <option value="rivadavia">Rivadavia / La Bebida</option>
            <option value="santa-lucia">Santa Lucía (Zona Este)</option>
            <option value="albardon">Albardón</option>
            <option value="ullum">Ullum (Dique y Bahía)</option>
          </select>
          <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
        </div>
      </div>

      <!-- Field 2: Tipo de Ocasión -->
      <div class="flex flex-col gap-1.5">
        <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-secondary text-[16px]">celebration</span>
          Tipo de Ocasión
        </label>
        <div class="relative">
          <select
            :value="occasion"
            class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
            @change="emit('update:occasion', ($event.target as HTMLSelectElement).value)"
          >
            <option value="quinta-pileta">Quinta con Pileta</option>
            <option value="quincho-asados">Quincho Familiar / Asados</option>
            <option value="cumpleanos-evento">Festejo de Cumpleaños / Evento diurno</option>
            <option value="cabana-descanso">Cabaña descanso de día</option>
          </select>
          <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
        </div>
      </div>

      <!-- Field 3: Capacidad Diurna -->
      <div class="flex flex-col gap-1.5">
        <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-secondary text-[16px]">groups</span>
          Capacidad Diurna
        </label>
        <div class="relative">
          <select
            :value="capacity"
            class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
            @change="emit('update:capacity', ($event.target as HTMLSelectElement).value)"
          >
            <option value="10-20">10 a 20 personas (Grupos chicos)</option>
            <option value="20-40">20 a 40 personas (Ideal cumpleaños)</option>
            <option value="40-60">40 a 60 personas (Familias grandes)</option>
            <option value="60-plus">+60 personas (Eventos y bautismos)</option>
          </select>
          <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
        </div>
      </div>

      <!-- Field 4: Horario / Turno -->
      <div class="flex flex-col gap-1.5">
        <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-secondary text-[16px]">schedule</span>
          Horario / Turno
        </label>
        <div class="relative">
          <select
            :value="shift"
            class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
            @change="emit('update:shift', ($event.target as HTMLSelectElement).value)"
          >
            <option value="dia-completo">Día Completo (10:00 a 20:00 hs)</option>
            <option value="tarde-noche">Turno Tarde / Noche (14:00 a 02:00 hs)</option>
            <option value="finde-completo">Todo el fin de semana</option>
          </select>
          <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
        </div>
      </div>
    </form>

    <!-- Quick-Filter Pills Integrados -->
    <div class="mt-space-md pt-space-sm border-t border-surface-container">
      <div class="flex items-center justify-between mb-space-xs">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-secondary text-[18px]">filter_list</span>
          <span class="font-label-sm text-label-sm text-primary font-bold uppercase tracking-wider">Filtros rápidos por comodidades</span>
        </div>
        <button
          v-if="(pills || []).length > 0"
          type="button"
          class="font-label-sm text-label-sm text-secondary hover:underline cursor-pointer font-bold"
          @click="resetPills"
        >
          Restablecer filtros
        </button>
      </div>

      <div class="flex items-center gap-space-sm overflow-x-auto pb-1 scrollbar-none">
        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm transition-all cursor-pointer border',
            (pills || []).includes('fence') ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent',
          ]"
          @click="togglePill('fence')"
        >
          <span class="material-symbols-outlined text-[15px]" :class="(pills || []).includes('fence') ? 'text-secondary-fixed' : 'text-secondary'">fence</span>
          Piscina con cerca de seguridad (niños)
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm transition-all cursor-pointer border',
            (pills || []).includes('grill') ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent',
          ]"
          @click="togglePill('grill')"
        >
          <span class="material-symbols-outlined text-[15px]" :class="(pills || []).includes('grill') ? 'text-secondary-fixed' : 'text-secondary'">outdoor_grill</span>
          Parrilla techada con mesada
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm transition-all cursor-pointer border',
            (pills || []).includes('kitchen') ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent',
          ]"
          @click="togglePill('kitchen')"
        >
          <span class="material-symbols-outlined text-[15px]" :class="(pills || []).includes('kitchen') ? 'text-secondary-fixed' : 'text-secondary'">kitchen</span>
          Freezer / Heladera amplia
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm transition-all cursor-pointer border',
            (pills || []).includes('park') ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent',
          ]"
          @click="togglePill('park')"
        >
          <span class="material-symbols-outlined text-[15px]" :class="(pills || []).includes('park') ? 'text-secondary-fixed' : 'text-secondary'">park</span>
          Parque con sombra natural
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-full font-label-sm text-label-sm transition-all cursor-pointer border',
            (pills || []).includes('car') ? 'bg-primary text-on-primary border-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent',
          ]"
          @click="togglePill('car')"
        >
          <span class="material-symbols-outlined text-[15px]" :class="(pills || []).includes('car') ? 'text-secondary-fixed' : 'text-secondary'">directions_car</span>
          Cochera para +5 autos
        </button>
      </div>
    </div>

    <!-- Search Submit Row -->
    <div class="mt-space-md pt-space-sm flex flex-col sm:flex-row items-center justify-between gap-space-md border-t border-surface-container">
      <div class="flex items-center gap-space-xs text-on-surface-variant">
        <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
        <span class="font-body-sm text-body-sm">48 quintas y cabañas auditadas disponibles para el próximo fin de semana</span>
      </div>
      <button
        type="button"
        class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-lg py-3.5 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-md hover:shadow-lg font-bold cursor-pointer"
        @click="emit('search')"
      >
        <span class="material-symbols-outlined text-[20px]">search</span>
        Buscar Quintas y Cabañas Verificadas
      </button>
    </div>
  </div>
</template>
