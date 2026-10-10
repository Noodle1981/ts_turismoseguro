<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'

// Pestaña activa de evento
const activeTab = ref('fns')

// Filtros
const selectedAnchor = ref('fns')
const selectedRadius = ref('30')
const selectedTypology = ref('quinta')

const eventsData: Record<string, any> = {
  fns: {
    title: 'Fiesta Nacional del Sol 2026',
    badge: 'FNS 2026',
    description: 'Alquileres diurnos, previas de asado y quintas para delegaciones cerca del Predio Ferial y Estadio San Juan del Bicentenario.',
    quintas: [
      {
        id: 1,
        title: 'Quinta Los Olivos (Previa FNS)',
        location: 'El Medanito, Rawson (A 12 min del Predio)',
        price: '$45.000',
        capacity: '25 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
        whatsapp: '5492644111222',
      },
      {
        id: 3,
        title: 'Quincho & Parque Don Antonio',
        location: 'Pocito Sur (A 15 min por RN 40)',
        price: '$38.000',
        capacity: '40 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDy9PLSFQrRBslTy_bvrA-1TCT9yqpH3AOaIgMErqcdnTNQIurXvbPz7rgzvuqQygWTMNM3lWQl1YdQxlP_uKXm1OEN5W99s8w2qShVbdnAQUHMTaBGvIVDuE7F1IoBk_Dse-4TnvUcYoCTEqytxmZA4zJ0l2d0E2d6cFrc6xaRLIQT_JKgzZuhQCKdbJFlKOjHdx7pe3M-3Myd19vWSDUrTna7yHad2MKpYNtg7DdvqkMl3Bt1DVxq',
        whatsapp: '5492644333444',
      },
    ],
  },
  diques: {
    title: 'Diques Ullum & Punta Negra (Ruta Interlagos)',
    badge: 'Náutica & Sunset',
    description: 'Quintas y cabañas con bajada a dique, paseos en kayak y solárium panorámico sobre los espejos de agua sanjuaninos.',
    quintas: [
      {
        id: 4,
        title: 'Rincón de la Sierra (Sobre el Dique)',
        location: 'Dique de Ullum (Frente al embarcadero)',
        price: '$65.000',
        capacity: '20 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAd2EiOfveL3i9TOsDt_6jA0bkXgBOha_sUJ_2PXl84NszF67_xG-0qf9N1k0ywlm8O1rvG0uARNxvVxBZWdJPGIYgvzMOn7YEjGUdSknqiEQbNYhkgnxvrVimvoylJD9JyRit-6mvV0f7IrJR2DPMtXtu568sf1ykKlTTf7OPG8a3b8Ma8OZZqNR3kbQY9dMAxs_CNhkwnDJVKPCjQmovW3_Fr4ZFEotLw6jrrPLDjYGcRMsRlyHEd',
        whatsapp: '5492644444555',
      },
    ],
  },
  astro: {
    title: 'Astroturismo en El Leoncito & Barreal',
    badge: 'Cielos Limpios',
    description: 'Casonas de montaña y posadas con cielo nocturno certificado en el Valle de Calingasta.',
    quintas: [
      {
        id: 2,
        title: 'La Posada del Valle Zonda',
        location: 'Valle de Zonda (Portal hacia la cordillera)',
        price: '$55.000',
        capacity: '30 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV',
        whatsapp: '5492644222333',
      },
    ],
  },
  villicum: {
    title: 'Autódromo El Villicum (Superbike & TC)',
    badge: 'Deporte Motor',
    description: 'Quintas y cabañas en Albardón y Santa Lucía con rápido acceso por Ruta Nacional 40 hacia el circuito.',
    quintas: [
      {
        id: 1,
        title: 'Quinta Los Olivos',
        location: 'Acceso Sur / Autopista 40',
        price: '$45.000',
        capacity: '25 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
        whatsapp: '5492644111222',
      },
    ],
  },
  ischigualasto: {
    title: 'Valle de la Luna & Ruta del Vino',
    badge: 'Patrimonio de la Humanidad',
    description: 'Turismo arqueológico y bodegas boutique en San Juan con prestadores certificados in situ.',
    quintas: [
      {
        id: 2,
        title: 'La Posada del Valle',
        location: 'Zonda (Ruta del Vino Sanjuanino)',
        price: '$55.000',
        capacity: '30 personas',
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV',
        whatsapp: '5492644222333',
      },
    ],
  },
}

const currentEvent = computed(() => eventsData[activeTab.value] || eventsData['fns'])

const selectTab = (tab: string) => {
  activeTab.value = tab
  selectedAnchor.value = tab
}
</script>

<template>
  <main class="w-full bg-background min-h-screen">
    <!-- Top Ambient Banner & Hero Section -->
    <section class="relative w-full overflow-hidden bg-surface-container-low py-space-xl lg:py-24">
      <div class="absolute -top-32 -left-20 w-96 h-96 bg-secondary-fixed/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute top-1/2 -right-32 w-[32rem] h-[32rem] bg-tertiary-fixed/30 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
        <!-- Breadcrumb / Anti-Scam Assurance Pill -->
        <div class="flex flex-wrap items-center gap-space-sm mb-space-md">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/40 text-on-secondary-fixed font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
            TURISMO SEGURO SAN JUAN • RED AUDITADA 2026
          </span>
          <span class="text-on-surface-variant font-label-md text-label-md hidden sm:inline-flex items-center gap-1 font-semibold">
            <span class="material-symbols-outlined text-[14px]">shield_lock</span>
            CBU e identidad titular cotejados in situ
          </span>
        </div>

        <!-- Main Heading & Value Proposition -->
        <div class="max-w-4xl">
          <h1 class="font-headline-xl text-headline-xl text-primary tracking-tight mb-space-sm font-extrabold">
            Viví San Juan con Seguridad: <br class="hidden sm:inline"/>
            <span class="text-secondary">Experiencias, Grandes Eventos</span> y Estadías Verificadas
          </h1>
          <p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mb-space-lg">
            Conectá el turismo activo de montaña y los festivales emblemáticos con quintas, cabañas, quinchos y casas de campo inspeccionadas físicamente en los departamentos aledaños. Cero perfiles falsos, trato directo y seña asegurada.
          </p>
        </div>

        <!-- Interactive Tab Bar: Motivo de Viaje -->
        <div class="bg-surface-container-lowest p-2 rounded-2xl shadow-sm mb-space-md inline-flex flex-wrap gap-1 border border-surface-container">
          <button
            type="button"
            :class="[
              'flex items-center gap-2 px-4 py-2.5 rounded-xl font-label-md text-label-md transition-all font-bold cursor-pointer',
              activeTab === 'fns' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
            ]"
            @click="selectTab('fns')"
          >
            <span class="text-base">🎪</span>
            <span>Fiesta Nacional del Sol</span>
            <span class="bg-secondary text-on-secondary text-[10px] px-1.5 py-0.5 rounded-full font-bold">FNS</span>
          </button>

          <button
            type="button"
            :class="[
              'flex items-center gap-2 px-4 py-2.5 rounded-xl font-label-md text-label-md transition-all font-bold cursor-pointer',
              activeTab === 'diques' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
            ]"
            @click="selectTab('diques')"
          >
            <span class="text-base">🏄</span>
            <span>Diques Ullum &amp; Punta Negra</span>
          </button>

          <button
            type="button"
            :class="[
              'flex items-center gap-2 px-4 py-2.5 rounded-xl font-label-md text-label-md transition-all font-bold cursor-pointer',
              activeTab === 'astro' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
            ]"
            @click="selectTab('astro')"
          >
            <span class="text-base">✨</span>
            <span>Astroturismo El Leoncito</span>
          </button>

          <button
            type="button"
            :class="[
              'flex items-center gap-2 px-4 py-2.5 rounded-xl font-label-md text-label-md transition-all font-bold cursor-pointer',
              activeTab === 'villicum' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
            ]"
            @click="selectTab('villicum')"
          >
            <span class="text-base">🏎️</span>
            <span>Autódromo El Villicum</span>
          </button>

          <button
            type="button"
            :class="[
              'flex items-center gap-2 px-4 py-2.5 rounded-xl font-label-md text-label-md transition-all font-bold cursor-pointer',
              activeTab === 'ischigualasto' ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
            ]"
            @click="selectTab('ischigualasto')"
          >
            <span class="text-base">🧗</span>
            <span>Valle de la Luna</span>
          </button>
        </div>

        <!-- Comprehensive Proximity Filter Hub -->
        <div class="bg-surface-container-lowest p-5 lg:p-7 rounded-2xl shadow-md border border-surface-container">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <!-- Event / Focus Dropdown -->
            <div class="flex flex-col gap-1.5">
              <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider flex items-center gap-1 font-bold">
                <span class="material-symbols-outlined text-[16px] text-secondary">festival</span>
                Evento o Atracción Ancla
              </label>
              <div class="relative">
                <select
                  v-model="selectedAnchor"
                  class="w-full h-12 pl-3 pr-8 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm appearance-none focus:outline-none focus:bg-surface-container cursor-pointer font-medium"
                >
                  <option value="fns">Fiesta Nacional del Sol (Predio FNS / Bicentenario)</option>
                  <option value="diques">Dique de Ullum &amp; Ruta Interlagos</option>
                  <option value="astro">Observatorios Astronómicos de Calingasta</option>
                  <option value="villicum">Superbike &amp; TC en El Villicum (Albardón)</option>
                  <option value="ischigualasto">Parque Triásico Ischigualasto</option>
                </select>
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-3 text-on-surface-variant">expand_more</span>
              </div>
            </div>

            <!-- Maximum Distance -->
            <div class="flex flex-col gap-1.5">
              <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider flex items-center gap-1 font-bold">
                <span class="material-symbols-outlined text-[16px] text-secondary">commute</span>
                Radio de Cercanía Máximo
              </label>
              <div class="relative">
                <select
                  v-model="selectedRadius"
                  class="w-full h-12 pl-3 pr-8 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm appearance-none focus:outline-none focus:bg-surface-container cursor-pointer font-medium"
                >
                  <option value="15">Hasta 15 min en auto (Inmediaciones)</option>
                  <option value="30">Hasta 30 min (Departamentos vecinos)</option>
                  <option value="45">Hasta 45 min (Acceso por autopista / ruta)</option>
                </select>
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-3 text-on-surface-variant">expand_more</span>
              </div>
            </div>

            <!-- Property Typology -->
            <div class="flex flex-col gap-1.5">
              <label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider flex items-center gap-1 font-bold">
                <span class="material-symbols-outlined text-[16px] text-secondary">cottage</span>
                Tipo de Espacio Deseado
              </label>
              <div class="relative">
                <select
                  v-model="selectedTypology"
                  class="w-full h-12 pl-3 pr-8 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm appearance-none focus:outline-none focus:bg-surface-container cursor-pointer font-medium"
                >
                  <option value="quinta">Quinta con pileta cercada y parque</option>
                  <option value="quincho">Quincho para previa/asado (Día completo)</option>
                  <option value="cabana">Cabaña / Casona de descanso con pernocte</option>
                </select>
                <span class="material-symbols-outlined pointer-events-none absolute right-3 top-3 text-on-surface-variant">expand_more</span>
              </div>
            </div>

            <!-- Search Action CTA -->
            <div class="flex">
              <button
                type="button"
                class="w-full h-12 rounded-xl bg-secondary hover:bg-on-secondary-container text-on-secondary font-label-lg text-label-lg flex items-center justify-center gap-2 shadow-sm transition-all font-bold cursor-pointer"
              >
                <span class="material-symbols-outlined text-[20px]">explore</span>
                <span>Explorar Alojamientos</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Special Showcase: FNS & Nearby Accommodations -->
    <section class="w-full bg-surface-container-lowest py-space-xl">
      <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <!-- Section Announcement Card -->
        <div class="bg-primary-container text-on-primary p-6 lg:p-10 rounded-2xl relative overflow-hidden shadow-lg mb-space-lg">
          <div class="absolute -right-10 -bottom-10 w-80 h-80 rounded-full bg-secondary/15 blur-2xl pointer-events-none"></div>
          <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-8">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm mb-3 font-bold">
                <span class="material-symbols-outlined text-[16px]">celebration</span>
                {{ currentEvent.badge }}
              </div>
              <h2 class="font-headline-lg text-headline-lg text-on-primary mb-space-xs font-bold">
                {{ currentEvent.title }}
              </h2>
              <p class="font-body-md text-body-md text-on-primary-container max-w-2xl leading-relaxed">
                {{ currentEvent.description }}
              </p>
            </div>
            <div class="lg:col-span-4 flex flex-col gap-3 bg-surface-container-lowest/10 backdrop-blur-md p-5 rounded-xl border border-white/10">
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary-fixed text-[28px]">verified</span>
                <div>
                  <p class="font-label-lg text-label-lg text-on-primary font-bold">Blindaje Anti-Estafas</p>
                  <p class="font-body-sm text-body-sm text-on-primary-container">Sin seña a cuentas fantasma</p>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary-fixed text-[28px]">speed</span>
                <div>
                  <p class="font-label-lg text-label-lg text-on-primary font-bold">A menos de {{ selectedRadius }} min</p>
                  <p class="font-body-sm text-body-sm text-on-primary-container">Cercanía directa al evento</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Event Nearby Quintas Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
          <article
            v-for="q in currentEvent.quintas"
            :key="q.id"
            class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between border border-surface-container"
          >
            <div>
              <div class="relative h-64 overflow-hidden">
                <img
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                  :src="q.image"
                  :alt="q.title"
                />
                <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md font-bold">
                  <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                  <span class="font-label-sm text-label-sm">Auditado In Situ</span>
                </div>
                <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1 font-bold">
                  <span class="material-symbols-outlined text-[14px]">location_on</span>
                  {{ q.location }}
                </div>
              </div>

              <div class="p-space-lg">
                <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                  <h3 class="font-headline-md text-headline-md text-primary font-bold">
                    {{ q.title }}
                  </h3>
                  <div class="text-right">
                    <div class="font-headline-md text-headline-md text-secondary font-bold">{{ q.price }}</div>
                    <div class="font-label-sm text-label-sm text-on-surface-variant">/ jornada</div>
                  </div>
                </div>
                <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                  <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                  <span class="font-label-md text-label-md font-semibold text-on-surface">Capacidad: Hasta {{ q.capacity }}</span>
                </div>
              </div>
            </div>

            <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
              <RouterLink
                :to="`/quintas/${q.id}`"
                class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
              >
                <span class="material-symbols-outlined text-[18px]">info</span> Ver Disponibilidad
              </RouterLink>
              <a
                :href="`https://wa.me/${q.whatsapp}?text=Hola,%20vi%20tu%20quinta%20o%20caba%C3%B1a%20para%20el%20evento%20en%20Turismo%20Seguro`"
                target="_blank"
                rel="noopener noreferrer"
                class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm cursor-pointer"
                title="WhatsApp Directo"
              >
                <span class="material-symbols-outlined text-[20px]">chat</span>
              </a>
            </div>
          </article>
        </div>
      </div>
    </section>
  </main>
</template>
