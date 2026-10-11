<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import BentoGallery from '../components/quintas/BentoGallery.vue'
import AuditorReportCard from '../components/quintas/AuditorReportCard.vue'
import CotizadorTurno from '../components/quintas/CotizadorTurno.vue'

const route = useRoute()

// Base de datos de quintas sincronizada
const quintasCatalog: Record<string, any> = {
  '1': {
    id: 1,
    title: 'Solo Día Quinta Los Olivos',
    location: 'Calle Ramón Franco s/n, El Medanito, Rawson, San Juan',
    distanceInfo: 'A sólo 15 min de San Juan Capital',
    department: 'Rawson (El Medanito)',
    rating: '4.9',
    familiesCount: 38,
    basePrice: 45000,
    capacity: 25,
    hours: '10:00 a 20:30',
    poolSpecs: '9 x 4.5 mts • Climatizada • Cerco 1.20m',
    auditorName: 'Ing. Marcos Quiroga',
    auditorCode: '#TS-044',
    inspectionDate: 'Feb 2026',
    hostWhatsapp: '5492644111222',
    images: [
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDA9g1_tjz0yz4bOBCodIUvmuTE0USNFSWBQm3WiTCkZfkcqvciNNc3RWNnxKa-oAEDSNHSudTeaLPzajUA3KgCfRkawh77NiV8yD7by81UjGqYbjTQhAcBFRIhYn8kNoPp3rbRxQmIMQoOAUCyiWUKs88rIR-qU3jG-fP9K5p4MwpafvSceEmjmKAfyDe1Ptsk8z37UdCVr68wejOfTWeRQgUWILu5335og8YMNplb3yluX3HC3fiW',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDGf_LdqlZMQO_JleFndbKaeDyKv-B6ssNrRTmucpaZ3JXY0z8VzwJDB3Gf7cMdjj8eVYG__H1wVHS95Kg7_aIN-YvyVqpoNzEA4KT1oWyUrHFcIop1LUeXJpeRxvPf8lGfIRat5RIwlluZZFhMhpjlpMRQHVLrd5bEkXt1ALHCQlFmYJkI0AfTNWBzf49UQYQ5waLj9VRVTYSJ6j1g1-fHwgPLDulp9xj-F9CtU1rmwUeuwnAtU4nY',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuBYV0fxDQV-C6_P2Y0j1YHYP1d5r1Yc545YTV-1g0C9W-EFltQdXMLGT2ATorvKl6PhG7S0SBsaFyX0FG3Uq9DAEaSKIl5n_V12Ls13Tc6PRTpuI2eqnZwuVBFuxXVTPt6cvN1mAlhXuqaQx1MZJztIOhHms4JMyJMv7Eg2IshpQa8PXpdOjuUE30pgpC8XPHgx8XqLywWLpX0ddKU10kmdNAxb8kVZwpuKVqfyio-vWoqvuPXZ20xV',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuCSDZuGaqXAxv762ba-0JpLqHgY8qXVCIuWMbt8Twc1kZBZImn0SheZcZSG34rwbJ0m4jwsrr69VwJx5KHkld9pdAmx07Kfc0MCoSrv0Wu3umQhc-R2t2nAl-wf0gyVqX5LdWo7ZwyiKlzJh5NHUIkjJRGeIjhyUyn-QhSTBRlE3noL86Wz4W0DrEY-_aNgf-JZrcN8ULb2XPaq2Goianu8hjWyWP_uybBujbHu16SMZEQshremZAkq',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDM3qXPOTeF2Prl_jFLj2Zi0RpDqhiZIFWVvRAmsbtj-qgrNJ65vIjoJYoekLqxJpeOJx3F5t1-wQzUQv7JWEMHOwBWnv9AOZ77e8XD0ZdKEoh5C4loJanMH0hahjb4XI3KHFZ2e0WphP_xm0uiSdH0CPwDgaxXVRnRB33oLG40j4DDuyfbTaJoPh-cTkKWu6qPB65qdL6eRvmjbg9Hq9FTFhs91nGagGAeLrBuTu17M4TjnZwrFW4d',
    ],
  },
  '2': {
    id: 2,
    title: 'La Posada del Valle • Vistas a la Sierra',
    location: 'Calle Las Moras, Villa Basilio Nievas, Zonda, San Juan',
    distanceInfo: 'A 25 min de San Juan Capital',
    department: 'Zonda',
    rating: '5.0',
    familiesCount: 42,
    basePrice: 55000,
    capacity: 30,
    hours: '11:00 a 21:00',
    poolSpecs: '10 x 5 mts • Solárium • Cerco perimetral',
    auditorName: 'Lic. Paula Gómez',
    auditorCode: '#TS-012',
    inspectionDate: 'Ene 2026',
    hostWhatsapp: '5492644222333',
    images: [
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDA9g1_tjz0yz4bOBCodIUvmuTE0USNFSWBQm3WiTCkZfkcqvciNNc3RWNnxKa-oAEDSNHSudTeaLPzajUA3KgCfRkawh77NiV8yD7by81UjGqYbjTQhAcBFRIhYn8kNoPp3rbRxQmIMQoOAUCyiWUKs88rIR-qU3jG-fP9K5p4MwpafvSceEmjmKAfyDe1Ptsk8z37UdCVr68wejOfTWeRQgUWILu5335og8YMNplb3yluX3HC3fiW',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuBYV0fxDQV-C6_P2Y0j1YHYP1d5r1Yc545YTV-1g0C9W-EFltQdXMLGT2ATorvKl6PhG7S0SBsaFyX0FG3Uq9DAEaSKIl5n_V12Ls13Tc6PRTpuI2eqnZwuVBFuxXVTPt6cvN1mAlhXuqaQx1MZJztIOhHms4JMyJMv7Eg2IshpQa8PXpdOjuUE30pgpC8XPHgx8XqLywWLpX0ddKU10kmdNAxb8kVZwpuKVqfyio-vWoqvuPXZ20xV',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuCSDZuGaqXAxv762ba-0JpLqHgY8qXVCIuWMbt8Twc1kZBZImn0SheZcZSG34rwbJ0m4jwsrr69VwJx5KHkld9pdAmx07Kfc0MCoSrv0Wu3umQhc-R2t2nAl-wf0gyVqX5LdWo7ZwyiKlzJh5NHUIkjJRGeIjhyUyn-QhSTBRlE3noL86Wz4W0DrEY-_aNgf-JZrcN8ULb2XPaq2Goianu8hjWyWP_uybBujbHu16SMZEQshremZAkq',
      'https://lh3.googleusercontent.com/aida-public/AB6AXuDM3qXPOTeF2Prl_jFLj2Zi0RpDqhiZIFWVvRAmsbtj-qgrNJ65vIjoJYoekLqxJpeOJx3F5t1-wQzUQv7JWEMHOwBWnv9AOZ77e8XD0ZdKEoh5C4loJanMH0hahjb4XI3KHFZ2e0WphP_xm0uiSdH0CPwDgaxXVRnRB33oLG40j4DDuyfbTaJoPh-cTkKWu6qPB65qdL6eRvmjbg9Hq9FTFhs91nGagGAeLrBuTu17M4TjnZwrFW4d',
    ],
  },
}

const currentId = computed(() => String(route.params.id || '1'))
const quinta = computed(() => quintasCatalog[currentId.value] || quintasCatalog['1'])
</script>

<template>
  <main class="w-full bg-background py-space-md min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 w-full">
      <!-- 1. Breadcrumbs -->
      <nav aria-label="Breadcrumb" class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md mb-space-md">
        <RouterLink to="/" class="hover:text-primary transition-colors flex items-center gap-1 font-bold">
          <span class="material-symbols-outlined text-[16px]">home</span>
          Inicio
        </RouterLink>
        <span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
        <span class="hover:text-primary transition-colors font-medium">{{ quinta.department }}</span>
        <span class="material-symbols-outlined text-[14px] text-outline-variant">chevron_right</span>
        <span class="text-on-surface font-semibold truncate">{{ quinta.title }}</span>
      </nav>

      <!-- 2. Property Header -->
      <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-space-md mb-space-lg">
        <div class="space-y-space-xs">
          <div class="flex flex-wrap items-center gap-space-xs">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/40 text-on-secondary-fixed font-label-sm text-label-sm shadow-sm font-bold">
              <span class="material-symbols-outlined text-[18px] text-secondary" style="font-variation-settings: 'FILL' 1;">verified_user</span>
              <span>VERIFICADA IN SITU POR AUDITOR TURISMO SEGURO</span>
              <span class="text-on-secondary-fixed-variant opacity-80">(Inspección: {{ quinta.inspectionDate }})</span>
            </div>
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm font-semibold">
              <span class="material-symbols-outlined text-[16px] text-amber-500">wb_sunny</span>
              Alquiler Diurno Exclusivo (Sin pernocte)
            </span>
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm font-mono font-bold">
              <span class="material-symbols-outlined text-[15px] text-primary">pin_drop</span>
              ID: SJ-RAW-{{ quinta.id }}
            </span>
          </div>

          <h1 class="font-headline-xl text-headline-xl text-primary tracking-tight font-extrabold">
            {{ quinta.title }}
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant flex items-center gap-1">
            <span class="material-symbols-outlined text-[20px] text-secondary">location_on</span>
            <span>{{ quinta.location }} — <em>{{ quinta.distanceInfo }}</em></span>
          </p>
        </div>

        <!-- Quick Trust Indicators -->
        <div class="flex items-center gap-space-md bg-surface-container-lowest p-space-sm rounded-xl shadow-sm self-start border border-surface-container">
          <div class="text-center px-space-sm border-r border-surface-container-highest">
            <div class="flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[20px]">handshake</span>
              <span class="font-headline-sm text-headline-sm font-bold ml-1">0%</span>
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">Trato Directo con Dueño</span>
          </div>
          <div class="text-center px-space-sm">
            <div class="flex items-center justify-center text-primary">
              <span class="material-symbols-outlined text-[20px] text-secondary" style="font-variation-settings: 'FILL' 1;">star</span>
              <span class="font-headline-sm text-headline-sm font-bold ml-1">{{ quinta.rating }}</span>
            </div>
            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ quinta.familiesCount }} Familias</span>
          </div>
        </div>
      </div>

      <!-- 3. Bento Grid Gallery Component -->
      <BentoGallery
        :images="quinta.images"
        :main-title="quinta.title"
        location-subtitle="Parque de 1.800 m² arbolado en San Juan"
      />

      <!-- 4. Content Split (65% / 35%) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">
        <!-- LEFT COLUMN (8 cols) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-space-xl">
          <!-- Ficha Técnica Rápida -->
          <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-surface-container">
            <div class="flex items-center justify-between pb-space-sm mb-space-md bg-surface-container-low px-4 py-3 rounded-xl border border-surface-container">
              <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2 font-bold">
                <span class="material-symbols-outlined text-secondary">assignment</span>
                Ficha Técnica de Uso Diurno
              </h2>
              <span class="font-label-sm text-label-sm text-secondary bg-surface-container-lowest px-2.5 py-1 rounded-full uppercase tracking-wider font-bold border border-secondary/30">
                Protocolo TS San Juan
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md mb-space-md">
              <div class="p-space-md bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm">
                  <span class="material-symbols-outlined text-[24px]">groups</span>
                </div>
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block font-bold">Capacidad Diurna</span>
                  <span class="font-headline-sm text-headline-sm text-on-surface font-extrabold">Hasta {{ quinta.capacity }} pers.</span>
                  <span class="font-body-sm text-body-sm text-on-surface-variant block text-xs">Exclusivo familiar</span>
                </div>
              </div>

              <div class="p-space-md bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm">
                  <span class="material-symbols-outlined text-[24px]">schedule</span>
                </div>
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block font-bold">Horario Estricto</span>
                  <span class="font-headline-sm text-headline-sm text-on-surface font-extrabold">{{ quinta.hours }}</span>
                  <span class="font-body-sm text-body-sm text-on-surface-variant block text-xs">Turno Día Completo</span>
                </div>
              </div>

              <div class="p-space-md bg-surface-container-low rounded-xl flex items-start gap-3 border border-surface-container">
                <div class="w-10 h-10 rounded-lg bg-surface-container-lowest flex items-center justify-center text-secondary shadow-sm">
                  <span class="material-symbols-outlined text-[24px]">pool</span>
                </div>
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block font-bold">Pileta Climatizada</span>
                  <span class="font-headline-sm text-headline-sm text-on-surface font-extrabold">{{ quinta.poolSpecs.split('•')[0] }}</span>
                  <span class="font-body-sm text-body-sm text-on-surface-variant block text-xs">Cerco de niños 1.20m</span>
                </div>
              </div>
            </div>

            <!-- Servicios de Pileta Incluidos -->
            <div class="p-space-md bg-secondary-fixed/20 rounded-xl border border-secondary/20">
              <h4 class="font-label-lg text-label-lg text-on-secondary-fixed mb-space-xs flex items-center gap-2 font-bold">
                <span class="material-symbols-outlined text-secondary text-[18px]">water</span>
                Servicios de Pileta Incluidos en tu Estadía
              </h4>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <div class="flex items-center gap-2 font-body-sm text-body-sm text-on-secondary-fixed-variant">
                  <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                  <span>Mantenimiento químico matutino</span>
                </div>
                <div class="flex items-center gap-2 font-body-sm text-body-sm text-on-secondary-fixed-variant">
                  <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                  <span>Cerco perimetral para niños</span>
                </div>
                <div class="flex items-center gap-2 font-body-sm text-body-sm text-on-secondary-fixed-variant">
                  <span class="material-symbols-outlined text-secondary text-[18px]">check_circle</span>
                  <span>Reposeras y sombrillón</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Equipamiento para Asados & Eventos -->
          <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm border border-surface-container">
            <h2 class="font-headline-sm text-headline-sm text-primary mb-space-sm flex items-center gap-2 font-bold">
              <span class="material-symbols-outlined text-secondary">outdoor_grill</span>
              Equipamiento para Asados &amp; Eventos
            </h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant mb-space-md">
              Instalaciones especialmente pensadas para almuerzos y festejos al aire libre en San Juan sin necesidad de llevar vajilla pesada.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
              <div class="flex items-start gap-3 p-space-sm bg-surface-container-low rounded-xl border border-surface-container">
                <div class="p-2 rounded-lg bg-surface-container-lowest text-primary">
                  <span class="material-symbols-outlined text-[24px]">fireplace</span>
                </div>
                <div>
                  <h3 class="font-label-lg text-label-lg text-on-surface font-bold">Parrilla Sanjuanina de 2 Metros</h3>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">Luz interior para el atardecer, sector leñero, bacha de acero y mesada auxiliar de mármol.</p>
                </div>
              </div>

              <div class="flex items-start gap-3 p-space-sm bg-surface-container-low rounded-xl border border-surface-container">
                <div class="p-2 rounded-lg bg-surface-container-lowest text-primary">
                  <span class="material-symbols-outlined text-[24px]">kitchen</span>
                </div>
                <div>
                  <h3 class="font-label-lg text-label-lg text-on-surface font-bold">Refrigeración Doble Capacidad</h3>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">2 Freezers verticales exclusivos para barras de bebidas/hielo + heladera familiar con freezer.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Componente Extraído: Reporte del Auditor Oficial -->
          <AuditorReportCard
            :auditor-name="quinta.auditorName"
            :auditor-code="quinta.auditorCode"
            :inspection-date="quinta.inspectionDate"
          />
        </div>

        <!-- RIGHT COLUMN: CotizadorTurno Sticky Component -->
        <div class="lg:col-span-5 xl:col-span-4">
          <CotizadorTurno
            :base-price="quinta.basePrice"
            :quinta-title="quinta.title"
            :quinta-id="quinta.id"
            :host-whatsapp="quinta.hostWhatsapp"
          />
        </div>
      </div>
    </div>
  </main>
</template>
