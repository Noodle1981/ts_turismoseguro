<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import {
  eventosCatalogo,
  turisticosCatalogo,
  experienciasAventura,
  corredoresTuristicos,
} from '../../data/experienciasData'

// 1. Selector de Evento Activo
const selectedEventKey = ref<'fns' | 'villicum'>('fns')

// 2. Selector de Destino Turístico Activo
const selectedTurismoKey = ref<'ullum' | 'leoncito' | 'cuesta'>('ullum')

const activeEvent = computed(() => {
  return eventosCatalogo[selectedEventKey.value] || eventosCatalogo.fns
})

const activeTurismo = computed(() => {
  return turisticosCatalogo[selectedTurismoKey.value] || turisticosCatalogo.ullum
})
</script>

<template>
  <div>
    <!-- ========================================================= -->
    <!-- SECCIÓN 1: EXCLUSIVA DE GRANDES EVENTOS & FIESTAS POPULARES -->
    <!-- ========================================================= -->
    <section class="w-full bg-surface-container-low py-space-xl border-y border-surface-container">
      <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <!-- HEADER EXCLUSIVO DE EVENTOS -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-space-lg">
          <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm font-bold mb-2">
              <span class="material-symbols-outlined text-[16px]">festival</span>
              CALENDARIO DEPORTIVO &amp; FIESTAS POPULARES
            </div>
            <h2 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
              Quintas y Hospedajes para Grandes Eventos en San Juan
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl mt-1">
              Si venís a vivir la Fiesta Nacional del Sol o una fecha de automovilismo en El Villicum, encontrá quinchos de asado para previas y hospedajes grupales con accesos rápidos y seguros sin demoras viales.
            </p>
          </div>

          <RouterLink
            to="/eventos"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container text-primary font-label-md text-label-md font-bold shadow-xs border border-surface-container transition-all shrink-0"
          >
            <span>Agenda Completa de Eventos</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
          </RouterLink>
        </div>

        <!-- TABS DE EVENTOS (SIN MEZCLA CON TURISMO) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-space-lg">
          <button
            type="button"
            :class="[
              'px-4 py-2.5 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedEventKey === 'fns'
                ? 'bg-primary text-on-primary border-primary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedEventKey = 'fns'"
          >
            <span class="material-symbols-outlined text-[18px]">celebration</span>
            <span>Fiesta Nacional del Sol 2026</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2.5 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedEventKey === 'villicum'
                ? 'bg-primary text-on-primary border-primary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedEventKey = 'villicum'"
          >
            <span class="material-symbols-outlined text-[18px]">sports_motorsports</span>
            <span>Autódromo Circuito Villicum</span>
          </button>
        </div>

        <!-- BANNER DEL EVENTO ACTIVO -->
        <div class="bg-primary-container text-on-primary p-6 lg:p-8 rounded-3xl relative overflow-hidden shadow-md mb-space-lg">
          <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-secondary/15 blur-2xl pointer-events-none"></div>
          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8 space-y-2">
              <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm font-bold">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                {{ activeEvent.badge }}
              </div>
              <h3 class="font-headline-lg text-headline-lg font-bold text-on-primary">
                {{ activeEvent.title }}
              </h3>
              <p class="font-label-md text-label-md text-secondary-fixed font-semibold">
                {{ activeEvent.subtitle }} • {{ activeEvent.department }}
              </p>
              <p class="font-body-md text-body-md text-on-primary-container max-w-2xl leading-relaxed">
                {{ activeEvent.description }}
              </p>
            </div>

            <div class="lg:col-span-4 bg-surface-container-lowest/10 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex flex-col gap-3">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary-fixed text-[26px]">distance</span>
                <div>
                  <p class="font-label-md text-label-md text-on-primary font-bold">Radio de Proximidad Seguro</p>
                  <p class="font-body-sm text-body-sm text-on-primary-container">Accesos de 5 a 15 minutos al predio</p>
                </div>
              </div>
              <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-primary-container">Disponibles para este evento:</span>
                <span class="font-label-md text-label-md text-secondary-fixed font-bold">{{ activeEvent.lodgingsCount }} Propiedades</span>
              </div>
            </div>
          </div>
        </div>

        <!-- RESULTADOS: QUINTAS Y CABAÑAS CERCANAS AL EVENTO -->
        <div class="space-y-space-md mb-space-lg">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[22px]">near_me</span>
              <h4 class="font-headline-sm text-headline-sm text-primary font-bold">
                Quintas Auditadas Cerca de: <span class="text-secondary">{{ activeEvent.title }}</span>
              </h4>
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant font-medium hidden sm:inline-block">
              CBU cotejado in situ • Cero estafas
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <article
              v-for="item in activeEvent.lodgings"
              :key="item.id"
              class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between border border-surface-container group"
            >
              <div>
                <!-- Imagen y badges -->
                <div class="relative h-52 w-full overflow-hidden">
                  <img
                    :src="item.image"
                    :alt="item.title"
                    loading="lazy"
                    decoding="async"
                    width="400"
                    height="208"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div class="absolute top-3 left-3 flex items-center gap-1.5 bg-surface-container-lowest/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm text-secondary font-label-sm text-label-sm font-bold">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    Validado
                  </div>
                  <div class="absolute bottom-3 left-3 bg-primary/90 backdrop-blur-sm text-on-primary px-2.5 py-0.5 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-1 shadow-sm">
                    <span class="material-symbols-outlined text-[14px] text-secondary-fixed">near_me</span>
                    {{ item.distance }}
                  </div>
                </div>

                <!-- Contenido -->
                <div class="p-space-md space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-secondary font-bold uppercase tracking-wider">
                      {{ item.location }}
                    </span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px] text-secondary">groups</span>
                      Hasta {{ item.capacity }} pers.
                    </span>
                  </div>

                  <h5 class="font-headline-sm text-headline-sm text-primary font-bold line-clamp-1">
                    {{ item.title }}
                  </h5>

                  <p class="font-label-sm text-label-sm text-on-surface-variant">
                    Tipo: <strong class="text-on-surface">{{ item.tipo }}</strong>
                  </p>

                  <div class="flex flex-wrap gap-1.5 pt-1">
                    <span
                      v-for="(amenity, aIdx) in item.amenities"
                      :key="aIdx"
                      class="px-2 py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm flex items-center gap-1"
                    >
                      <span class="material-symbols-outlined text-[14px] text-secondary">check</span>
                      {{ amenity }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Footer con precio y acción principal a detalles -->
              <div class="p-space-md pt-0 border-t border-surface-container mt-2 flex items-center justify-between gap-2">
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Tarifa por día</span>
                  <span class="font-headline-md text-headline-md text-primary font-black">
                    ${{ item.price.toLocaleString('es-AR') }}
                  </span>
                </div>

                <RouterLink
                  :to="`/quintas/${item.id}`"
                  class="px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-dim text-primary transition-colors font-label-md text-label-md font-bold flex items-center gap-1.5 cursor-pointer"
                >
                  <span class="material-symbols-outlined text-[18px]">info</span>
                  <span>Ver Detalles</span>
                </RouterLink>
              </div>
            </article>
          </div>
        </div>

        <!-- Banner de Rutas y Accesibilidad de Eventos -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl flex flex-col lg:flex-row items-center justify-between gap-6 border border-surface-container shadow-sm">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-secondary/15 flex items-center justify-center text-secondary shrink-0 mt-0.5">
              <span class="material-symbols-outlined text-[24px]">map</span>
            </div>
            <div>
              <h4 class="font-headline-sm text-headline-sm text-primary font-bold">Mapa de Rutas y Accesibilidad en Tiempo Real</h4>
              <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                Consultá los desvíos habilitados durante la Fiesta del Sol, Autódromo El Villicum y accesos despejados por RN 40.
              </p>
            </div>
          </div>
          <a
            href="https://wa.me/5492644000000?text=Hola,%20deseo%20recibir%20el%20mapa%20de%20accesos%20y%20estacionamiento%20FNS%202026"
            target="_blank"
            rel="noopener noreferrer"
            class="px-5 py-3 rounded-xl bg-primary text-on-primary hover:bg-primary-container transition-all flex items-center gap-2 font-label-md text-label-md font-bold whitespace-nowrap shadow-sm cursor-pointer shrink-0"
          >
            <span class="material-symbols-outlined text-[20px] text-secondary-fixed">navigation</span>
            <span>Guía de Rutas y Accesos FNS</span>
          </a>
        </div>
      </div>
    </section>

    <!-- ========================================================= -->
    <!-- SECCIÓN 2: EXCLUSIVA DE TURISMO PURO, DIQUES & CORDILLERA  -->
    <!-- ========================================================= -->
    <section class="w-full bg-background py-space-xl border-b border-surface-container">
      <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <!-- HEADER EXCLUSIVO DE TURISMO PURO -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-space-lg">
          <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-bold mb-2">
              <span class="material-symbols-outlined text-[16px]">landscape</span>
              DESTINOS NATURALES &amp; TURISMO PURO
            </div>
            <h2 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
              Cabañas y Quintas en Diques y Valles Cordilleranos
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl mt-1">
              Descanso al pie de la montaña, náutica en los diques o astroturismo bajo cielos certificados. Propiedades verificadas in situ con trato directo y sin comisiones.
            </p>
          </div>

          <RouterLink
            to="/explorar"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-dim text-primary font-label-md text-label-md font-bold shadow-xs border border-surface-container transition-all shrink-0"
          >
            <span>Explorar Todos los Destinos</span>
            <span class="material-symbols-outlined text-[18px]">travel_explore</span>
          </RouterLink>
        </div>

        <!-- TABS DE DESTINOS TURÍSTICOS (DIQUES / LEONCITO / CUESTA) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-space-lg">
          <button
            type="button"
            :class="[
              'px-4 py-2.5 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedTurismoKey === 'ullum'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedTurismoKey === 'ullum'"
          >
            <span class="material-symbols-outlined text-[18px]">kayaking</span>
            <span>Diques Ullum y Punta Negra</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2.5 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedTurismoKey === 'leoncito'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedTurismoKey === 'leoncito'"
          >
            <span class="material-symbols-outlined text-[18px]">nights_stay</span>
            <span>Astroturismo en El Leoncito (Barreal)</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2.5 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedTurismoKey === 'cuesta'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedTurismoKey === 'cuesta'"
          >
            <span class="material-symbols-outlined text-[18px]">air</span>
            <span>Cuesta del Viento (Rodeo)</span>
          </button>
        </div>

        <!-- BANNER DEL DESTINO TURÍSTICO ACTIVO -->
        <div class="bg-surface-container-lowest text-on-surface p-6 lg:p-8 rounded-3xl relative overflow-hidden shadow-sm mb-space-lg border border-surface-container">
          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8 space-y-2">
              <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold">
                <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                {{ activeTurismo.badge }}
              </div>
              <h3 class="font-headline-lg text-headline-lg font-bold text-primary">
                {{ activeTurismo.title }}
              </h3>
              <p class="font-label-md text-label-md text-secondary font-semibold">
                {{ activeTurismo.subtitle }} • {{ activeTurismo.department }}
              </p>
              <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl leading-relaxed">
                {{ activeTurismo.description }}
              </p>
            </div>

            <div class="lg:col-span-4 bg-surface-container-low p-5 rounded-2xl border border-surface-container flex flex-col gap-3">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary text-[26px]">landscape</span>
                <div>
                  <p class="font-label-md text-label-md text-primary font-bold">Polo Turístico Natural</p>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">Aguas calmas, vientos y cielo diáfano</p>
                </div>
              </div>
              <div v-if="activeTurismo.officialOperator" class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary text-[26px]">shield</span>
                <div>
                  <p class="font-label-md text-label-md text-primary font-bold">Operador Acreditado</p>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">{{ activeTurismo.officialOperator }}</p>
                </div>
              </div>
              <div class="pt-2 border-t border-surface-container flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Cabañas y quintas en la zona:</span>
                <span class="font-label-md text-label-md text-primary font-bold">{{ activeTurismo.lodgingsCount }} Propiedades</span>
              </div>
            </div>
          </div>
        </div>

        <!-- RESULTADOS: CABAÑAS Y QUINTAS DEL DESTINO TURÍSTICO -->
        <div class="space-y-space-md mb-space-xl">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[22px]">cottage</span>
              <h4 class="font-headline-sm text-headline-sm text-primary font-bold">
                Alojamientos en: <span class="text-secondary">{{ activeTurismo.title }}</span>
              </h4>
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant font-medium hidden sm:inline-block">
              Inspección técnica vigente • Directo con dueño
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <article
              v-for="item in activeTurismo.lodgings"
              :key="item.id"
              class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between border border-surface-container group"
            >
              <div>
                <!-- Imagen y badges -->
                <div class="relative h-52 w-full overflow-hidden">
                  <img
                    :src="item.image"
                    :alt="item.title"
                    loading="lazy"
                    decoding="async"
                    width="400"
                    height="208"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div class="absolute top-3 left-3 flex items-center gap-1.5 bg-surface-container-lowest/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm text-secondary font-label-sm text-label-sm font-bold">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    Validado
                  </div>
                  <div class="absolute bottom-3 left-3 bg-primary/90 backdrop-blur-sm text-on-primary px-2.5 py-0.5 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-1 shadow-sm">
                    <span class="material-symbols-outlined text-[14px] text-secondary-fixed">near_me</span>
                    {{ item.distance }}
                  </div>
                </div>

                <!-- Contenido -->
                <div class="p-space-md space-y-3">
                  <div class="flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-secondary font-bold uppercase tracking-wider">
                      {{ item.location }}
                    </span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold flex items-center gap-1">
                      <span class="material-symbols-outlined text-[16px] text-secondary">groups</span>
                      Hasta {{ item.capacity }} pers.
                    </span>
                  </div>

                  <h5 class="font-headline-sm text-headline-sm text-primary font-bold line-clamp-1">
                    {{ item.title }}
                  </h5>

                  <p class="font-label-sm text-label-sm text-on-surface-variant">
                    Tipo: <strong class="text-on-surface">{{ item.tipo }}</strong>
                  </p>

                  <div class="flex flex-wrap gap-1.5 pt-1">
                    <span
                      v-for="(amenity, aIdx) in item.amenities"
                      :key="aIdx"
                      class="px-2 py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm flex items-center gap-1"
                    >
                      <span class="material-symbols-outlined text-[14px] text-secondary">check</span>
                      {{ amenity }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Footer con precio y acción principal a detalles -->
              <div class="p-space-md pt-0 border-t border-surface-container mt-2 flex items-center justify-between gap-2">
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Tarifa por día</span>
                  <span class="font-headline-md text-headline-md text-primary font-black">
                    ${{ item.price.toLocaleString('es-AR') }}
                  </span>
                </div>

                <RouterLink
                  :to="`/quintas/${item.id}`"
                  class="px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-dim text-primary transition-colors font-label-md text-label-md font-bold flex items-center gap-1.5 cursor-pointer"
                >
                  <span class="material-symbols-outlined text-[18px]">info</span>
                  <span>Ver Detalles</span>
                </RouterLink>
              </div>
            </article>
          </div>
        </div>

        <!-- EXPERIENCIAS ÚNICAS DE NATURALEZA & AVENTURA -->
        <div class="pt-space-xl border-t border-surface-container">
          <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-space-lg">
            <div>
              <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest font-bold">OPERADORES TS CERTIFICADOS</span>
              <h3 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold mt-1">
                Experiencias Únicas de Naturaleza &amp; Aventura
              </h3>
              <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mt-1">
                Cada operador cuenta con matrícula turística provincial vigente, seguro de responsabilidad civil para turismo aventura y guías baqueanos certificados.
              </p>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center gap-1.5 font-label-md text-label-md px-3.5 py-2 rounded-xl bg-surface-container text-primary shadow-xs border border-surface-container font-semibold">
                <span class="material-symbols-outlined text-secondary text-[20px]">assignment_turned_in</span>
                Pólizas de Seguro Auditadas
              </span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="exp in experienciasAventura"
              :key="exp.id"
              class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col lg:flex-row border border-surface-container"
            >
              <div class="relative lg:w-2/5 h-64 lg:h-auto min-h-[16rem]">
                <img
                  :src="exp.image"
                  :alt="exp.title"
                  loading="lazy"
                  decoding="async"
                  width="480"
                  height="320"
                  class="w-full h-full object-cover"
                />
                <div class="absolute top-3 left-3 bg-primary/85 backdrop-blur-md text-on-primary px-2.5 py-1 rounded-md font-label-sm text-label-sm flex items-center gap-1 font-semibold">
                  <span class="material-symbols-outlined text-[16px] text-tertiary-fixed">location_on</span>
                  {{ exp.locationTag }}
                </div>
              </div>

              <div class="p-6 lg:w-3/5 flex flex-col justify-between">
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-secondary bg-secondary/10 px-2 py-0.5 rounded-md font-bold">
                      <span class="material-symbols-outlined text-[14px]">verified</span> Operador: {{ exp.operator }}
                    </span>
                    <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">Duración: {{ exp.duration }}</span>
                  </div>

                  <h4 class="font-headline-sm text-headline-sm text-primary mb-2 font-bold">
                    {{ exp.title }}
                  </h4>

                  <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">
                    {{ exp.description }}
                  </p>

                  <div class="grid grid-cols-2 gap-2 text-on-surface font-label-sm text-label-sm mb-4">
                    <span v-for="(feat, fIdx) in exp.features" :key="fIdx" class="flex items-center gap-1">
                      <span class="material-symbols-outlined text-[14px] text-secondary">check</span>
                      {{ feat }}
                    </span>
                  </div>
                </div>

                <div class="pt-3 border-t border-surface-container bg-surface-container-low -mx-6 -mb-6 p-4 rounded-b-2xl flex flex-col sm:flex-row items-center justify-between gap-3">
                  <RouterLink
                    to="/explorar"
                    class="text-secondary hover:text-on-secondary-container font-label-md text-label-md flex items-center gap-1.5 transition-colors font-bold cursor-pointer"
                  >
                    <span class="material-symbols-outlined text-[18px]">cottage</span>
                    Ver {{ exp.lodgingCount }} cabañas en {{ exp.lodgingZone }}
                  </RouterLink>

                  <a
                    :href="`https://wa.me/${exp.whatsapp}?text=Hola,%20deseo%20consultar%20por%20la%20experiencia%20${encodeURIComponent(exp.title)}%20en%20Turismo%20Seguro`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="w-full sm:w-auto px-4 py-2 rounded-xl bg-primary text-on-primary font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center justify-center gap-2 font-bold cursor-pointer shadow-xs"
                  >
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                      <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.79.78 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.767-5.766zm9.969 5.766c0 5.505-4.475 9.98-9.97 9.98-1.742 0-3.376-.447-4.808-1.23l-5.222 1.369 1.393-5.087c-.89-1.493-1.393-3.238-1.393-5.032 0-5.506 4.475-9.981 9.97-9.981 5.505 0 10.03 4.475 10.03 9.981z" />
                    </svg>
                    <span>WhatsApp Operador</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CORREDORES TURÍSTICOS Y DEPARTAMENTOS ESTRATÉGICOS -->
        <div class="mt-space-xl pt-space-xl border-t border-surface-container">
          <div class="max-w-3xl mb-space-lg">
            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest font-bold">LOGÍSTICA Y TIEMPOS DE TRASLADO</span>
            <h3 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold mt-1">
              Corredores Turísticos y Departamentos Estratégicos
            </h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2">
              Elegí la base de tu grupo según tu plan. Cada corredor cuenta con auditorías físicas periódicas para garantizar servicios reales (agua potable, cerco en piletas, electricidad apta para equipos).
            </p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
              v-for="(corr, cIdx) in corredoresTuristicos"
              :key="cIdx"
              class="p-6 rounded-2xl bg-surface-container-lowest hover:bg-surface-container-low transition-all flex flex-col justify-between border border-surface-container shadow-sm"
            >
              <div>
                <div class="w-12 h-12 rounded-xl bg-primary text-on-primary flex items-center justify-center mb-4 shadow-xs">
                  <span class="material-symbols-outlined text-[26px]">{{ corr.icon }}</span>
                </div>
                <div class="inline-flex items-center gap-1 text-label-sm font-label-sm text-secondary uppercase font-bold mb-1">
                  {{ corr.badge }}
                </div>
                <h4 class="font-headline-sm text-headline-sm text-primary mb-2 font-bold">
                  {{ corr.title }}
                </h4>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">
                  {{ corr.description }}
                </p>
                <ul class="space-y-2 mb-6 font-body-sm text-body-sm text-on-surface">
                  <li v-for="(p, pIdx) in corr.points" :key="pIdx" class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    <span>{{ p }}</span>
                  </li>
                </ul>
              </div>
              <RouterLink
                :to="{ path: '/explorar', query: { zone: corr.filterZone } }"
                class="w-full py-2.5 rounded-xl bg-surface-container hover:bg-primary hover:text-on-primary text-primary font-label-md text-label-md text-center transition-colors font-bold cursor-pointer border border-surface-container"
              >
                {{ corr.buttonText }}
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
