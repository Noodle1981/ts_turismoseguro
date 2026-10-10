<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'

// 1. Selector de tipo principal: 'eventos' o 'turisticos'
const activeMainCategory = ref<'eventos' | 'turisticos'>('eventos')

// 2. Elemento seleccionado dentro de la categoría
const selectedItemKey = ref('fns')

// Modelos de datos para Eventos Culturales / Deportivos
interface ProximityLodging {
  id: number
  title: string
  tipo: string
  location: string
  distance: string
  price: number
  capacity: number
  image: string
  whatsapp: string
  acta: string
  amenities: string[]
}

interface AttractionData {
  key: string
  title: string
  subtitle: string
  department: string
  badge: string
  category: 'eventos' | 'turisticos'
  description: string
  officialOperator?: string
  lodgingsCount: number
  lodgings: ProximityLodging[]
}

const ofertasCatalogo: Record<string, AttractionData> = {
  // PARTE 1: EVENTOS DESTACADOS
  fns: {
    key: 'fns',
    title: 'Fiesta Nacional del Sol 2026',
    subtitle: 'Predio Ferial Costanera & Estadio del Bicentenario',
    department: 'Chimbas / Pocito',
    badge: 'FNS 2026 • Mayor Convocatoria',
    category: 'eventos',
    description: 'La máxima fiesta sanjuanina. Previas de asados, estadías para delegaciones y descansos familiares con acceso rápido directo por RN 40.',
    lodgingsCount: 14,
    lodgings: [
      {
        id: 1,
        title: 'Quinta Los Sauces (Previa FNS)',
        tipo: 'Quinta con Gran Quincho',
        location: 'Pocito • Calle 11',
        distance: 'A 12 min del Estadio Bicentenario',
        price: 85000,
        capacity: 40,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDVXAhfsrz9RqqrhjElGsZFj_z2M5Ym0gyakTGqUQB8rX0d54LTB6l34pYRNLoxip77iA5ZkWclq6hNrsfgJqb3s_lQ4y4Jxv_XHciMHNVEzFgDtn1s7DQadZ8Z31DfHrw0a1QGX6CSrnFmiacozYmlV2mU47idZID1u9srmemQEj6nmG0lcNJ086KpgFw78efBrFepRBWLdibOm70rNMCRxE_PblSNO246AExw4x-Kdw2Ux_99pkqA',
        whatsapp: '5492645892140',
        acta: 'Acta #812-POC/26',
        amenities: ['Pileta con cerco homologado', 'Parrillero doble', 'Freezer vertical', 'Sombra de olivos']
      },
      {
        id: 2,
        title: 'Quincho Familiar El Solar',
        tipo: 'Quincho Climatizado',
        location: 'Santa Lucía • Zona Viñedos',
        distance: 'A 8 min de Predio Costanera',
        price: 68000,
        capacity: 30,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCrYnw6VToBVxaVDwQOi8HVTlqLx5JJQ7FyHx7f7LtYO_nNK1tr_czHaaeva8SUfYh4lEbKkiMCtyME2M993Pj6K0xIQaPhE5Z_pnnetqDhpPBQUExdAW6cUSy0ey68D1ZTUdFUvUzaKn0_C48d_1gxbAOwws_G_lexpTjc1FkHMhrMx1yPCf_vdpKX2z_cCx06nrN_A8AGQpDsOiMIOScqadESlr7y5e7d7uqLGtNd0WDx0bLxbXlw',
        whatsapp: '5492644222333',
        acta: 'Acta #532-SLU/26',
        amenities: ['Pileta cercada', 'Galería abierta', 'Vajilla 30 personas', 'Wifi Fibra Óptica']
      },
      {
        id: 3,
        title: 'Casona Sanjuanina & Parque',
        tipo: 'Cabaña / Casona Colonial',
        location: 'Rawson • Médano de Oro',
        distance: 'A 15 min por Calle Ramón Franco',
        price: 95000,
        capacity: 35,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuC1F3wXz330G8s9f6T23xYI1P3L1Z6YffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
        whatsapp: '5492645023341',
        acta: 'Acta #644-RAW/26',
        amenities: ['Piscina 10x4m', 'Horno de barro', 'Cochera cubierta', 'Sector fogón nocturno']
      }
    ]
  },
  villicum: {
    key: 'villicum',
    title: 'Autódromo Circuito San Juan Villicum',
    subtitle: 'Superbike Mundial, Turismo Carretera & TC2000',
    department: 'Albardón',
    badge: 'Deporte Motor Internacional',
    category: 'eventos',
    description: 'El templo del automovilismo y motociclismo de Cuyo. Quintas y cabañas con rápido enlace sobre RN 40 Norte para equipos y fanáticos.',
    lodgingsCount: 8,
    lodgings: [
      {
        id: 4,
        title: 'Finca Los Algarrobos del Norte',
        tipo: 'Quinta con Pileta & Tinglado',
        location: 'Albardón • Villa San Martín',
        distance: 'A 6 min directo al circuito',
        price: 72000,
        capacity: 35,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDVXAhfsrz9RqqrhjElGsZFj_z2M5Ym0gyakTGqUQB8rX0d54LTB6l34pYRNLoxip77iA5ZkWclq6hNrsfgJqb3s_lQ4y4Jxv_XHciMHNVEzFgDtn1s7DQadZ8Z31DfHrw0a1QGX6CSrnFmiacozYmlV2mU47idZID1u9srmemQEj6nmG0lcNJ086KpgFw78efBrFepRBWLdibOm70rNMCRxE_PblSNO246AExw4x-Kdw2Ux_99pkqA',
        whatsapp: '5492644778899',
        acta: 'Acta #914-ALB/26',
        amenities: ['Estacionamiento para 10 autos', 'Asador criollo cubierto', 'Pileta con agua de pozo', 'Wifi satelital']
      },
      {
        id: 5,
        title: 'Cabaña & Quincho Las Moras Norte',
        tipo: 'Cabaña con Parque',
        location: 'Chimbas • Límite Albardón',
        distance: 'A 10 min por Ruta 40',
        price: 58000,
        capacity: 25,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCrYnw6VToBVxaVDwQOi8HVTlqLx5JJQ7FyHx7f7LtYO_nNK1tr_czHaaeva8SUfYh4lEbKkiMCtyME2M993Pj6K0xIQaPhE5Z_pnnetqDhpPBQUExdAW6cUSy0ey68D1ZTUdFUvUzaKn0_C48d_1gxbAOwws_G_lexpTjc1FkHMhrMx1yPCf_vdpKX2z_cCx06nrN_A8AGQpDsOiMIOScqadESlr7y5e7d7uqLGtNd0WDx0bLxbXlw',
        whatsapp: '5492644111222',
        acta: 'Acta #731-CHM/26',
        amenities: ['Parrilla techada', 'Freezer cervecero', 'Piscina cercada', 'Canchita de fútbol tenis']
      }
    ]
  },

  // PARTE 2: LUGARES TURÍSTICOS Y EXPERIENCIAS
  ullum: {
    key: 'ullum',
    title: 'Diques de Ullum y Punta Negra (Ruta Interlagos)',
    subtitle: 'Turismo Náutico, Kayak, Windsurf y Atardeceres',
    department: 'Ullum & Zonda',
    badge: 'Interlagos • Espejos de Agua',
    category: 'turisticos',
    description: 'La gran postal cordillerana sobre el lago. Alquiler de cabañas y quintas con bajada privada, muelles y soláriums panorámicos.',
    officialOperator: 'Náutica & Kayak San Juan (Matr. 84/26)',
    lodgingsCount: 11,
    lodgings: [
      {
        id: 6,
        title: 'Rincón de la Sierra (Sobre el Dique)',
        tipo: 'Cabaña & Deck Panorámico',
        location: 'Dique de Ullum • Costa Este',
        distance: 'A 2 min de embarcaderos náuticos',
        price: 65000,
        capacity: 20,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAd2EiOfveL3i9TOsDt_6jA0bkXgBOha_sUJ_2PXl84NszF67_xG-0qf9N1k0ywlm8O1rvG0uARNxvVxBZWdJPGIYgvzMOn7YEjGUdSknqiEQbNYhkgnxvrVimvoylJD9JyRit-6mvV0f7IrJR2DPMtXtu568sf1ykKlTTf7OPG8a3b8Ma8OZZqNR3kbQY9dMAxs_CNhkwnDJVKPCjQmovW3_Fr4ZFEotLw6jrrPLDjYGcRMsRlyHEd',
        whatsapp: '5492644444555',
        acta: 'Acta #311-ULL/26',
        amenities: ['Bajada privada para kayaks', 'Deck sobre el lago', 'Parrillero móvil', 'Mirador al atardecer']
      },
      {
        id: 7,
        title: 'Posada Los Morteritos (Ruta Interlagos)',
        tipo: 'Quinta de Montaña con Cabaña',
        location: 'Zonda • Portal al Dique Punta Negra',
        distance: 'A 5 min de Punta Negra Sunset',
        price: 78000,
        capacity: 25,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV',
        whatsapp: '5492644888123',
        acta: 'Acta #429-ZON/26',
        amenities: ['Piscina con cascada', 'Gran quincho de caña', 'Parrillero doble', 'Aire acondicionado']
      }
    ]
  },
  leoncito: {
    key: 'leoncito',
    title: 'Astroturismo en El Leoncito & Barreal',
    subtitle: 'Parque Nacional El Leoncito • CASLEO y CESCO',
    department: 'Calingasta (Precordillera)',
    badge: 'Cielos Más Limpios del Mundo',
    category: 'turisticos',
    description: 'Uno de los centros astronómicos más importantes de Sudamérica. Cabañas y posadas cordilleranas a minutos de los observatorios.',
    officialOperator: 'AstroTours SJ (Certificado Provincial)',
    lodgingsCount: 9,
    lodgings: [
      {
        id: 8,
        title: 'Posada de los Astros Cordilleranos',
        tipo: 'Cabaña & Observación Nocturna',
        location: 'Barreal • Calle Los Enamorados',
        distance: 'A 18 min de Casleo y El Leoncito',
        price: 82000,
        capacity: 18,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAPnzDWGYQ354AVnodnxyTLNZMOgfuo-Sy8hpqHOKc9FadPYVVeBsoVxTSmylVk9bj1lP3wfDtwIaSlcMhodiMk6SCP9ZAv2LaGA8rd6k_TqtSN5by8lFl_IbWALWlKg2Gp3yp98VrpOA2ep90spHRWKo9F8uz3MobH0Q6a7zUow6tAkoGeSkjC1c46B3dGbI-fZsGbsr66JSiv1UrIntV4scLHEjxYflTcEtUuRGkTS_iKJ4ZNWG3l',
        whatsapp: '5492644333999',
        acta: 'Acta #104-CAL/26',
        amenities: ['Terraza de astrofotografía', 'Estufa a leña', 'Parque con sauces', 'Parrillero cubierto']
      }
    ]
  },
  cuesta: {
    key: 'cuesta',
    title: 'Cuesta del Viento (Kitesurf & Windsurf)',
    subtitle: 'Dique Cuesta del Viento • Rodeo',
    department: 'Iglesia (Norte Cordillerano)',
    badge: 'Meca Mundial del Viento',
    category: 'turisticos',
    description: 'Epicentro internacional de deportes eólicos y aventura. Alojamientos auditados con guardería de tablas y bajada directa a playa.',
    officialOperator: 'Viento Extremo Cuyo (Matr. IKO 19/26)',
    lodgingsCount: 7,
    lodgings: [
      {
        id: 9,
        title: 'Cabañas del Viento y Cordillera',
        tipo: 'Complejo de Cabañas & Playa',
        location: 'Rodeo • Frente a Cuesta del Viento',
        distance: 'A 3 min de la playa de despegue',
        price: 90000,
        capacity: 22,
        image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAMRaIEylxpe-zO_9hNKTLEiJ1pHRyyGHiEt8qr5hAMOpydHe9eMYO2F-zkwt3Im_1ihPBcknKcD8NwZDlo3KZxA29ibNbF5yCBqmiZLwkR-1FvQ7MH2RTNpJh2iXT38WeEkaizlUo13TBoExSzr4t6QF8TdEJVD-w2qFAOZ4wcxzBu0xG2uEZxER20pL_D-yil_-InKlxvcXfgomwfK5xIyFkWb_f0ShxcXJQsEtwB-u2GjSb6cedr',
        whatsapp: '5492644123456',
        acta: 'Acta #207-IGL/26',
        amenities: ['Guardería de equipo náutico', 'Piscina protegida de viento', 'Parrillero techado', 'Wifi satelital']
      }
    ]
  }
}

// Opciones disponibles para la pestaña activa
const activeAttraction = computed(() => {
  return ofertasCatalogo[selectedItemKey.value] || ofertasCatalogo.fns
})

const handleCategoryChange = (category: 'eventos' | 'turisticos') => {
  activeMainCategory.value = category
  if (category === 'eventos') {
    selectedItemKey.value = 'fns'
  } else {
    selectedItemKey.value = 'ullum'
  }
}

// DATA: Experiencias Únicas de Naturaleza & Aventura (Foto 1 del usuario)
interface OperadorExperiencia {
  id: string
  title: string
  locationTag: string
  operator: string
  duration: string
  description: string
  image: string
  features: string[]
  lodgingCount: number
  lodgingZone: string
  whatsapp: string
}

const experienciasAventura: OperadorExperiencia[] = [
  {
    id: 'exp-leoncito',
    title: 'Tour de las Estrellas en Parque Nacional El Leoncito',
    locationTag: 'Barreal, Calingasta',
    operator: 'AstroTours SJ',
    duration: '4 hs',
    description: 'Observación telescópica profesional bajo uno de los cielos más diáfanos del planeta (CASLEO / CESCO). Incluye charla astronómica, astrofotografía y copa de vino sanjuanino.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAPnzDWGYQ354AVnodnxyTLNZMOgfuo-Sy8hpqHOKc9FadPYVVeBsoVxTSmylVk9bj1lP3wfDtwIaSlcMhodiMk6SCP9ZAv2LaGA8rd6k_TqtSN5by8lFl_IbWALWlKg2Gp3yp98VrpOA2ep90spHRWKo9F8uz3MobH0Q6a7zUow6tAkoGeSkjC1c46B3dGbI-fZsGbsr66JSiv1UrIntV4scLHEjxYflTcEtUuRGkTS_iKJ4ZNWG3l',
    features: ['Guía Astronómico', 'Telescopio Meade 14"', 'Seguro Aventura', 'Degustación Valle'],
    lodgingCount: 11,
    lodgingZone: 'Barreal',
    whatsapp: '5492644333999'
  },
  {
    id: 'exp-cuesta',
    title: 'Windsurf y Kitesurf en Cuesta del Viento',
    locationTag: 'Rodeo, Iglesia',
    operator: 'Viento Extremo',
    duration: 'Clases y alquiler',
    description: 'La meca mundial de los deportes eólicos. Clases de iniciación bautismo o alquiler de equipo de última generación con lancha de rescate activa permanentemente.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAMRaIEylxpe-zO_9hNKTLEiJ1pHRyyGHiEt8qr5hAMOpydHe9eMYO2F-zkwt3Im_1ihPBcknKcD8NwZDlo3KZxA29ibNbF5yCBqmiZLwkR-1FvQ7MH2RTNpJh2iXT38WeEkaizlUo13TBoExSzr4t6QF8TdEJVD-w2qFAOZ4wcxzBu0xG2uEZxER20pL_D-yil_-InKlxvcXfgomwfK5xIyFkWb_f0ShxcXJQsEtwB-u2GjSb6cedr',
    features: ['Instructor IKO Certificado', 'Traje Neoprene y Casco', 'Lancha de Rescate', 'Asistencia en Playa'],
    lodgingCount: 7,
    lodgingZone: 'Rodeo',
    whatsapp: '5492644123456'
  },
  {
    id: 'exp-punta-negra',
    title: 'Kayak y Travesía Sunset en Dique Punta Negra',
    locationTag: 'Ruta Interlagos (Ullum/Zonda)',
    operator: 'Náutica San Juan',
    duration: '2.5 hs',
    description: 'Paseo en kayaks sit-on-top dobles o simples recorriendo los cañadones vírgenes del dique al atardecer. Ideal para familias y grupos de amigos que buscan relax diurno.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuD1VKuXBof6RE-Qkl2kxr2xAk0wF984oE8ki0aIFz9L4-HBANBR_xmDLWh3iHUhxJi4t7W1FY-Rmqe_pf9lC6iRvXiqsaHnVFCQ9uI47g5lMo8ETa762aDdr24ngRma3BFK5AXOJkNpIb1cz0TemviaBikeA5NVtUufod3hqAJO22XcHiH-EXnzXipRBiFILZ4pvl0iXMAxctuLWORR53oL51aUif8TqTqCdXj3cXYixi5bXro-WF5w',
    features: ['Kayaks y Chalecos DAF', 'Guía Guardavidas', 'Seguro Náutico', 'Merienda y Mateada'],
    lodgingCount: 16,
    lodgingZone: 'Ullum y Zonda',
    whatsapp: '5492644444555'
  },
  {
    id: 'exp-ischigualasto',
    title: 'Expedición Paleontológica Ischigualasto',
    locationTag: 'Valle Fértil',
    operator: 'Guías Matriculados Parque',
    duration: 'Circuito Tradicional & Luna Llena',
    description: 'Patrimonio Natural de la Humanidad UNESCO. Recorrido vehicular y pedestre por las geoformas del Triásico (El Hongo, El Submarino, Cancha de Bochas) con guías de sitio oficiales.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBugCA6er70W_F7D3o54zqJSGcqRw9c8L-DEuJxBkpmo_LqBDuCPiXTlIfOP_cnKlCVoo0Tl1WGMcMeJ4JocnleXo5BeeFqphEWwWQ_oVtgFFR1rAAnOSrXQdQxgtKFIuP28RmX5NkdCtEMkVRorT7ZqzUS1nAhHOATixOT4lCE93jStAbdaPaRxPnjkeXNwbf68rI6DhMKgM_fM2w_momA-_XuoJs49EOcnqhJJLbQ7S5iS8bkrAOS',
    features: ['Entrada y Tasa Park', 'Guía Paleontólogo', 'Circuito 3.5 horas', 'Asistencia en Ruta'],
    lodgingCount: 9,
    lodgingZone: 'San Agustín',
    whatsapp: '5492645889900'
  }
]

// DATA: Corredores Turísticos y Departamentos Estratégicos (Foto 2 del usuario)
const corredoresTuristicos = [
  {
    icon: 'location_city',
    badge: 'TIEMPO MEDIO AL EVENTO: 10 - 20 MIN',
    title: 'Corredor Gran San Juan',
    description: 'Capital, Santa Lucía, Chimbas, Rawson, Rivadavia y Pocito Norte. Ideal para la Fiesta Nacional del Sol, eventos en el Estadio del Bicentenario y previas urbanas de asados familiares.',
    points: [
      '28 Quintas verificadas para pasar el día',
      'Acceso inmediato a autovías iluminadas',
      'Delivery y servicios comerciales 24hs'
    ],
    buttonText: 'Explorar Gran San Juan (28)',
    filterZone: 'rawson'
  },
  {
    icon: 'water',
    badge: 'TIEMPO MEDIO AL AGUA: 5 - 15 MIN',
    title: 'Ruta Interlagos & Embalses',
    description: 'Departamentos de Ullum y Zonda. El polo veraniego de relax, clubes náuticos, paseos en kayak, senderos en la Quebrada de Zonda y quintas con vista a las sierras.',
    points: [
      '16 Casas quintas y quinchos auditados',
      'Bajada náutica a menos de 10 min',
      'Microclima fresco al pie de la montaña'
    ],
    buttonText: 'Explorar Ullum & Zonda (16)',
    filterZone: 'ullum'
  },
  {
    icon: 'terrain',
    badge: 'NATURALEZA PURA Y CIELO ABIERTO',
    title: 'Valles Cordilleranos',
    description: 'Calingasta (Barreal, Tamberías), Iglesia (Rodeo, Bella Vista) y Jáchal. Destinos para desconexión absoluta, astroturismo, windsurf y cabalgatas cordilleranas.',
    points: [
      '10 Complejos de cabañas inspeccionados',
      'Contacto directo con baqueanos y guías',
      'Seguro de turismo activo incluido'
    ],
    buttonText: 'Explorar Valles Cordilleranos (10)',
    filterZone: 'zonda'
  }
]
</script>

<template>
  <section class="w-full bg-surface-container-low py-space-xl border-y border-surface-container">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
      <!-- HEADER PRINCIPAL DEL MÓDULO -->
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-space-lg">
        <div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-bold mb-2">
            <span class="material-symbols-outlined text-[16px]">travel_explore</span>
            CERCANÍA INTELIGENTE &amp; CONECTIVIDAD
          </div>
          <h2 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
            Elegí tu Evento o Lugar Turístico: Te Ofrecemos Quintas y Cabañas Cerca
          </h2>
          <p class="font-body-md text-body-md text-on-surface-variant max-w-3xl mt-1">
            Si vas a vivir la Fiesta del Sol, una fecha en el Villicum, un fin de semana náutico en los diques o astroturismo en la cordillera, te mostramos opciones auditadas con accesos despejados para tu grupo.
          </p>
        </div>

        <RouterLink
          to="/eventos"
          class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container-lowest hover:bg-surface-container text-primary font-label-md text-label-md font-bold shadow-xs border border-surface-container transition-all"
        >
          <span>Ver Portal Completo</span>
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </RouterLink>
      </div>

      <!-- SELECTOR EN 2 PARTES: OFERTAS DE EVENTOS vs OFERTAS TURÍSTICAS -->
      <div class="bg-surface-container-lowest p-2 rounded-2xl shadow-sm border border-surface-container mb-space-md">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
          <!-- PARTE 1 -->
          <button
            type="button"
            :class="[
              'flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-xl font-label-lg text-label-lg font-bold transition-all cursor-pointer',
              activeMainCategory === 'eventos'
                ? 'bg-primary text-on-primary shadow-sm'
                : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low'
            ]"
            @click="handleCategoryChange('eventos')"
          >
            <span class="material-symbols-outlined text-[22px]">festival</span>
            <span>1. Ofertas de Eventos (FNS &amp; Carreras)</span>
          </button>

          <!-- PARTE 2 -->
          <button
            type="button"
            :class="[
              'flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-xl font-label-lg text-label-lg font-bold transition-all cursor-pointer',
              activeMainCategory === 'turisticos'
                ? 'bg-primary text-on-primary shadow-sm'
                : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-low'
            ]"
            @click="handleCategoryChange('turisticos')"
          >
            <span class="material-symbols-outlined text-[22px]">landscape</span>
            <span>2. Lugares y Experiencias Turísticas (Diques &amp; Cordillera)</span>
          </button>
        </div>
      </div>

      <!-- SUB-TABS: ATRACTIVOS DENTRO DE LA CATEGORÍA ELEGIDA -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none mb-space-lg">
        <template v-if="activeMainCategory === 'eventos'">
          <button
            type="button"
            :class="[
              'px-4 py-2 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedItemKey === 'fns'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedItemKey = 'fns'"
          >
            <span class="material-symbols-outlined text-[18px]">celebration</span>
            <span>Fiesta Nacional del Sol 2026</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedItemKey === 'villicum'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedItemKey = 'villicum'"
          >
            <span class="material-symbols-outlined text-[18px]">sports_motorsports</span>
            <span>Autódromo Circuito Villicum</span>
          </button>
        </template>

        <template v-else>
          <button
            type="button"
            :class="[
              'px-4 py-2 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedItemKey === 'ullum'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedItemKey = 'ullum'"
          >
            <span class="material-symbols-outlined text-[18px]">kayaking</span>
            <span>Diques Ullum y Punta Negra</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedItemKey === 'leoncito'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedItemKey = 'leoncito'"
          >
            <span class="material-symbols-outlined text-[18px]">nights_stay</span>
            <span>Astroturismo en El Leoncito (Barreal)</span>
          </button>

          <button
            type="button"
            :class="[
              'px-4 py-2 rounded-xl font-label-md text-label-md font-bold whitespace-nowrap transition-all cursor-pointer border flex items-center gap-2',
              selectedItemKey === 'cuesta'
                ? 'bg-secondary text-on-secondary border-secondary shadow-xs'
                : 'bg-surface-container-lowest text-on-surface-variant hover:text-on-surface border-surface-container'
            ]"
            @click="selectedItemKey = 'cuesta'"
          >
            <span class="material-symbols-outlined text-[18px]">air</span>
            <span>Cuesta del Viento (Rodeo)</span>
          </button>
        </template>
      </div>

      <!-- BANNER INFORMATIVO DEL EVENTO / ATRACTIVO ACTIVO -->
      <div class="bg-primary-container text-on-primary p-6 lg:p-8 rounded-3xl relative overflow-hidden shadow-md mb-space-lg">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-secondary/15 blur-2xl pointer-events-none"></div>
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
          <div class="lg:col-span-8 space-y-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm font-bold">
              <span class="material-symbols-outlined text-[16px]">verified</span>
              {{ activeAttraction.badge }}
            </div>
            <h3 class="font-headline-lg text-headline-lg font-bold text-on-primary">
              {{ activeAttraction.title }}
            </h3>
            <p class="font-label-md text-label-md text-secondary-fixed font-semibold">
              {{ activeAttraction.subtitle }} • {{ activeAttraction.department }}
            </p>
            <p class="font-body-md text-body-md text-on-primary-container max-w-2xl leading-relaxed">
              {{ activeAttraction.description }}
            </p>
          </div>

          <div class="lg:col-span-4 bg-surface-container-lowest/10 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex flex-col gap-3">
            <div class="flex items-center gap-3">
              <span class="material-symbols-outlined text-secondary-fixed text-[26px]">distance</span>
              <div>
                <p class="font-label-md text-label-md text-on-primary font-bold">Radio de Proximidad Seguro</p>
                <p class="font-body-sm text-body-sm text-on-primary-container">Accesos de 5 a 18 minutos sin atascos</p>
              </div>
            </div>
            <div v-if="activeAttraction.officialOperator" class="flex items-center gap-3">
              <span class="material-symbols-outlined text-secondary-fixed text-[26px]">shield</span>
              <div>
                <p class="font-label-md text-label-md text-on-primary font-bold">Operador Acreditado</p>
                <p class="font-body-sm text-body-sm text-on-primary-container">{{ activeAttraction.officialOperator }}</p>
              </div>
            </div>
            <div class="pt-2 border-t border-white/10 flex items-center justify-between">
              <span class="font-label-sm text-label-sm text-on-primary-container">Disponibles en la zona:</span>
              <span class="font-label-md text-label-md text-secondary-fixed font-bold">{{ activeAttraction.lodgingsCount }} Propiedades</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RESULTADOS: QUINTAS Y CABAÑAS CERCANAS AL ATRACTIVO SELECCIONADO -->
      <div class="space-y-space-md">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[22px]">near_me</span>
            <h4 class="font-headline-sm text-headline-sm text-primary font-bold">
              Quintas y Cabañas Auditadas Cerca de: <span class="text-secondary">{{ activeAttraction.title }}</span>
            </h4>
          </div>
          <span class="font-label-sm text-label-sm text-on-surface-variant font-medium hidden sm:inline-block">
            CBU cotejado in situ • Cero estafas
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="item in activeAttraction.lodgings"
            :key="item.id"
            class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between border border-surface-container group"
          >
            <div>
              <!-- Imagen y badges -->
              <div class="relative h-52 w-full overflow-hidden">
                <img
                  :src="item.image"
                  :alt="item.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div class="absolute top-3 left-3 flex items-center gap-1.5 bg-surface-container-lowest/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm text-secondary font-label-sm text-label-sm font-bold">
                  <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                  {{ item.acta }}
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

                <!-- Amenities chips -->
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

            <!-- Footer con precio y botones -->
            <div class="p-space-md pt-0 border-t border-surface-container mt-2 flex items-center justify-between gap-2">
              <div>
                <span class="font-label-sm text-label-sm text-on-surface-variant block">Tarifa por día</span>
                <span class="font-headline-md text-headline-md text-primary font-black">
                  ${{ item.price.toLocaleString('es-AR') }}
                </span>
              </div>

              <div class="flex items-center gap-1.5">
                <RouterLink
                  :to="`/quintas/${item.id}`"
                  class="p-2.5 rounded-xl bg-surface-container hover:bg-surface-dim text-primary transition-colors cursor-pointer"
                  title="Ver Ficha Detallada"
                >
                  <span class="material-symbols-outlined text-[20px]">info</span>
                </RouterLink>

                <a
                  :href="`https://wa.me/${item.whatsapp}?text=Hola,%20vi%20la%20propiedad%20${encodeURIComponent(item.title)}%20cerca%20de%20${encodeURIComponent(activeAttraction.title)}%20en%20Turismo%20Seguro`"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="px-3.5 py-2.5 rounded-xl bg-secondary text-on-secondary hover:bg-on-secondary-container transition-all flex items-center gap-2 font-label-md text-label-md font-bold shadow-sm cursor-pointer"
                >
                  <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.79.78 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.767-5.766zm9.969 5.766c0 5.505-4.475 9.98-9.97 9.98-1.742 0-3.376-.447-4.808-1.23l-5.222 1.369 1.393-5.087c-.89-1.493-1.393-3.238-1.393-5.032 0-5.506 4.475-9.981 9.97-9.981 5.505 0 10.03 4.475 10.03 9.981z" />
                  </svg>
                  <span>Contactar</span>
                </a>
              </div>
            </div>
          </article>
        </div>
      </div>

      <!-- SECCIÓN ADICIONAL 1: EXPERIENCIAS ÚNICAS DE NATURALEZA & AVENTURA (FOTO 1) -->
      <div class="mt-space-xl pt-space-xl border-t border-surface-container">
        <!-- Encabezado con distinción visual -->
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
            <span class="inline-flex items-center gap-1.5 font-label-md text-label-md px-3.5 py-2 rounded-xl bg-surface-container-lowest text-primary shadow-xs border border-surface-container font-semibold">
              <span class="material-symbols-outlined text-secondary text-[20px]">assignment_turned_in</span>
              Pólizas de Seguro Auditadas
            </span>
          </div>
        </div>

        <!-- 4 Tarjetas de Experiencias de Aventura -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div
            v-for="exp in experienciasAventura"
            :key="exp.id"
            class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col lg:flex-row border border-surface-container"
          >
            <!-- Imagen lateral / superior -->
            <div class="relative lg:w-2/5 h-64 lg:h-auto min-h-[16rem]">
              <img :src="exp.image" :alt="exp.title" class="w-full h-full object-cover" />
              <div class="absolute top-3 left-3 bg-primary/85 backdrop-blur-md text-on-primary px-2.5 py-1 rounded-md font-label-sm text-label-sm flex items-center gap-1 font-semibold">
                <span class="material-symbols-outlined text-[16px] text-tertiary-fixed">location_on</span>
                {{ exp.locationTag }}
              </div>
            </div>

            <!-- Contenido descriptivo -->
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

                <!-- Checklist de servicios incluidos -->
                <div class="grid grid-cols-2 gap-2 text-on-surface font-label-sm text-label-sm mb-4">
                  <span v-for="(feat, fIdx) in exp.features" :key="fIdx" class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px] text-secondary">check</span>
                    {{ feat }}
                  </span>
                </div>
              </div>

              <!-- Footer de la experiencia -->
              <div class="pt-3 border-t border-surface-container bg-surface-container-low -mx-6 -mb-6 p-4 rounded-b-2xl flex flex-col sm:flex-row items-center justify-between gap-3">
                <RouterLink
                  to="/explorar"
                  class="text-secondary hover:text-on-secondary-container font-label-md text-label-md flex items-center gap-1.5 transition-colors font-bold cursor-pointer"
                >
                  <span class="material-symbols-outlined text-[18px]">cottage</span>
                  Ver {{ exp.lodgingCount }} quintas y cabañas en {{ exp.lodgingZone }}
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

      <!-- SECCIÓN ADICIONAL 2: CORREDORES TURÍSTICOS Y DEPARTAMENTOS ESTRATÉGICOS (FOTO 2) -->
      <div class="mt-space-xl pt-space-xl border-t border-surface-container">
        <div class="max-w-3xl mb-space-lg">
          <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest font-bold">LOGÍSTICA Y TIEMPOS DE TRASLADO</span>
          <h3 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold mt-1">
            Corredores Turísticos y Departamentos Estratégicos
          </h3>
          <p class="font-body-md text-body-md text-on-surface-variant mt-2">
            Elegí la base de tu grupo según el evento o la actividad. Cada corredor cuenta con auditorías físicas periódicas para garantizar servicios reales (agua potable, cerco en piletas, electricidad apta para equipos).
          </p>
        </div>

        <!-- Grilla de 3 Corredores -->
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
              class="w-full py-2.5 rounded-xl bg-surface-container-low hover:bg-primary hover:text-on-primary text-primary font-label-md text-label-md text-center transition-colors font-bold cursor-pointer border border-surface-container"
            >
              {{ corr.buttonText }}
            </RouterLink>
          </div>
        </div>

        <!-- Banner de Rutas y Accesibilidad FNS -->
        <div class="mt-8 bg-surface-container-lowest p-6 rounded-2xl flex flex-col lg:flex-row items-center justify-between gap-6 border border-surface-container shadow-sm">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-secondary/15 flex items-center justify-center text-secondary shrink-0 mt-0.5">
              <span class="material-symbols-outlined text-[24px]">map</span>
            </div>
            <div>
              <h4 class="font-headline-sm text-headline-sm text-primary font-bold">Mapa de Rutas y Accesibilidad en Tiempo Real</h4>
              <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                Consultá los desvíos habilitados durante la Fiesta del Sol, Autódromo El Villicum y accesos a Ruta 150 y Ruta 149.
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
            <span>Guía de Rutas y Estacionamiento FNS</span>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
