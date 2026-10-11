export interface ProximityLodging {
  id: number
  title: string
  tipo: string
  location: string
  distance: string
  price: number
  capacity: number
  image: string
  whatsapp: string
  amenities: string[]
}

export interface EventOrAttractionData {
  key: string
  title: string
  subtitle: string
  department: string
  badge: string
  description: string
  officialOperator?: string
  lodgingsCount: number
  lodgings: ProximityLodging[]
}

export interface OperadorExperiencia {
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

export interface CorredorTuristico {
  icon: string
  badge: string
  title: string
  description: string
  points: string[]
  buttonText: string
  filterZone: string
}

export const eventosCatalogo: Record<string, EventOrAttractionData> = {
  fns: {
    key: 'fns',
    title: 'Fiesta Nacional del Sol 2026',
    subtitle: 'Predio Ferial Costanera & Estadio del Bicentenario',
    department: 'Chimbas / Pocito',
    badge: 'FNS 2026 • Mayor Convocatoria',
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
        amenities: ['Pileta con cerco homologado', 'Parrillero doble', 'Freezer vertical', 'Sombra de olivos'],
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
        amenities: ['Pileta cercada', 'Galería abierta', 'Vajilla 30 personas', 'Wifi Fibra Óptica'],
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
        amenities: ['Piscina 10x4m', 'Horno de barro', 'Cochera cubierta', 'Sector fogón nocturno'],
      },
    ],
  },
  villicum: {
    key: 'villicum',
    title: 'Autódromo Circuito San Juan Villicum',
    subtitle: 'Superbike Mundial, Turismo Carretera & TC2000',
    department: 'Albardón',
    badge: 'Deporte Motor Internacional',
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
        amenities: ['Estacionamiento para 10 autos', 'Asador criollo cubierto', 'Pileta con agua de pozo', 'Wifi satelital'],
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
        amenities: ['Parrilla techada', 'Freezer cervecero', 'Piscina cercada', 'Canchita de fútbol tenis'],
      },
    ],
  },
}

export const turisticosCatalogo: Record<string, EventOrAttractionData> = {
  ullum: {
    key: 'ullum',
    title: 'Diques de Ullum y Punta Negra (Ruta Interlagos)',
    subtitle: 'Turismo Náutico, Kayak, Windsurf y Atardeceres',
    department: 'Ullum & Zonda',
    badge: 'Interlagos • Espejos de Agua',
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
        amenities: ['Bajada privada para kayaks', 'Deck sobre el lago', 'Parrillero móvil', 'Mirador al atardecer'],
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
        amenities: ['Piscina con cascada', 'Gran quincho de caña', 'Parrillero doble', 'Aire acondicionado'],
      },
    ],
  },
  leoncito: {
    key: 'leoncito',
    title: 'Astroturismo en El Leoncito & Barreal',
    subtitle: 'Parque Nacional El Leoncito • CASLEO y CESCO',
    department: 'Calingasta (Precordillera)',
    badge: 'Cielos Más Limpios del Mundo',
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
        amenities: ['Terraza de astrofotografía', 'Estufa a leña', 'Parque con sauces', 'Parrillero cubierto'],
      },
    ],
  },
  cuesta: {
    key: 'cuesta',
    title: 'Cuesta del Viento (Kitesurf & Windsurf)',
    subtitle: 'Dique Cuesta del Viento • Rodeo',
    department: 'Iglesia (Norte Cordillerano)',
    badge: 'Meca Mundial del Viento',
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
        amenities: ['Guardería de equipo náutico', 'Piscina protegida de viento', 'Parrillero techado', 'Wifi satelital'],
      },
    ],
  },
}

export const experienciasAventura: OperadorExperiencia[] = [
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
    whatsapp: '5492644333999',
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
    whatsapp: '5492644123456',
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
    whatsapp: '5492644444555',
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
    whatsapp: '5492645889900',
  },
]

export const corredoresTuristicos: CorredorTuristico[] = [
  {
    icon: 'location_city',
    badge: 'TIEMPO MEDIO AL EVENTO: 10 - 20 MIN',
    title: 'Corredor Gran San Juan',
    description: 'Capital, Santa Lucía, Chimbas, Rawson, Rivadavia y Pocito Norte. Ideal para la Fiesta Nacional del Sol, eventos en el Estadio del Bicentenario y previas urbanas de asados familiares.',
    points: [
      '28 Quintas verificadas para pasar el día',
      'Acceso inmediato a autovías iluminadas',
      'Delivery y servicios comerciales 24hs',
    ],
    buttonText: 'Explorar Gran San Juan (28)',
    filterZone: 'rawson',
  },
  {
    icon: 'water',
    badge: 'TIEMPO MEDIO AL AGUA: 5 - 15 MIN',
    title: 'Ruta Interlagos & Embalses',
    description: 'Departamentos de Ullum y Zonda. El polo veraniego de relax, clubes náuticos, paseos en kayak, senderos en la Quebrada de Zonda y quintas con vista a las sierras.',
    points: [
      '16 Casas quintas y quinchos auditados',
      'Bajada náutica a menos de 10 min',
      'Microclima fresco al pie de la montaña',
    ],
    buttonText: 'Explorar Ullum & Zonda (16)',
    filterZone: 'ullum',
  },
  {
    icon: 'terrain',
    badge: 'NATURALEZA PURA Y CIELO ABIERTO',
    title: 'Valles Cordilleranos',
    description: 'Calingasta (Barreal, Tamberías), Iglesia (Rodeo, Bella Vista) y Jáchal. Destinos para desconexión absoluta, astroturismo, windsurf y cabalgatas cordilleranas.',
    points: [
      '10 Complejos de cabañas inspeccionados',
      'Contacto directo con baqueanos y guías',
      'Seguro de turismo activo incluido',
    ],
    buttonText: 'Explorar Valles Cordilleranos (10)',
    filterZone: 'zonda',
  },
]
