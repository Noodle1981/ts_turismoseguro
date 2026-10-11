<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'

const route = useRoute()
const router = useRouter()
const { user, logout } = useAuth()

// 1. Estructura de Ofertas del Propietario (Soporte Multi-Propiedad)
export interface PropietarioOferta {
  id: number
  legajo: string
  title: string
  subtitle: string
  department: string
  location: string
  poliza: string
  actaFecha: string
  image: string
  tariffs: {
    lunesJueves: number
    viernesDomingo: number
    sabadoFeriados: number
    eventoEspecial: number
    pernocteCamas?: number
    pernocteCarpas?: number
    findeCompleto?: number
    fiestaNocturna?: number
  }
  schedule: {
    start: string
    end: string
    activeDays: number[]
  }
  isListingVisible: boolean
  metrics: {
    jornadas: number
    jornadasDiff: string
    ocupacion: number
    ingresosEstimados: number
    ingresosCobrado: number
    reputacion: number
    resenasCount: number
  }
  tenants: Array<{
    id: string
    name: string
    initials: string
    dni: string
    phone: string
    date: string
    time: string
    guestsText: string
    guestsAdults: number
    guestsChildren: number
    eventType: string
    insuranceBadge: string
    sena: string
    saldo: string
    history: string
    status: string
    ownerRating?: 'up' | 'down' | 'none'
  }>
  pastTenants: Array<{
    id: string
    name: string
    initials: string
    dni: string
    phone: string
    date: string
    time: string
    guestsText: string
    guestsAdults: number
    guestsChildren: number
    eventType: string
    insuranceBadge: string
    totalPrice: string
    history: string
    status: string
    ownerRating?: 'up' | 'down' | 'none'
  }>
}

const myOffers = ref<PropietarioOferta[]>([
  {
    id: 8,
    legajo: 'Acta #849-MED/26',
    title: 'Solo Día Quinta Los Olivos',
    subtitle: 'Quincho & Parque Arbolado',
    department: 'Rawson, San Juan',
    location: 'Calle Picasso 1400, Médano de Oro, Rawson',
    poliza: 'Póliza Triunfo Seguros #TS-SEG-4821',
    actaFecha: '14/Feb/2026',
    image: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
    isListingVisible: true,
    schedule: {
      start: '10:00',
      end: '20:00',
      activeDays: [4, 5, 6, 0], // Jueves a Domingo por defecto
    },
    tariffs: {
      lunesJueves: 35000,
      viernesDomingo: 45000,
      sabadoFeriados: 55000,
      eventoEspecial: 85000,
      pernocteCamas: 65000,
      pernocteCarpas: 40000,
      findeCompleto: 110000,
      fiestaNocturna: 120000,
    },
    metrics: {
      jornadas: 14,
      jornadasDiff: '+3 jornadas vs. mes pasado',
      ocupacion: 78,
      ingresosEstimados: 630000,
      ingresosCobrado: 490000,
      reputacion: 0,
      resenasCount: 0,
    },
    tenants: [
      {
        id: 'INQ-01',
        name: 'Lorena Agüero',
        initials: 'LA',
        dni: '32.844.119',
        phone: '+54 9 264 588-9911',
        date: 'Sábado 15 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '38 personas (28 adultos, 10 niños)',
        guestsAdults: 28,
        guestsChildren: 10,
        eventType: 'Bautismo familiar',
        insuranceBadge: 'Póliza TS Activa #9482',
        sena: '$27.500',
        saldo: '$27.500 en tranquera',
        history: '5 estrellas • 2 alquileres previos • Excelente cuidado',
        status: 'confirmed',
        ownerRating: 'up',
      },
      {
        id: 'INQ-02',
        name: 'Damián Ruarte',
        initials: 'DR',
        dni: '35.109.432',
        phone: '+54 9 264 588-9922',
        date: 'Viernes 21 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '35 personas (30 adultos, 5 niños)',
        guestsAdults: 30,
        guestsChildren: 5,
        eventType: 'Asado de amigos',
        insuranceBadge: 'Póliza TS Activa #9482',
        sena: '$22.500',
        saldo: '$22.500 en tranquera',
        history: '4.8 estrellas • 1 alquiler previo • Cuidó el parrillero',
        status: 'confirmed',
        ownerRating: 'up',
      },
      {
        id: 'INQ-03',
        name: 'Ignacio Castro',
        initials: 'IC',
        dni: '28.601.780',
        phone: '+54 9 264 588-9933',
        date: 'Sábado 22 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '45 personas (40 adultos, 5 niños)',
        guestsAdults: 40,
        guestsChildren: 5,
        eventType: 'Cumpleaños 50 años',
        insuranceBadge: 'Microseguro TS #SEG-4821',
        sena: '$55.000',
        saldo: '$0 (Cancelado 100%)',
        history: '5.0 estrellas • 4 alquileres previos sin reclamos',
        status: 'confirmed',
        ownerRating: 'up',
      },
      {
        id: 'INQ-04',
        name: 'Beatriz Montiel',
        initials: 'BM',
        dni: '30.984.111',
        phone: '+54 9 264 588-9944',
        date: 'Domingo 23 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '32 personas (16 adultos, 16 niños)',
        guestsAdults: 16,
        guestsChildren: 16,
        eventType: 'Cumple infantil temático',
        insuranceBadge: 'Póliza TS Activa #9482',
        sena: '$22.500',
        saldo: '$22.500 en tranquera',
        history: '5.0 estrellas • Nuevo en Turismo Seguro',
        status: 'confirmed',
        ownerRating: 'none',
      },
    ],
    pastTenants: [
      {
        id: 'PAS-01',
        name: 'Esteban Morales',
        initials: 'EM',
        dni: '35.889.120',
        phone: '+54 9 264 458-1290',
        date: 'Sábado 05 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '35 personas (30 adultos, 5 niños)',
        guestsAdults: 30,
        guestsChildren: 5,
        eventType: 'Cumpleaños 40 de Esteban',
        insuranceBadge: 'Microseguro TS #SEG-4821',
        totalPrice: '$55.000 (Cancelado 100%)',
        history: '5.0 estrellas • Cumplió aforo y reglas sin observaciones',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-02',
        name: 'Lorena Agüero',
        initials: 'LA',
        dni: '32.844.119',
        phone: '+54 9 264 588-9911',
        date: 'Domingo 06 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '38 personas (28 adultos, 10 niños)',
        guestsAdults: 28,
        guestsChildren: 10,
        eventType: 'Bautismo familiar',
        insuranceBadge: 'Póliza TS Activa #9482',
        totalPrice: '$55.000 (Cancelado 100%)',
        history: '5.0 estrellas • Dejó el predio impecable',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-03',
        name: 'Carlos Benítez',
        initials: 'CB',
        dni: '34.812.504',
        phone: '+54 9 264 400-0005',
        date: 'Jueves 10 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '18 personas (18 adultos)',
        guestsAdults: 18,
        guestsChildren: 0,
        eventType: 'Almuerzo de trabajo / Jornada campestre',
        insuranceBadge: 'Microseguro TS #SEG-4821',
        totalPrice: '$35.000 (Día de semana)',
        history: '5.0 estrellas • Cliente corporativo responsable',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-04',
        name: 'Damián Ruarte',
        initials: 'DR',
        dni: '35.109.432',
        phone: '+54 9 264 588-9922',
        date: 'Sábado 12 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '35 personas (30 adultos, 5 niños)',
        guestsAdults: 30,
        guestsChildren: 5,
        eventType: 'Asado de amigos',
        insuranceBadge: 'Póliza TS Activa #9482',
        totalPrice: '$55.000 (Cancelado 100%)',
        history: '4.9 estrellas • Cuidó el parrillero techado',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-05',
        name: 'Beatriz Montiel',
        initials: 'BM',
        dni: '30.984.111',
        phone: '+54 9 264 588-9944',
        date: 'Domingo 13 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '32 personas (16 adultos, 16 niños)',
        guestsAdults: 16,
        guestsChildren: 16,
        eventType: 'Cumple infantil temático',
        insuranceBadge: 'Póliza TS Activa #9482',
        totalPrice: '$55.000 (Cancelado 100%)',
        history: '5.0 estrellas • Salva-vidas y cerco respetado',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-06',
        name: 'Lucía Albarracín',
        initials: 'LA',
        dni: '38.990.123',
        phone: '+54 9 11 500-0006',
        date: 'Miércoles 16 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '14 personas (12 adultos, 2 niños)',
        guestsAdults: 12,
        guestsChildren: 2,
        eventType: 'Día de campo turistas (Bs. As.)',
        insuranceBadge: 'Microseguro TS #SEG-4821',
        totalPrice: '$35.000 (Día de semana)',
        history: '5.0 estrellas • Turista verificada RENAPER',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-07',
        name: 'Ignacio Castro',
        initials: 'IC',
        dni: '28.601.780',
        phone: '+54 9 264 588-9933',
        date: 'Sábado 19 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '45 personas (40 adultos, 5 niños)',
        guestsAdults: 40,
        guestsChildren: 5,
        eventType: 'Cumpleaños 50 años',
        insuranceBadge: 'Microseguro TS #SEG-4821',
        totalPrice: '$55.000 (Cancelado 100%)',
        history: '5.0 estrellas • Aforo total cubierto con éxito',
        status: 'completed',
        ownerRating: 'up',
      },
    ],
  },
  {
    id: 10,
    legajo: 'Acta #792-ZON/26',
    title: 'Cabaña & Posada El Refugio de los Olivos',
    subtitle: 'Arboleda de Olivos & Piscina',
    department: 'Zonda, San Juan',
    location: 'Ruta Provincial 12 km 18, Zonda',
    poliza: 'Póliza Triunfo Seguros #POL-TRIUNFO-9104',
    actaFecha: '02/Feb/2026',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm0w3Eo3bIt_7_3405BlGYRthz836ni90tEsKwGbg4jcxaXwi7z66yjMC9regCjEdZzHZ32e2nsRjCv5T8LNXjcr6fhViHukkw4RNlK1SkQ7iWFEUSaW6eR68N6uggcTmSJ1HyFp7q8nY7XpEI4bZOFIhcjvkuleFCt1Z6fEXD2OcOLe7I7hpWifF13iUYWVSSNihJPiUKDBd3DDsVkzgtW9NqCeERC3L1gRdArXuRl_vi_XCT6ofS',
    isListingVisible: true,
    schedule: {
      start: '10:00',
      end: '20:00',
      activeDays: [5, 6, 0], // Viernes a Domingo por defecto
    },
    tariffs: {
      lunesJueves: 42000,
      viernesDomingo: 52000,
      sabadoFeriados: 68000,
      eventoEspecial: 95000,
      pernocteCamas: 75000,
      pernocteCarpas: 45000,
      findeCompleto: 130000,
      fiestaNocturna: 135000,
    },
    metrics: {
      jornadas: 10,
      jornadasDiff: '+2 jornadas vs. mes pasado',
      ocupacion: 65,
      ingresosEstimados: 520000,
      ingresosCobrado: 410000,
      reputacion: 0,
      resenasCount: 0,
    },
    tenants: [
      {
        id: 'INQ-05',
        name: 'Matías Zalazar',
        initials: 'MZ',
        dni: '34.201.889',
        phone: '+54 9 264 511-2233',
        date: 'Jueves 20 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '18 personas (18 adultos)',
        guestsAdults: 18,
        guestsChildren: 0,
        eventType: 'Almuerzo Empresarial Fin de Mes',
        insuranceBadge: 'Póliza TS Activa #9104',
        sena: '$21.000',
        saldo: '$21.000 en tranquera',
        history: '5.0 estrellas • Cliente Corporativo Verificado',
        status: 'confirmed',
        ownerRating: 'up',
      },
      {
        id: 'INQ-06',
        name: 'Carolina Gómez',
        initials: 'CG',
        dni: '36.541.902',
        phone: '+54 9 264 499-8877',
        date: 'Viernes 28 Feb, 2025',
        time: '10:00 a 20:00 hs',
        guestsText: '24 personas (18 adultos, 6 niños)',
        guestsAdults: 18,
        guestsChildren: 6,
        eventType: 'Cumpleaños 15 Diurno',
        insuranceBadge: 'Póliza TS Activa #9104',
        sena: '$26.000',
        saldo: '$26.000 en tranquera',
        history: '5.0 estrellas • Sin incidentes registrados',
        status: 'confirmed',
        ownerRating: 'up',
      },
    ],
    pastTenants: [
      {
        id: 'PAS-REF-01',
        name: 'Carolina Gómez',
        initials: 'CG',
        dni: '36.541.902',
        phone: '+54 9 264 499-8877',
        date: 'Sábado 05 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '22 personas (16 adultos, 6 niños)',
        guestsAdults: 16,
        guestsChildren: 6,
        eventType: 'Cumpleaños 15 Diurno',
        insuranceBadge: 'Póliza TS Activa #9104',
        totalPrice: '$68.000 (Cancelado 100%)',
        history: '5.0 estrellas • Disfrutaron de la vista en Zonda',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-REF-02',
        name: 'Carlos Benítez',
        initials: 'CB',
        dni: '34.812.504',
        phone: '+54 9 264 400-0005',
        date: 'Domingo 06 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '20 personas (18 adultos, 2 niños)',
        guestsAdults: 18,
        guestsChildren: 2,
        eventType: 'Día familiar en la montaña',
        insuranceBadge: 'Póliza TS Activa #9104',
        totalPrice: '$52.000 (Cancelado 100%)',
        history: '5.0 estrellas • Normas de convivencia respetadas',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-REF-03',
        name: 'Lucía Albarracín',
        initials: 'LA',
        dni: '38.990.123',
        phone: '+54 9 11 500-0006',
        date: 'Jueves 17 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '12 personas (10 adultos, 2 niños)',
        guestsAdults: 10,
        guestsChildren: 2,
        eventType: 'Retiro corporativo / Almuerzo',
        insuranceBadge: 'Póliza TS Activa #9104',
        totalPrice: '$42.000 (Día de semana)',
        history: '5.0 estrellas • Cuidaron instalaciones de Zonda',
        status: 'completed',
        ownerRating: 'up',
      },
      {
        id: 'PAS-REF-04',
        name: 'Ignacio Castro',
        initials: 'IC',
        dni: '28.601.780',
        phone: '+54 9 264 588-9933',
        date: 'Sábado 19 Sep, 2026',
        time: '10:00 a 20:00 hs',
        guestsText: '24 personas (20 adultos, 4 niños)',
        guestsAdults: 20,
        guestsChildren: 4,
        eventType: 'Asado campestre fin de semana',
        insuranceBadge: 'Póliza TS Activa #9104',
        totalPrice: '$68.000 (Cancelado 100%)',
        history: '5.0 estrellas • Excelente grupo familiar',
        status: 'completed',
        ownerRating: 'up',
      },
    ],
  },
])

// Oferta seleccionada actualmente (por defecto id: 8 Solo Día Quinta Los Olivos)
const selectedOfferId = ref<number>(8)
const selectedOffer = computed(() => {
  return myOffers.value.find(o => o.id === selectedOfferId.value) || myOffers.value[0]
})

// Navegación Sidebar activa
const activeNav = ref<'general' | 'ofertas' | 'calendario' | 'inquilinos' | 'revalidacion'>('general')

// Período de Estadísticas (Meses y Años existentes en la base de datos de reservas)
const selectedYear = ref<number>(2026)
const selectedMonth = ref<number>(10) // 10 = Octubre (Mes actual)

const availableYears = [2026, 2027, 2028]
const availableMonths = [
  { value: 1, label: 'Enero' },
  { value: 2, label: 'Febrero' },
  { value: 3, label: 'Marzo' },
  { value: 4, label: 'Abril' },
  { value: 5, label: 'Mayo' },
  { value: 6, label: 'Junio' },
  { value: 7, label: 'Julio' },
  { value: 8, label: 'Agosto' },
  { value: 9, label: 'Septiembre' },
  { value: 10, label: 'Octubre' },
  { value: 11, label: 'Noviembre' },
  { value: 12, label: 'Diciembre' },
]

const prevMonth = () => {
  if (selectedMonth.value === 1) {
    if (selectedYear.value > 2026) {
      selectedMonth.value = 12
      selectedYear.value--
    }
  } else {
    selectedMonth.value--
  }
}

const nextMonth = () => {
  if (selectedMonth.value === 12) {
    if (selectedYear.value < 2028) {
      selectedMonth.value = 1
      selectedYear.value++
    }
  } else {
    selectedMonth.value++
  }
}

// Métricas reactivas al filtro de mes/año y a la oferta seleccionada
const currentMetrics = computed(() => {
  const base = selectedOffer.value.metrics
  const isOlivos = selectedOffer.value.id === 8

  // Octubre 2026 (Mes Actual)
  if (selectedMonth.value === 10 && selectedYear.value === 2026) {
    return {
      jornadas: isOlivos ? 14 : 10,
      jornadasDiff: isOlivos ? '+3 jornadas vs. mes pasado' : '+2 jornadas vs. mes pasado',
      ocupacion: isOlivos ? 78 : 65,
      ingresosEstimados: isOlivos ? 630000 : 520000,
      ingresosCobrado: isOlivos ? 490000 : 410000,
      reputacion: 0,
      resenasCount: 0,
    }
  }

  // Septiembre 2026 (Mes Pasado Concluido)
  if (selectedMonth.value === 9 && selectedYear.value === 2026) {
    return {
      jornadas: isOlivos ? 11 : 8,
      jornadasDiff: '100% concluidas en Septiembre',
      ocupacion: isOlivos ? 72 : 60,
      ingresosEstimados: isOlivos ? 550000 : 420000,
      ingresosCobrado: isOlivos ? 550000 : 420000,
      reputacion: 0,
      resenasCount: 0,
    }
  }

  // Otros meses / Proyecciones
  const factor = (selectedMonth.value % 4) + 1
  return {
    ...base,
    jornadas: 8 + factor * 2,
    jornadasDiff: `Histórico ${selectedMonth.value}/${selectedYear.value}`,
    ocupacion: 50 + factor * 8,
    ingresosEstimados: Math.round(base.ingresosEstimados * (0.7 + factor * 0.1)),
    ingresosCobrado: Math.round(base.ingresosCobrado * (0.7 + factor * 0.1)),
  }
})

// Pestaña activa del formulario de tarifas
const tariffsTab = ref<'diurno' | 'pernocte' | 'fiestas'>('diurno')

// Estado de guardado de tarifas
const tarifaSavedFeedback = ref(false)
const handleSaveTarifas = () => {
  tarifaSavedFeedback.value = true
  setTimeout(() => {
    tarifaSavedFeedback.value = false
  }, 3000)
}

// Modal Agendar Nueva Reserva y Bloqueo de Fechas (Diurno / Multidía / Fiesta Nocturna / Bloqueo Particular)
const showBookingModal = ref(false)
const customBookedDays = ref<Record<string, { title: string; subtitle: string; icon: string; modality: string }>>({})

const newBooking = ref({
  modality: 'diurno' as 'diurno' | 'multidia' | 'fiesta_nocturna' | 'bloqueo',
  blockType: 'familiar' as 'familiar' | 'mantenimiento' | 'externo',
  tenantName: '',
  tenantDni: '',
  tenantPhone: '',
  tenantCity: 'rawson',
  date: '2026-10-25',
  endDate: '2026-10-27',
  nightStart: '20:00',
  nightEnd: '05:00',
  overnightType: 'camas' as 'camas' | 'carpas' | 'ambas',
  reason: 'Cumpleaños familiar / infantil',
  adults: 30,
  children: 10,
  insurance: 'mensual',
  notes: '',
  sena: '$0',
  saldo: '$0',
})

const totalGuests = computed(() => {
  if (newBooking.value.modality === 'bloqueo') return 0
  return (Number(newBooking.value.adults) || 0) + (Number(newBooking.value.children) || 0)
})

const isOverCapacity = computed(() => {
  if (newBooking.value.modality === 'bloqueo') return false
  const cap = newBooking.value.modality === 'multidia' ? 20 : 50
  return totalGuests.value > cap
})

const handleAddBooking = () => {
  const offerId = selectedOffer.value.id

  // CASO A: BLOQUEO / INDISPONIBILIDAD (Familiar, Mantenimiento o Reserva Externa)
  if (newBooking.value.modality === 'bloqueo') {
    if (!newBooking.value.date) {
      window.alert('Por favor seleccione una fecha a bloquear.')
      return
    }

    const isFamiliar = newBooking.value.blockType === 'familiar'
    const isExterno = newBooking.value.blockType === 'externo'

    const title = isExterno
      ? 'Reserva Particular (Externa)'
      : (isFamiliar ? (newBooking.value.reason || 'Uso Familiar') : 'Mantenimiento')

    const subtitle = isExterno
      ? (newBooking.value.notes || 'Alquiler pactado fuera de plataforma')
      : (newBooking.value.notes || (isFamiliar ? 'Bloqueo Privado' : 'Trabajos en predio'))

    const icon = isExterno ? 'no_crash' : (isFamiliar ? 'cottage' : 'construction')

    // Soporte para rango de fechas
    const [startY, startM, startD] = newBooking.value.date.split('-').map(Number)
    const current = new Date(startY, startM - 1, startD)
    const end = newBooking.value.endDate
      ? new Date(
          Number(newBooking.value.endDate.split('-')[0]),
          Number(newBooking.value.endDate.split('-')[1]) - 1,
          Number(newBooking.value.endDate.split('-')[2])
        )
      : new Date(startY, startM - 1, startD)

    while (current <= end) {
      const yStr = current.getFullYear()
      const mStr = String(current.getMonth() + 1).padStart(2, '0')
      const dStr = String(current.getDate()).padStart(2, '0')
      const dateKey = `${offerId}_${yStr}-${mStr}-${dStr}`

      internalBlocks.value[dateKey] = {
        type: newBooking.value.blockType,
        title,
        subtitle,
        icon,
      }

      current.setDate(current.getDate() + 1)
    }

    // Sincronizar mes y año en vista
    selectedYear.value = startY
    selectedMonth.value = startM
    showBookingModal.value = false

    if (isExterno) {
      window.alert(
        `¡Fecha(s) bloqueada(s) con éxito!\n\n` +
        `Estado: "Reserva Particular (Fuera de Plataforma)"\n` +
        `Constancia Legal: La fecha figura NO DISPONIBLE para alquiler en Turismo Seguro San Juan. Ni la plataforma ni el seguro emitido amparan eventos o incidentes acordados por fuera del sistema.`
      )
    } else {
      window.alert(`¡Fecha(s) bloqueada(s) con éxito como ${isFamiliar ? 'Uso Familiar' : 'Mantenimiento'}!`)
    }
    return
  }

  // CASO B: RESERVA DE INQUILINO (Diurno, Multidía o Fiesta)
  if (!newBooking.value.tenantName || !newBooking.value.tenantDni) {
    window.alert('Por favor complete el nombre y DNI del inquilino titular.')
    return
  }

  const initials = newBooking.value.tenantName
    .split(' ')
    .map(p => p[0])
    .join('')
    .substring(0, 2)
    .toUpperCase()

  let timeStr = '10:00 a 20:00 hs'
  let dateDisplay = newBooking.value.date
  let iconName = 'event_available'

  if (newBooking.value.modality === 'multidia') {
    timeStr = `Estadía Completa (${newBooking.value.overnightType === 'camas' ? 'Camas' : (newBooking.value.overnightType === 'carpas' ? 'Carpas/Acampe' : 'Camas + Carpas')})`
    dateDisplay = `${newBooking.value.date} al ${newBooking.value.endDate}`
    iconName = newBooking.value.overnightType === 'carpas' ? 'camping' : 'bed'

    // Bloquear todas las fechas intermedias en el calendario
    const [y1, m1, d1] = newBooking.value.date.split('-').map(Number)
    const [y2, m2, d2] = newBooking.value.endDate.split('-').map(Number)
    const cur = new Date(y1, m1 - 1, d1)
    const end = new Date(y2, m2 - 1, d2)
    while (cur <= end) {
      const yStr = cur.getFullYear()
      const mStr = String(cur.getMonth() + 1).padStart(2, '0')
      const dStr = String(cur.getDate()).padStart(2, '0')
      const k = `${offerId}_${yStr}-${mStr}-${dStr}`
      customBookedDays.value[k] = {
        title: newBooking.value.tenantName,
        subtitle: `Estadía • ${newBooking.value.overnightType === 'carpas' ? 'Carpa' : 'Pernocte'}`,
        icon: iconName,
        modality: 'multidia',
      }
      cur.setDate(cur.getDate() + 1)
    }
  } else if (newBooking.value.modality === 'fiesta_nocturna') {
    timeStr = `Nocturno (${newBooking.value.nightStart} a ${newBooking.value.nightEnd} hs)`
    iconName = 'nightlife'
    const k = `${offerId}_${newBooking.value.date}`
    customBookedDays.value[k] = {
      title: newBooking.value.tenantName,
      subtitle: `Fiesta Nocturna (${newBooking.value.nightStart}-${newBooking.value.nightEnd})`,
      icon: 'nightlife',
      modality: 'fiesta_nocturna',
    }
  } else {
    // Diurno estándar
    const k = `${offerId}_${newBooking.value.date}`
    customBookedDays.value[k] = {
      title: newBooking.value.tenantName,
      subtitle: 'Turno Diurno',
      icon: 'verified',
      modality: 'diurno',
    }
  }

  selectedOffer.value.tenants.unshift({
    id: `INQ-${Math.floor(10 + Math.random() * 90)}`,
    name: newBooking.value.tenantName,
    initials: initials || 'IN',
    dni: newBooking.value.tenantDni,
    phone: newBooking.value.tenantPhone || '+54 9 264 500-0000',
    date: dateDisplay,
    time: timeStr,
    guestsText: `${totalGuests.value} personas (${newBooking.value.adults} adultos, ${newBooking.value.children} niños)`,
    guestsAdults: newBooking.value.adults,
    guestsChildren: newBooking.value.children,
    eventType: newBooking.value.reason,
    insuranceBadge: 'Póliza TS Activa #9482',
    sena: newBooking.value.sena,
    saldo: `${newBooking.value.saldo} en tranquera`,
    history: 'Validado in situ • Aprobado por anfitrión',
    status: 'confirmed',
  })

  // Sincronizar mes en vista
  const [startY, startM] = newBooking.value.date.split('-').map(Number)
  selectedYear.value = startY
  selectedMonth.value = startM

  showBookingModal.value = false
  window.alert(`¡Reserva confirmada con éxito!\n\nModalidad: ${newBooking.value.modality === 'multidia' ? 'Estadía Multidía con Pernocte' : (newBooking.value.modality === 'fiesta_nocturna' ? 'Fiesta / Evento Nocturno' : 'Jornada Diurna')}.\nFechas aseguradas y bloqueadas en el almanaque.`)
}

// Modal Ficha Integral de Inquilino
const showTenantModal = ref(false)
const selectedTenant = ref<any>(null)

const openTenantDetail = (tenant: any) => {
  selectedTenant.value = tenant
  showTenantModal.value = true
}

const setTenantRating = (tenant: { ownerRating?: 'up' | 'down' | 'none'; name?: string }, rating: 'up' | 'down') => {
  if (tenant.ownerRating === rating) {
    tenant.ownerRating = 'none'
  } else {
    tenant.ownerRating = rating
  }
}

// Re-Validación In Situ
const revalidacionForm = ref({
  motivo: 'fotos',
  fecha: '2025-02-15',
  notas: '',
})
const handleSolicitarRevalidacion = () => {
  window.alert('Solicitud enviada al cuerpo técnico de inspectores de Turismo Seguro San Juan. Nos comunicaremos dentro de las 24hs hábiles para confirmar el turno presencial.')
  revalidacionForm.value.notas = ''
}

// Interfaz y Estado Reactivo del Calendario Sincronizado
interface CalendarDay {
  day: number
  dateStr: string
  status: 'available' | 'booked' | 'blocked' | 'maintenance' | 'past' | 'unconfigured' | 'special_event'
  isPast?: boolean
  title?: string
  subtitle?: string
  icon?: string
  specialEventSlug?: string
  specialRate?: number
  allowsOvernight?: boolean
}

// -------------------------------------------------------------
// DÍAS DE SEPTIEMBRE 2026 (Mes Pasado Completo: Fines de semana + días de semana)
// Todos los días ya transcurridos se marcan como isPast = true (grises no disponibles)
// -------------------------------------------------------------
const septemberDaysOlivos: CalendarDay[] = [
  { day: 1, dateStr: '2026-09-01', status: 'past', isPast: true },
  { day: 2, dateStr: '2026-09-02', status: 'past', isPast: true },
  { day: 3, dateStr: '2026-09-03', status: 'past', isPast: true },
  { day: 4, dateStr: '2026-09-04', status: 'past', isPast: true },
  { day: 5, dateStr: '2026-09-05', status: 'booked', isPast: true, title: 'Esteban Morales', subtitle: '35 asis. • Cumpleaños 40', icon: 'verified' },
  { day: 6, dateStr: '2026-09-06', status: 'booked', isPast: true, title: 'Lorena Agüero', subtitle: '38 asis. • Bautismo', icon: 'verified' },
  { day: 7, dateStr: '2026-09-07', status: 'past', isPast: true },
  { day: 8, dateStr: '2026-09-08', status: 'maintenance', isPast: true, title: 'Mantenimiento', subtitle: 'Filtrado & Poda', icon: 'construction' },
  { day: 9, dateStr: '2026-09-09', status: 'past', isPast: true },
  { day: 10, dateStr: '2026-09-10', status: 'booked', isPast: true, title: 'Carlos Benítez', subtitle: '18 asis. • Día campestre', icon: 'person' },
  { day: 11, dateStr: '2026-09-11', status: 'past', isPast: true },
  { day: 12, dateStr: '2026-09-12', status: 'booked', isPast: true, title: 'Damián Ruarte', subtitle: '35 asis. • Asado amigos', icon: 'verified' },
  { day: 13, dateStr: '2026-09-13', status: 'booked', isPast: true, title: 'Beatriz Montiel', subtitle: '32 asis. • Cumple infantil', icon: 'person' },
  { day: 14, dateStr: '2026-09-14', status: 'past', isPast: true },
  { day: 15, dateStr: '2026-09-15', status: 'maintenance', isPast: true, title: 'Mantenimiento', subtitle: 'Pintura y Cloro', icon: 'construction' },
  { day: 16, dateStr: '2026-09-16', status: 'booked', isPast: true, title: 'Lucía Albarracín', subtitle: '14 asis. • Turistas Bs. As.', icon: 'person' },
  { day: 17, dateStr: '2026-09-17', status: 'past', isPast: true },
  { day: 18, dateStr: '2026-09-18', status: 'past', isPast: true },
  { day: 19, dateStr: '2026-09-19', status: 'booked', isPast: true, title: 'Ignacio Castro', subtitle: '45 asis. • Cumple 50', icon: 'verified' },
  { day: 20, dateStr: '2026-09-20', status: 'booked', isPast: true, title: 'Carolina Gómez', subtitle: '28 asis. • Primavera', icon: 'verified' },
  { day: 21, dateStr: '2026-09-21', status: 'past', isPast: true },
  { day: 22, dateStr: '2026-09-22', status: 'blocked', isPast: true, title: 'Uso Familiar', subtitle: 'Cumpleaños Nieto', icon: 'cottage' },
  { day: 23, dateStr: '2026-09-23', status: 'past', isPast: true },
  { day: 24, dateStr: '2026-09-24', status: 'past', isPast: true },
  { day: 25, dateStr: '2026-09-25', status: 'past', isPast: true },
  { day: 26, dateStr: '2026-09-26', status: 'booked', isPast: true, title: 'Lorena Agüero', subtitle: '40 asis. • Aniversario', icon: 'verified' },
  { day: 27, dateStr: '2026-09-27', status: 'booked', isPast: true, title: 'Damián Ruarte', subtitle: '30 asis. • Despedida', icon: 'person' },
  { day: 28, dateStr: '2026-09-28', status: 'past', isPast: true },
  { day: 29, dateStr: '2026-09-29', status: 'past', isPast: true },
  { day: 30, dateStr: '2026-09-30', status: 'past', isPast: true },
]

const septemberDaysRefugio: CalendarDay[] = [
  { day: 1, dateStr: '2026-09-01', status: 'past', isPast: true },
  { day: 2, dateStr: '2026-09-02', status: 'past', isPast: true },
  { day: 3, dateStr: '2026-09-03', status: 'past', isPast: true },
  { day: 4, dateStr: '2026-09-04', status: 'past', isPast: true },
  { day: 5, dateStr: '2026-09-05', status: 'booked', isPast: true, title: 'Carolina Gómez', subtitle: '22 asis. • Cumple 15', icon: 'verified' },
  { day: 6, dateStr: '2026-09-06', status: 'booked', isPast: true, title: 'Carlos Benítez', subtitle: '20 asis. • Montaña', icon: 'person' },
  { day: 7, dateStr: '2026-09-07', status: 'past', isPast: true },
  { day: 8, dateStr: '2026-09-08', status: 'past', isPast: true },
  { day: 9, dateStr: '2026-09-09', status: 'maintenance', isPast: true, title: 'Mantenimiento', subtitle: 'Caldera & Pileta', icon: 'construction' },
  { day: 10, dateStr: '2026-09-10', status: 'past', isPast: true },
  { day: 11, dateStr: '2026-09-11', status: 'past', isPast: true },
  { day: 12, dateStr: '2026-09-12', status: 'past', isPast: true },
  { day: 13, dateStr: '2026-09-13', status: 'past', isPast: true },
  { day: 14, dateStr: '2026-09-14', status: 'past', isPast: true },
  { day: 15, dateStr: '2026-09-15', status: 'past', isPast: true },
  { day: 16, dateStr: '2026-09-16', status: 'past', isPast: true },
  { day: 17, dateStr: '2026-09-17', status: 'booked', isPast: true, title: 'Lucía Albarracín', subtitle: '12 asis. • Corporativo', icon: 'person' },
  { day: 18, dateStr: '2026-09-18', status: 'past', isPast: true },
  { day: 19, dateStr: '2026-09-19', status: 'booked', isPast: true, title: 'Ignacio Castro', subtitle: '24 asis. • Asado', icon: 'verified' },
  { day: 20, dateStr: '2026-09-20', status: 'booked', isPast: true, title: 'Beatriz Montiel', subtitle: '18 asis. • Bienvenida', icon: 'person' },
  { day: 21, dateStr: '2026-09-21', status: 'past', isPast: true },
  { day: 22, dateStr: '2026-09-22', status: 'past', isPast: true },
  { day: 23, dateStr: '2026-09-23', status: 'blocked', isPast: true, title: 'Uso Familiar', subtitle: 'Finde Elena', icon: 'cottage' },
  { day: 24, dateStr: '2026-09-24', status: 'past', isPast: true },
  { day: 25, dateStr: '2026-09-25', status: 'past', isPast: true },
  { day: 26, dateStr: '2026-09-26', status: 'booked', isPast: true, title: 'Carolina Gómez', subtitle: '25 asis. • Festejo', icon: 'verified' },
  { day: 27, dateStr: '2026-09-27', status: 'past', isPast: true },
  { day: 28, dateStr: '2026-09-28', status: 'past', isPast: true },
  { day: 29, dateStr: '2026-09-29', status: 'past', isPast: true },
  { day: 30, dateStr: '2026-09-30', status: 'past', isPast: true },
]

// -------------------------------------------------------------
// DÍAS DE OCTUBRE 2026 (Mes Actual: Días 1-10 pasados en gris, 11-31 disponibles/reservados)
// -------------------------------------------------------------
const octoberDaysOlivos: CalendarDay[] = [
  { day: 1, dateStr: '2026-10-01', status: 'past', isPast: true },
  { day: 2, dateStr: '2026-10-02', status: 'past', isPast: true },
  { day: 3, dateStr: '2026-10-03', status: 'past', isPast: true },
  { day: 4, dateStr: '2026-10-04', status: 'past', isPast: true },
  { day: 5, dateStr: '2026-10-05', status: 'past', isPast: true },
  { day: 6, dateStr: '2026-10-06', status: 'maintenance', isPast: true, title: 'Mantenimiento', subtitle: 'Filtrado & Césped', icon: 'construction' },
  { day: 7, dateStr: '2026-10-07', status: 'past', isPast: true },
  { day: 8, dateStr: '2026-10-08', status: 'past', isPast: true },
  { day: 9, dateStr: '2026-10-09', status: 'blocked', isPast: true, title: 'Uso Familiar', subtitle: 'Cumpleaños', icon: 'cottage' },
  { day: 10, dateStr: '2026-10-10', status: 'past', isPast: true },
  // A partir de hoy (11 en adelante):
  { day: 11, dateStr: '2026-10-11', status: 'available' },
  { day: 12, dateStr: '2026-10-12', status: 'available' },
  { day: 13, dateStr: '2026-10-13', status: 'available' },
  { day: 14, dateStr: '2026-10-14', status: 'booked', title: 'Esteban Morales', subtitle: '28 asis. • Cumpleaños', icon: 'verified' },
  { day: 15, dateStr: '2026-10-15', status: 'booked', title: 'Lorena Agüero', subtitle: '38 asis. • Bautismo', icon: 'verified' },
  { day: 16, dateStr: '2026-10-16', status: 'available' },
  { day: 17, dateStr: '2026-10-17', status: 'available' },
  { day: 18, dateStr: '2026-10-18', status: 'blocked', title: 'Bloqueo Clima', subtitle: 'Viento Zonda', icon: 'cloud' },
  { day: 19, dateStr: '2026-10-19', status: 'available' },
  { day: 20, dateStr: '2026-10-20', status: 'available' },
  { day: 21, dateStr: '2026-10-21', status: 'booked', title: 'Damián Ruarte', subtitle: '35 asis. • Asado amigos', icon: 'person' },
  { day: 22, dateStr: '2026-10-22', status: 'booked', title: 'Ignacio Castro', subtitle: '45 asis. • Cumple 50', icon: 'verified' },
  { day: 23, dateStr: '2026-10-23', status: 'booked', title: 'Beatriz Montiel', subtitle: '32 asis. • Cumple infantil', icon: 'person' },
  { day: 24, dateStr: '2026-10-24', status: 'available' },
  { day: 25, dateStr: '2026-10-25', status: 'available' },
  { day: 26, dateStr: '2026-10-26', status: 'available' },
  { day: 27, dateStr: '2026-10-27', status: 'available' },
  { day: 28, dateStr: '2026-10-28', status: 'available' },
  { day: 29, dateStr: '2026-10-29', status: 'available' },
  { day: 30, dateStr: '2026-10-30', status: 'available' },
  { day: 31, dateStr: '2026-10-31', status: 'available' },
]

const octoberDaysRefugio: CalendarDay[] = [
  { day: 1, dateStr: '2026-10-01', status: 'past', isPast: true },
  { day: 2, dateStr: '2026-10-02', status: 'past', isPast: true },
  { day: 3, dateStr: '2026-10-03', status: 'past', isPast: true },
  { day: 4, dateStr: '2026-10-04', status: 'past', isPast: true },
  { day: 5, dateStr: '2026-10-05', status: 'past', isPast: true },
  { day: 6, dateStr: '2026-10-06', status: 'past', isPast: true },
  { day: 7, dateStr: '2026-10-07', status: 'past', isPast: true },
  { day: 8, dateStr: '2026-10-08', status: 'past', isPast: true },
  { day: 9, dateStr: '2026-10-09', status: 'past', isPast: true },
  { day: 10, dateStr: '2026-10-10', status: 'past', isPast: true },
  // Días habilitados de octubre:
  { day: 11, dateStr: '2026-10-11', status: 'available' },
  { day: 12, dateStr: '2026-10-12', status: 'available' },
  { day: 13, dateStr: '2026-10-13', status: 'available' },
  { day: 14, dateStr: '2026-10-14', status: 'available' },
  { day: 15, dateStr: '2026-10-15', status: 'available' },
  { day: 16, dateStr: '2026-10-16', status: 'available' },
  { day: 17, dateStr: '2026-10-17', status: 'available' },
  { day: 18, dateStr: '2026-10-18', status: 'booked', title: 'Carolina Gómez', subtitle: '24 asis. • Cumple 15', icon: 'verified' },
  { day: 19, dateStr: '2026-10-19', status: 'available' },
  { day: 20, dateStr: '2026-10-20', status: 'booked', title: 'Matías Zalazar', subtitle: '18 asis. • Almuerzo', icon: 'person' },
  { day: 21, dateStr: '2026-10-21', status: 'available' },
  { day: 22, dateStr: '2026-10-22', status: 'available' },
  { day: 23, dateStr: '2026-10-23', status: 'available' },
  { day: 24, dateStr: '2026-10-24', status: 'available' },
  { day: 25, dateStr: '2026-10-25', status: 'available' },
  { day: 26, dateStr: '2026-10-26', status: 'available' },
  { day: 27, dateStr: '2026-10-27', status: 'available' },
  { day: 28, dateStr: '2026-10-28', status: 'available' },
  { day: 29, dateStr: '2026-10-29', status: 'available' },
  { day: 30, dateStr: '2026-10-30', status: 'available' },
  { day: 31, dateStr: '2026-10-31', status: 'available' },
]

// Selector de pestaña en la tabla de inquilinos
const tenantTab = ref<'proximos' | 'pasados'>('proximos')

// Días de la semana para configuración de franja diurna
const weekDayDefs = [
  { id: 1, letter: 'L', fullName: 'Lunes' },
  { id: 2, letter: 'M', fullName: 'Martes' },
  { id: 3, letter: 'X', fullName: 'Miércoles' },
  { id: 4, letter: 'J', fullName: 'Jueves' },
  { id: 5, letter: 'V', fullName: 'Viernes' },
  { id: 6, letter: 'S', fullName: 'Sábado' },
  { id: 0, letter: 'D', fullName: 'Domingo' },
]

// Modal para configurar horarios y días de atención diurna
const showScheduleModal = ref(false)

const toggleActiveDay = (dayId: number) => {
  const active = selectedOffer.value.schedule.activeDays
  const index = active.indexOf(dayId)
  if (index >= 0) {
    if (active.length === 1) {
      window.alert('Debe quedar al menos un día habilitado para la oferta.')
      return
    }
    active.splice(index, 1)
  } else {
    active.push(dayId)
  }
}

const setSchedulePreset = (preset: 'all' | 'thu-sun' | 'fri-sun') => {
  if (preset === 'all') {
    selectedOffer.value.schedule.activeDays = [1, 2, 3, 4, 5, 6, 0]
  } else if (preset === 'thu-sun') {
    selectedOffer.value.schedule.activeDays = [4, 5, 6, 0]
  } else if (preset === 'fri-sun') {
    selectedOffer.value.schedule.activeDays = [5, 6, 0]
  }
}

const calculateHoursDuration = (start: string, end: string) => {
  const [h1, m1] = (start || '10:00').split(':').map(Number)
  const [h2, m2] = (end || '20:00').split(':').map(Number)
  let diff = (h2 * 60 + (m2 || 0)) - (h1 * 60 + (m1 || 0))
  if (diff < 0) diff += 24 * 60
  const hours = Math.floor(diff / 60)
  return `${hours}`
}

// Catálogo Oficial de Eventos Provinciales de San Juan (Administrado institucionalmente)
interface ProvincialEvent {
  id: number
  slug: string
  title: string
  shortTitle: string
  description: string
  category: string
  startDate: string
  endDate: string
  venue: string
  impactDepartments: string[]
  suggestedMultiplier: number
  allowsOvernight: boolean
}

const officialProvincialEvents = ref<ProvincialEvent[]>([
  {
    id: 1,
    slug: 'fns-2026',
    title: 'Fiesta Nacional del Sol (FNS 2026)',
    shortTitle: 'FNS 2026',
    description: 'Mayor celebración cultural y turística de San Juan con desfiles, recitales internacionales y gastronomía regional.',
    category: 'cultural',
    startDate: '2026-11-19',
    endDate: '2026-11-22',
    venue: 'Predio Costanera Complejo Ferial / Estadio del Bicentenario',
    impactDepartments: ['Rawson', 'Pocito', 'Capital', 'Chimbas', 'Rivadavia', 'Santa Lucía'],
    suggestedMultiplier: 1.80,
    allowsOvernight: true,
  },
  {
    id: 2,
    slug: 'superbike-villicum-2026',
    title: 'Superbike & TC en Autódromo El Villicum',
    shortTitle: 'Villicum TC',
    description: 'Competencia automovilística y motociclística de nivel nacional e internacional que atrae a miles de fanáticos y delegaciones.',
    category: 'deportivo',
    startDate: '2026-10-23',
    endDate: '2026-10-25',
    venue: 'Circuito San Juan Villicum, Albardón',
    impactDepartments: ['Albardón', 'Angaco', 'Chimbas', 'Capital', 'San Martín'],
    suggestedMultiplier: 1.65,
    allowsOvernight: true,
  },
  {
    id: 3,
    slug: 'expo-minera-2026',
    title: 'San Juan Minero: Exposición Internacional',
    shortTitle: 'Expo Minera',
    description: 'Encuentro minero, industrial y corporativo líder de la región cuyana con ejecutivos y técnicos de todo el país.',
    category: 'minero_corporativo',
    startDate: '2026-11-05',
    endDate: '2026-11-07',
    venue: 'Predio Cepas Sanjuaninas, Rivadavia',
    impactDepartments: ['Rivadavia', 'Capital', 'Rawson', 'Pocito', 'Iglesia', 'Calingasta'],
    suggestedMultiplier: 1.50,
    allowsOvernight: false,
  },
  {
    id: 4,
    slug: 'navidad-2026',
    title: 'Nochebuena & Navidad 2026',
    shortTitle: 'Navidad 2026',
    description: 'Celebración festiva de alta demanda para reuniones familiares, asados campestres y estadías de fin de año.',
    category: 'fiestas_anuales',
    startDate: '2026-12-24',
    endDate: '2026-12-25',
    venue: 'En toda la provincia de San Juan',
    impactDepartments: ['TODOS'],
    suggestedMultiplier: 1.70,
    allowsOvernight: true,
  },
  {
    id: 5,
    slug: 'ano-nuevo-2027',
    title: 'Fin de Año & Año Nuevo 2027',
    shortTitle: 'Año Nuevo 2027',
    description: 'Pico máximo de demanda de quintas y cabañas con pileta para festejo de Año Nuevo y estadías con pernocte.',
    category: 'fiestas_anuales',
    startDate: '2026-12-31',
    endDate: '2027-01-01',
    venue: 'En toda la provincia de San Juan',
    impactDepartments: ['TODOS'],
    suggestedMultiplier: 2.00,
    allowsOvernight: true,
  },
  {
    id: 6,
    slug: 'safari-sierras-2027',
    title: 'Safari Tras las Sierras 2027',
    shortTitle: 'Safari Valle Fértil',
    description: 'Clásico festival deportivo y automotor off-road en los ríos secos y quebradas de Valle Fértil.',
    category: 'deportivo',
    startDate: '2027-02-05',
    endDate: '2027-02-08',
    venue: 'San Agustín de Valle Fértil',
    impactDepartments: ['Valle Fértil'],
    suggestedMultiplier: 1.90,
    allowsOvernight: true,
  },
])

// Eventos relevantes para el departamento de la oferta seleccionada
const relevantEventsForOffer = computed(() => {
  const dept = selectedOffer.value.department || ''
  return officialProvincialEvents.value.filter(evt => {
    if (evt.impactDepartments.includes('TODOS')) return true
    return evt.impactDepartments.some(d => dept.toLowerCase().includes(d.toLowerCase()))
  })
})

// Eventos que coinciden con el mes y año actualmente visualizados
const currentMonthEvents = computed(() => {
  const targetYear = selectedYear.value
  const targetMonth = selectedMonth.value
  return relevantEventsForOffer.value.filter(evt => {
    const [y1, m1] = evt.startDate.split('-').map(Number)
    const [y2, m2] = evt.endDate.split('-').map(Number)
    return (y1 === targetYear && m1 === targetMonth) || (y2 === targetYear && m2 === targetMonth)
  })
})

// Días asignados a eventos especiales (clave: `${offerId}_${dateStr}`)
const specialEventBookings = ref<Record<string, { eventSlug: string; title: string; specialRate: number; allowsOvernight: boolean }>>({
  // FNS 2026 pre-asignado para Quinta Los Olivos (19 al 22 de Noviembre 2026)
  '8_2026-11-19': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-20': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-21': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-22': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  // Navidad y Año Nuevo pre-asignados
  '8_2026-12-24': { eventSlug: 'navidad-2026', title: 'Navidad 2026', specialRate: 90000, allowsOvernight: true },
  '8_2026-12-25': { eventSlug: 'navidad-2026', title: 'Navidad 2026', specialRate: 90000, allowsOvernight: true },
  '8_2026-12-31': { eventSlug: 'ano-nuevo-2027', title: 'Año Nuevo 2027', specialRate: 110000, allowsOvernight: true },
  '8_2027-01-01': { eventSlug: 'ano-nuevo-2027', title: 'Año Nuevo 2027', specialRate: 110000, allowsOvernight: true },
})

// Modal para vincular fechas a eventos especiales
const showSpecialEventModal = ref(false)
const specialEventForm = ref({
  selectedEventId: 1,
  startDate: '2026-11-19',
  endDate: '2026-11-22',
  rate: 85000,
  allowsOvernight: true,
})

const selectedSpecialEventDef = computed(() => {
  return officialProvincialEvents.value.find(e => e.id === specialEventForm.value.selectedEventId)
})

watch(() => specialEventForm.value.selectedEventId, (newId) => {
  const ev = officialProvincialEvents.value.find(e => e.id === newId)
  if (ev) {
    specialEventForm.value.startDate = ev.startDate
    specialEventForm.value.endDate = ev.endDate
    const baseRate = selectedOffer.value.tariffs.eventoEspecial || 85000
    specialEventForm.value.rate = Math.round(baseRate * (ev.suggestedMultiplier / 1.5))
    specialEventForm.value.allowsOvernight = ev.allowsOvernight
  }
})

const openSpecialEventModal = () => {
  const evts = relevantEventsForOffer.value
  if (evts.length > 0) {
    const ev = evts[0]
    specialEventForm.value.selectedEventId = ev.id
    specialEventForm.value.startDate = ev.startDate
    specialEventForm.value.endDate = ev.endDate
    const baseRate = selectedOffer.value.tariffs.eventoEspecial || 85000
    specialEventForm.value.rate = Math.round(baseRate * (ev.suggestedMultiplier / 1.5))
    specialEventForm.value.allowsOvernight = ev.allowsOvernight
  }
  showSpecialEventModal.value = true
}

const applySpecialEventToCalendar = () => {
  const evt = selectedSpecialEventDef.value
  if (!evt) return

  const [startY, startM, startD] = specialEventForm.value.startDate.split('-').map(Number)
  const [endY, endM, endD] = specialEventForm.value.endDate.split('-').map(Number)
  const offerId = selectedOffer.value.id

  const current = new Date(startY, startM - 1, startD)
  const end = new Date(endY, endM - 1, endD)

  while (current <= end) {
    const yStr = current.getFullYear()
    const mStr = String(current.getMonth() + 1).padStart(2, '0')
    const dStr = String(current.getDate()).padStart(2, '0')
    const dateKey = `${offerId}_${yStr}-${mStr}-${dStr}`

    specialEventBookings.value[dateKey] = {
      eventSlug: evt.slug,
      title: evt.shortTitle,
      specialRate: specialEventForm.value.rate,
      allowsOvernight: specialEventForm.value.allowsOvernight,
    }

    current.setDate(current.getDate() + 1)
  }

  selectedOffer.value.tariffs.eventoEspecial = specialEventForm.value.rate
  selectedYear.value = startY
  selectedMonth.value = startM
  showSpecialEventModal.value = false
  window.alert(`¡Fechas vinculadas exitosamente a ${evt.title}!\n\nSe asignó tarifa especial de $${specialEventForm.value.rate.toLocaleString()} ARS ${specialEventForm.value.allowsOvernight ? '(con pernocte habilitado)' : '(jornada diurna)'}.`)
}

// Cálculo matemático de columna inicial para cualquier mes/año
const firstDayOffset = computed(() => {
  const jsDay = new Date(selectedYear.value, selectedMonth.value - 1, 1).getDay()
  return jsDay === 0 ? 7 : jsDay
})

// Días reactivos computados según la oferta, mes/año seleccionado, franja y eventos provinciales
const calendarDays = computed<CalendarDay[]>(() => {
  const isOlivos = selectedOffer.value.id === 8
  const offerId = selectedOffer.value.id
  const activeDays = selectedOffer.value.schedule?.activeDays || [4, 5, 6, 0]
  const yr = selectedYear.value
  const mo = selectedMonth.value

  let baseDays: CalendarDay[] = []
  if (yr === 2026 && mo === 9) {
    baseDays = isOlivos ? septemberDaysOlivos : septemberDaysRefugio
  } else if (yr === 2026 && mo === 10) {
    baseDays = isOlivos ? octoberDaysOlivos : octoberDaysRefugio
  } else {
    // Generación dinámica en tiempo real para cualquier mes y año futuro
    const totalDays = new Date(yr, mo, 0).getDate()
    baseDays = Array.from({ length: totalDays }, (_, idx) => {
      const dNum = idx + 1
      const dStr = `${yr}-${String(mo).padStart(2, '0')}-${String(dNum).padStart(2, '0')}`
      const isPastDate = dStr < '2026-10-10'
      return {
        day: dNum,
        dateStr: dStr,
        status: isPastDate ? ('past' as const) : ('available' as const),
        isPast: isPastDate,
      }
    })
  }

  return baseDays.map((d) => {
    // Si ya es un día pasado, queda como concluido/histórico
    if (d.isPast || d.status === 'past') {
      return d
    }

    // Si ya está reservado por un inquilino con póliza
    if (d.status === 'booked') {
      return d
    }

    // Si está agendado por nueva reserva (Diurno, Multidía o Fiesta)
    const key = `${offerId}_${d.dateStr}`
    const customB = customBookedDays.value[key]
    if (customB) {
      return {
        ...d,
        status: 'booked' as const,
        title: customB.title,
        subtitle: customB.subtitle,
        icon: customB.icon,
      }
    }

    // Si el propietario lo bloqueó manualmente para Uso Familiar o Mantenimiento
    if (d.status === 'maintenance' || (d.status === 'blocked' && d.title !== 'Bloqueado')) {
      return d
    }

    // Verificar si está registrado en Bloqueos Internos configurados por el anfitrión
    const internalBlk = internalBlocks.value[key]
    if (internalBlk) {
      return {
        ...d,
        status: internalBlk.type === 'mantenimiento' ? ('maintenance' as const) : ('blocked' as const),
        title: internalBlk.title,
        subtitle: internalBlk.subtitle,
        icon: internalBlk.icon,
      }
    }

    // Verificar si está vinculado a un Evento Especial provincial (Morado)
    const special = specialEventBookings.value[key]
    if (special) {
      return {
        ...d,
        status: 'special_event' as const,
        title: special.title,
        subtitle: `$${special.specialRate.toLocaleString()} • ${special.allowsOvernight ? 'Con Pernocte' : 'Diurno'}`,
        icon: special.allowsOvernight ? 'bed' : 'festival',
        specialEventSlug: special.eventSlug,
        specialRate: special.specialRate,
        allowsOvernight: special.allowsOvernight,
      }
    }

    // Calcular día de la semana local según fecha YYYY-MM-DD
    const [year, month, dayNum] = d.dateStr.split('-').map(Number)
    const dayOfWeek = new Date(year, month - 1, dayNum).getDay()

    // Si el día no está configurado en los días activos de la oferta
    if (!activeDays.includes(dayOfWeek)) {
      return {
        ...d,
        status: 'unconfigured' as const,
        title: 'Bloqueado',
        subtitle: undefined,
        icon: 'block',
      }
    }

    // Si el día está habilitado y venía como unconfigured
    if (d.status === 'unconfigured') {
      return {
        ...d,
        status: 'available' as const,
        title: undefined,
        subtitle: undefined,
        icon: undefined,
      }
    }

    return d
  })
})

const toggleCellState = async (dayNumber: number) => {
  const item = calendarDays.value.find(d => d.day === dayNumber)
  if (!item) return

  if (item.isPast || item.status === 'past') {
    window.alert(`Día ${dayNumber}: Es una fecha pasada ya concluida y no puede ser modificada.`)
    return
  }

  if (item.status === 'booked') {
    window.alert(`Día ${dayNumber}: Turno Diurno reservado para ${item.title || 'Inquilino'} (${item.subtitle || 'Verificado'}). Las fechas con seña no pueden liberarse directamente sin cancelar la reserva.`)
    return
  }

  if (item.status === 'unconfigured') {
    const dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
    const [year, month, dayNum] = item.dateStr.split('-').map(Number)
    const dayOfWeek = new Date(year, month - 1, dayNum).getDay()
    const nameOfDay = dayNames[dayOfWeek]

    const confirmEnable = window.confirm(
      `Día ${dayNumber} (${nameOfDay}): Este día figura 'Bloqueado' porque los ${nameOfDay}s no están habilitados en la Franja Diurna de tu oferta.\n\n¿Deseas habilitar los ${nameOfDay}s en tu oferta para que esté disponible?`
    )
    if (confirmEnable) {
      if (!selectedOffer.value.schedule.activeDays.includes(dayOfWeek)) {
        selectedOffer.value.schedule.activeDays.push(dayOfWeek)
      }
    }
    return
  }

  // Si el día está disponible, abrir directamente el Modal de Reserva con la fecha preseleccionada
  if (item.status === 'available') {
    newBooking.value.date = item.dateStr
    // Si es multidía, fijar fecha fin 2 días después
    const [y, m, d] = item.dateStr.split('-').map(Number)
    const endD = new Date(y, m - 1, d + 2)
    const endYStr = endD.getFullYear()
    const endMStr = String(endD.getMonth() + 1).padStart(2, '0')
    const endDStr = String(endD.getDate()).padStart(2, '0')
    newBooking.value.endDate = `${endYStr}-${endMStr}-${endDStr}`
    
    showBookingModal.value = true
    return
  }

  // Alternar entre available -> blocked (familiar) -> maintenance -> available en el array base
  const isOlivos = selectedOffer.value.id === 8
  const baseDays = selectedMonth.value === 9
    ? (isOlivos ? septemberDaysOlivos : septemberDaysRefugio)
    : (isOlivos ? octoberDaysOlivos : octoberDaysRefugio)
  const rawItem = baseDays.find(d => d.day === dayNumber)
  if (!rawItem) return

  let nextStatus: 'available' | 'blocked' | 'maintenance' = 'available'
  if (rawItem.status === 'available') {
    nextStatus = 'blocked'
    rawItem.status = 'blocked'
    rawItem.title = 'Uso Familiar'
    rawItem.subtitle = 'Bloqueado por Anfitrión'
    rawItem.icon = 'cottage'
  } else if (rawItem.status === 'blocked') {
    nextStatus = 'maintenance'
    rawItem.status = 'maintenance'
    rawItem.title = 'Mantenimiento'
    rawItem.subtitle = 'Filtrado & Parquizado'
    rawItem.icon = 'construction'
  } else {
    nextStatus = 'available'
    rawItem.status = 'available'
    rawItem.title = undefined
    rawItem.subtitle = undefined
    rawItem.icon = undefined
  }

  // Sincronizar asíncronamente con la API de Disponibilidad si hay token Sanctum
  try {
    const token = localStorage.getItem('auth_token')
    if (token) {
      await fetch(`/api/v1/client/properties/${selectedOffer.value.id}/availability/toggle-date`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
          date: item.dateStr,
          status: nextStatus,
          notes: rawItem.title,
        }),
      })
    }
  } catch (e) {
    console.warn('Sync offline / demo mode:', e)
  }
}

// Registro reactivo de Bloqueos Internos del Propietario (Uso Familiar / Mantenimiento / Reserva Particular Fuera de Plataforma)
// Clave: `${offerId}_${dateStr}`
const internalBlocks = ref<Record<string, { type: 'familiar' | 'mantenimiento' | 'externo'; title: string; subtitle: string; icon: string }>>({})

const openInternalBlockModal = () => {
  const mStr = String(selectedMonth.value).padStart(2, '0')
  const defaultDay = '15'
  newBooking.value.modality = 'bloqueo'
  newBooking.value.blockType = 'familiar'
  newBooking.value.reason = 'Uso Familiar'
  newBooking.value.notes = 'Reunión familiar / Asado privado'
  newBooking.value.date = `${selectedYear.value}-${mStr}-${defaultDay}`
  newBooking.value.endDate = ''
  showBookingModal.value = true
}


// Modal Agregar Oferta
const showAddOfferModal = ref(false)
const newOffer = ref({
  title: '',
  department: 'Pocito, San Juan',
  location: '',
  capacity: 40,
  rateWeekday: 38000,
  rateWeekend: 52000,
  poolFenced: true,
  hasGrill: true,
  hasFreezer: true,
})

const handleAddOffer = () => {
  if (!newOffer.value.title || !newOffer.value.location) {
    window.alert('Por favor complete el nombre del predio y su ubicación.')
    return
  }

  const newId = myOffers.value.length + 1
  const created: PropietarioOferta = {
    id: newId,
    legajo: `#${Math.floor(500 + Math.random() * 400)}-POC/25`,
    title: newOffer.value.title,
    subtitle: 'Parque & Piscina Exclusiva',
    department: newOffer.value.department,
    location: newOffer.value.location,
    poliza: 'Pre-Aprobación In Situ Pendiente',
    actaFecha: 'En trámite',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
    isListingVisible: false,
    schedule: {
      start: '10:00',
      end: '20:00',
      activeDays: [4, 5, 6, 0],
    },
    tariffs: {
      lunesJueves: newOffer.value.rateWeekday,
      viernesDomingo: Math.round(newOffer.value.rateWeekday * 1.25),
      sabadoFeriados: newOffer.value.rateWeekend,
      eventoEspecial: Math.round(newOffer.value.rateWeekend * 1.5),
    },
    metrics: {
      jornadas: 0,
      jornadasDiff: 'Nueva publicación',
      ocupacion: 0,
      ingresosEstimados: 0,
      ingresosCobrado: 0,
      reputacion: 5.0,
      resenasCount: 0,
    },
    tenants: [],
    pastTenants: [],
  }

  myOffers.value.push(created)
  selectedOfferId.value = newId
  showAddOfferModal.value = false
  window.alert(`¡Oferta "${created.title}" registrada con éxito! Ya puedes gestionar sus tarifas individuales. Se ha notificado al validador para coordinar la inspección in situ.`)
}

const handleLogout = async () => {
  await logout()
  router.push('/')
}

onMounted(() => {
  if (route.query.action === 'nueva-oferta') {
    showAddOfferModal.value = true
  }
})

watch(
  () => route.query.action,
  (action) => {
    if (action === 'nueva-oferta') {
      showAddOfferModal.value = true
    }
  }
)
</script>

<template>
  <div class="bg-background font-body-md text-on-surface min-h-screen">
    <!-- ========================================== -->
    <!-- 1. ASIDE: LEFT SIDEBAR EXACTO AL STITCH    -->
    <!-- ========================================== -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-surface-container-lowest shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-50 flex flex-col justify-between py-space-lg">
      <div class="flex flex-col gap-space-lg">
        <!-- Brand Header -->
        <RouterLink to="/" class="px-space-lg flex items-center gap-space-sm">
          <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-on-primary">verified_user</span>
          </div>
          <div class="flex flex-col">
            <span class="font-headline-sm text-headline-sm text-primary tracking-tight">Turismo Seguro</span>
            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">Vista Propietarios</span>
          </div>
        </RouterLink>



        <!-- Píldoras si tiene múltiples ofertas -->
        <div v-if="myOffers.length > 1" class="mx-space-lg flex flex-col gap-1.5">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Ofertas Registradas:</span>
            <button
              type="button"
              class="text-[11px] font-bold text-secondary hover:underline cursor-pointer flex items-center gap-0.5"
              @click="showAddOfferModal = true"
            >
              <span class="material-symbols-outlined text-[13px]">add</span> Agregar
            </button>
          </div>
          <div class="flex flex-col gap-1.5">
            <button
              v-for="(offer, idx) in myOffers"
              :key="offer.id"
              type="button"
              class="w-full text-left px-3 py-2 rounded-lg text-xs font-bold transition-all cursor-pointer border flex items-center justify-between gap-2"
              :class="selectedOfferId === offer.id
                ? 'bg-primary text-on-primary border-primary shadow-xs'
                : 'bg-surface-container-low text-on-surface hover:bg-surface-container hover:text-primary border-surface-container'"
              @click="selectedOfferId = offer.id"
            >
              <div class="flex items-center gap-1.5 truncate">
                <span
                  class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-mono shrink-0"
                  :class="selectedOfferId === offer.id ? 'bg-on-primary text-primary' : 'bg-surface-container-highest text-primary font-bold'"
                >
                  {{ idx + 1 }}
                </span>
                <span class="truncate">{{ offer.title }}</span>
              </div>
              <span
                v-if="selectedOfferId === offer.id"
                class="material-symbols-outlined text-[15px] shrink-0 text-secondary-fixed"
              >
                check_circle
              </span>
            </button>
          </div>
        </div>

        <!-- Navigation Items -->
        <nav class="px-space-md flex flex-col gap-space-xs">
          <a
            href="#mis-ofertas-y-tarifas"
            class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
            :class="activeNav === 'ofertas'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'"
            @click="activeNav = 'ofertas'"
          >
            <span class="material-symbols-outlined">sell</span>
            <span>Mis Ofertas y Tarifas</span>
          </a>

          <a
            href="#almanaque"
            class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
            :class="activeNav === 'calendario'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'"
            @click="activeNav = 'calendario'"
          >
            <span class="material-symbols-outlined">calendar_month</span>
            <span>Calendario y Reservas</span>
          </a>

          <a
            href="#registro-inquilinos"
            class="flex items-center gap-space-sm px-space-md py-space-sm transition-all rounded-xl font-label-lg text-label-lg"
            :class="activeNav === 'inquilinos'
              ? 'bg-primary-container text-on-primary shadow-[0_1px_3px_0_rgba(15,41,66,0.04)] font-bold'
              : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'"
            @click="activeNav = 'inquilinos'"
          >
            <span class="material-symbols-outlined">assignment_ind</span>
            <span>Registro de Inquilinos</span>
          </a>

          <!-- Botón de Agregar Oferta directo en barra -->
          <button
            type="button"
            class="mt-2 flex items-center gap-space-sm px-space-md py-space-sm rounded-xl text-secondary hover:bg-secondary/10 transition-all font-label-lg text-label-lg font-bold cursor-pointer text-left"
            @click="showAddOfferModal = true"
          >
            <span class="material-symbols-outlined text-secondary">add_circle</span>
            <span>Agregar Oferta</span>
          </button>
        </nav>
      </div>

      <!-- Footer del Sidebar -->
      <div class="px-space-lg flex flex-col gap-space-sm">
        <div class="p-space-sm bg-secondary-container/40 rounded-xl flex items-center justify-between border border-secondary/20">
          <div class="flex items-center gap-space-xs">
            <span class="material-symbols-outlined text-secondary text-[20px]">gshield</span>
            <div class="flex flex-col">
              <span class="font-label-sm text-label-sm text-on-surface font-bold">Trazabilidad Seguro</span>
              <span class="font-label-sm text-label-sm text-secondary">Vence: Nov 2025</span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between pt-space-sm">
          <div class="flex items-center gap-space-sm min-w-0">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center shrink-0">
              <span class="text-xs font-bold text-on-primary">
                {{ user?.name ? user.name.split(' ').map((n: string) => n[0]).slice(0, 2).join('') : 'EM' }}
              </span>
            </div>
            <div class="flex flex-col min-w-0">
              <span class="font-label-sm text-label-sm text-on-surface font-bold truncate">
                {{ user?.name ? user.name.split(' (')[0] : 'Elena Morales' }}
              </span>
              <span class="font-label-sm text-label-sm text-on-surface-variant">Anfitriona</span>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-all cursor-pointer"
            title="Cerrar sesión"
            @click="handleLogout"
          >
            <span class="material-symbols-outlined text-[20px]">logout</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- ========================================== -->
    <!-- 2. MAIN CONTAINER CON HEADER OFFSET PL-72  -->
    <!-- ========================================== -->
    <div class="pl-72">
      <!-- FIXED TOP SUB-HEADER -->
      <header class="fixed top-0 left-72 right-0 h-16 bg-surface/85 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)] z-40 flex items-center justify-between px-space-lg border-b border-surface-container">
        <div class="flex items-center gap-space-md">
          <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">verified</span>
            <span>Verificación Activa: In Situ Validado</span>
          </div>
          <div class="hidden xl:flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
            <span class="material-symbols-outlined text-[16px]">pin_drop</span>
            <span>{{ selectedOffer.department }}</span>
          </div>
        </div>

        <div class="flex items-center gap-space-md">
          <!-- Filtro de Mes y Año Rápido -->
          <div class="hidden md:flex items-center gap-1.5 bg-surface-container-low px-2.5 py-1 rounded-lg border border-surface-container">
            <span class="material-symbols-outlined text-primary text-[16px]">event</span>
            <select v-model.number="selectedMonth" class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer">
              <option v-for="m in availableMonths" :key="m.value" :value="m.value">{{ m.label }}</option>
            </select>
            <select v-model.number="selectedYear" class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer">
              <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>

          <div class="flex items-center gap-space-xs bg-surface-container-low px-space-sm py-space-xs rounded-lg">
            <span class="material-symbols-outlined text-tertiary text-[18px]">sunny</span>
            <span class="font-label-sm text-label-sm text-on-surface font-semibold">Turnos Diurnos Activos</span>
          </div>

          <button
            type="button"
            class="w-9 h-9 rounded-lg flex items-center justify-center hover:bg-surface-container-high text-on-surface-variant hover:text-on-surface transition-all cursor-pointer relative"
            title="Notificaciones"
          >
            <span class="material-symbols-outlined text-[20px]">notifications</span>
            <span class="w-2 h-2 rounded-full bg-secondary absolute top-2 right-2"></span>
          </button>

          <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-on-primary">
            <span class="material-symbols-outlined text-[18px]">person</span>
          </div>
        </div>
      </header>

      <!-- MAIN PAGE CONTENT (pt-16) -->
      <main class="relative pt-16 bg-background min-h-screen">
        <div class="flex flex-col w-full">
          <div class="p-space-lg lg:p-margin flex flex-col gap-space-lg max-w-[1440px] mx-auto w-full">



            <!-- 1. HEADER: ESTADO DE LA PROPIEDAD Y MÉTRICAS CLAVE -->
            <section id="resumen-general" class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-lg border border-surface-container scroll-mt-20">
              <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-space-md">
                <div class="flex flex-col gap-space-xs">
                  <div class="flex items-center gap-space-sm flex-wrap">
                    <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
                      {{ selectedOffer.title }}
                    </h1>
                    <span class="px-space-xs py-0.5 rounded-md bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-mono font-bold">
                      {{ selectedOffer.legajo }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-secondary/10 text-secondary font-label-sm text-label-sm font-bold">
                      <span class="material-symbols-outlined text-[15px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                      Verificado In Situ Vigente
                    </span>
                    <span class="inline-flex items-center gap-1 px-space-sm py-0.5 rounded-full bg-surface-container-high text-on-surface font-label-sm text-label-sm font-semibold">
                      <span class="material-symbols-outlined text-[15px] text-secondary">health_and_safety</span>
                      {{ selectedOffer.poliza }}
                    </span>
                  </div>

                  <div class="flex items-center gap-space-md text-on-surface-variant font-body-sm text-body-sm flex-wrap">
                    <span class="inline-flex items-center gap-1">
                      <span class="material-symbols-outlined text-[18px] text-tertiary">location_on</span>
                      {{ selectedOffer.location }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                      <span class="material-symbols-outlined text-[18px] text-secondary">verified_user</span>
                      Última inspección presencial: {{ selectedOffer.actaFecha }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-secondary font-label-sm text-label-sm font-bold">
                      <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                      En Línea / Aceptando Consultas
                    </span>
                  </div>
                </div>

                <div class="flex items-center gap-space-sm flex-wrap">
                  <!-- Botón Especial Eventos Provinciales (solo visible si el mes seleccionado tiene eventos especiales) -->
                  <button
                    v-if="currentMonthEvents.length > 0"
                    type="button"
                    class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg bg-purple-700 hover:bg-purple-800 text-white font-label-lg text-label-lg transition-all shadow-sm font-bold cursor-pointer animate-in fade-in duration-150"
                    title="Configurar tarifas especiales y pernocte para la Fiesta Nacional del Sol, Villicum o eventos masivos"
                    @click="openSpecialEventModal"
                  >
                    <span class="material-symbols-outlined text-[20px]">festival</span>
                    Reservar Eventos Especiales
                  </button>


                  <a
                    href="#almanaque"
                    class="inline-flex items-center gap-space-xs px-space-md py-space-sm rounded-lg bg-surface-container-low text-primary font-label-lg text-label-lg hover:bg-surface-container transition-colors font-bold border border-surface-container"
                  >
                    <span class="material-symbols-outlined text-[20px]">date_range</span>
                    Ver Calendario
                  </a>
                </div>
              </div>

              <!-- KEY METRICS GRID (3 CARDS) -->
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-space-md">
                <!-- Metric 1: Alquileres -->
                <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container">
                  <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">Jornadas Este Mes</span>
                    <span class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary">
                      <span class="material-symbols-outlined text-[20px]">event_available</span>
                    </span>
                  </div>
                  <div class="mt-space-sm">
                    <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ currentMetrics.jornadas }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant ml-1">jornadas confirmadas</span>
                  </div>
                  <div class="mt-space-xs flex items-center gap-1 text-secondary font-label-sm text-label-sm font-bold">
                    <span class="material-symbols-outlined text-[16px]">trending_up</span>
                    <span>{{ currentMetrics.jornadasDiff }}</span>
                  </div>
                </div>

                <!-- Metric 2: Ocupación -->
                <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container">
                  <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">Tasa de Ocupación</span>
                    <span class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary">
                      <span class="material-symbols-outlined text-[20px]">pie_chart</span>
                    </span>
                  </div>
                  <div class="mt-space-sm flex items-baseline gap-2">
                    <span class="font-headline-lg text-headline-lg text-primary font-bold">{{ currentMetrics.ocupacion }}%</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">de fines de semana</span>
                  </div>
                  <div class="mt-space-xs w-full bg-surface-container-highest h-2 rounded-full overflow-hidden">
                    <div class="bg-secondary h-full rounded-full transition-all duration-500" :style="{ width: `${currentMetrics.ocupacion}%` }"></div>
                  </div>
                </div>

                <!-- Metric 4: Calificación -->
                <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between border border-surface-container">
                  <div class="flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant font-bold">Reputación Verificada</span>
                    <span class="w-8 h-8 rounded-lg bg-tertiary-fixed flex items-center justify-center text-tertiary-container">
                      <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                    </span>
                  </div>
                  <div class="mt-space-sm flex items-baseline gap-1">
                    <span class="font-headline-lg text-headline-lg text-primary font-bold">
                      {{ currentMetrics.resenasCount > 0 ? currentMetrics.reputacion : '0.0' }}
                    </span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">/ 5.0</span>
                  </div>
                  <div class="mt-space-xs text-on-surface-variant font-label-sm text-label-sm">
                    <span v-if="currentMetrics.resenasCount > 0">
                      Basado en <span class="font-bold text-primary">{{ currentMetrics.resenasCount }} reseñas</span> con DNI validado
                    </span>
                    <span v-else class="text-on-surface-variant italic">
                      0 reseñas registradas en la base de datos
                    </span>
                  </div>
                </div>
              </div>
            </section>

            <!-- 2. GESTIÓN DE OFERTA Y EDICIÓN RÁPIDA DE PRECIOS + SOLICITUD RE-VALIDACIÓN -->
            <div id="mis-ofertas-y-tarifas" class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg scroll-mt-20">
              <!-- PROPERTY OVERVIEW & QUICK PRICING (8 COLS) -->
              <section class="lg:col-span-8 bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col justify-between gap-space-lg border border-surface-container">
                <div>
                  <div class="flex items-center justify-between pb-space-md">
                    <div class="flex items-center gap-space-xs">
                      <span class="material-symbols-outlined text-primary text-[24px]">price_change</span>
                      <h2 class="font-headline-md text-headline-md text-primary font-bold">Gestión de Oferta y Tarifas Diurnas</h2>
                    </div>

                    <!-- Switch Estado de Oferta -->
                    <div class="flex items-center gap-space-sm bg-surface-container-low px-space-md py-space-xs rounded-xl border border-surface-container">
                      <span class="font-label-sm text-label-sm text-on-surface font-semibold">Visible en Buscador:</span>
                      <label class="relative inline-flex items-center cursor-pointer">
                        <input
                          v-model="selectedOffer.isListingVisible"
                          type="checkbox"
                          class="sr-only peer"
                        />
                        <div class="w-11 h-6 bg-surface-container-highest peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-secondary"></div>
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
                        class="w-full h-56 object-cover object-center group-hover:scale-105 transition-transform duration-500"
                      />
                      <div class="absolute top-2 left-2 z-10">
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-lowest/95 backdrop-blur-md text-secondary font-label-sm text-label-sm shadow-sm font-bold">
                          <span class="material-symbols-outlined text-[14px]">verified</span>
                          Verificado In Situ
                        </span>
                      </div>
                      <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent p-space-sm text-on-primary">
                        <p class="font-label-sm text-label-sm text-primary-fixed">{{ selectedOffer.department }}</p>
                        <p class="font-headline-sm text-headline-sm truncate text-white font-bold">{{ selectedOffer.subtitle }}</p>
                      </div>
                    </div>

                    <!-- Features homologadas -->
                    <div class="md:col-span-7 flex flex-col justify-between gap-space-sm">
                      <div class="grid grid-cols-2 gap-space-sm">
                        <div class="p-space-sm rounded-lg bg-surface-container-low flex items-center gap-space-xs border border-surface-container">
                          <div class="w-8 h-8 rounded-md bg-surface-container-highest flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-[18px]">groups</span>
                          </div>
                          <div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant block">Aforo Homologado</span>
                            <span class="font-label-lg text-label-lg text-primary font-bold">Hasta 50 personas</span>
                          </div>
                        </div>

                        <div class="p-space-sm rounded-lg bg-surface-container-low flex flex-col justify-between gap-space-xs border border-surface-container">
                          <!-- Horario de Franja Diurna (sin elementos que rompan la vista) -->
                          <div class="flex items-center gap-space-xs">
                            <div class="w-8 h-8 rounded-md bg-surface-container-highest flex items-center justify-center text-tertiary shrink-0">
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
                                @click="toggleActiveDay(day.id)"
                                class="w-5 h-5 rounded text-[10px] font-bold flex items-center justify-center transition-all cursor-pointer"
                                :class="selectedOffer.schedule.activeDays.includes(day.id)
                                  ? 'bg-primary text-on-primary shadow-2xs'
                                  : 'bg-amber-100 text-amber-900 border border-amber-300 dark:bg-amber-950/60 dark:text-amber-200 line-through opacity-75'"
                                :title="day.fullName + (selectedOffer.schedule.activeDays.includes(day.id) ? ' (Habilitado)' : ' (Bloqueado)')"
                              >
                                {{ day.letter }}
                              </button>
                            </div>
                          </div>

                          <!-- Botón de configuración debajo de la tarjeta -->
                          <button
                            type="button"
                            @click="showScheduleModal = true"
                            class="w-full mt-0.5 py-1 px-2 rounded bg-surface-container-highest hover:bg-surface-container-high text-primary font-label-sm text-label-sm font-bold border border-surface-container flex items-center justify-center gap-1 cursor-pointer transition-colors"
                            title="Configurar horarios y días que se ofrece"
                          >
                            <span class="material-symbols-outlined text-[14px]">tune</span>
                            <span>Configurar</span>
                          </button>
                        </div>
                      </div>

                      <!-- Amenity Badges Homologados -->
                      <div class="flex flex-wrap gap-1.5">
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">pool</span> Pileta Cercada (1.80m)
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">outdoor_grill</span> Parrillero Techado
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">kitchen</span> 2 Freezers de Bebidas
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">park</span> 2.500 m² Césped
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">bolt</span> Disyuntor Diferencial OK
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">bed</span> Posee dormitorio
                        </span>
                        <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm border border-surface-container">
                          <span class="material-symbols-outlined text-secondary text-[14px]">camping</span> Posee lugar apto para acampar
                        </span>
                      </div>

                      <div class="bg-surface-container p-space-xs rounded-lg text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-primary">info</span>
                        <span>Los límites de aforo y sonido diurno están normados por Ley Provincial de Hospedaje Rural.</span>
                      </div>
                    </div>
                  </div>

                  <!-- TARIFAS EDITABLES MULTIMODALES -->
                  <form class="mt-space-lg flex flex-col gap-space-md" @submit.prevent="handleSaveTarifas">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                      <div>
                        <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Gestión Integral de Tarifas (ARS)</h3>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">Configure precios según modalidad: Diurno, Pernocte o Fiestas Nocturnas</p>
                      </div>

                      <!-- Selector de Pestañas de Tarifas -->
                      <div class="flex items-center gap-1 bg-surface-container-low p-1 rounded-xl border border-surface-container self-start sm:self-auto">
                        <button
                          type="button"
                          @click="tariffsTab = 'diurno'"
                          class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
                          :class="tariffsTab === 'diurno'
                            ? 'bg-secondary text-on-secondary shadow-xs'
                            : 'text-on-surface-variant hover:bg-surface-container'"
                        >
                          <span class="material-symbols-outlined text-[16px]">wb_sunny</span>
                          <span>Diurnas</span>
                        </button>

                        <button
                          type="button"
                          @click="tariffsTab = 'pernocte'"
                          class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
                          :class="tariffsTab === 'pernocte'
                            ? 'bg-primary text-on-primary shadow-xs'
                            : 'text-on-surface-variant hover:bg-surface-container'"
                        >
                          <span class="material-symbols-outlined text-[16px]">hotel</span>
                          <span>Pernocte / Estadía</span>
                        </button>

                        <button
                          type="button"
                          @click="tariffsTab = 'fiestas'"
                          class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 cursor-pointer"
                          :class="tariffsTab === 'fiestas'
                            ? 'bg-purple-700 text-white shadow-xs'
                            : 'text-on-surface-variant hover:bg-surface-container'"
                        >
                          <span class="material-symbols-outlined text-[16px]">nightlife</span>
                          <span>Fiestas &amp; Eventos</span>
                        </button>
                      </div>
                    </div>

                    <!-- TAB 1: TARIFAS DIURNAS DE TEMPORADA -->
                    <div v-if="tariffsTab === 'diurno'" class="grid grid-cols-1 sm:grid-cols-3 gap-space-md animate-in fade-in duration-150">
                      <!-- Tarifa Diurna 1 -->
                      <div class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container">
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
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Jornada base diurna ({{ calculateHoursDuration(selectedOffer.schedule.start, selectedOffer.schedule.end) }}h)</span>
                      </div>

                      <!-- Tarifa Diurna 2 -->
                      <div class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container">
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
                      <div class="p-space-sm rounded-xl bg-surface-container-low flex flex-col gap-space-xs border border-surface-container">
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

                    <!-- TAB 2: TARIFAS DE ESTADÍA Y PERNOCTE (VARIOS DÍAS) -->
                    <div v-else-if="tariffsTab === 'pernocte'" class="grid grid-cols-1 sm:grid-cols-3 gap-space-md animate-in fade-in duration-150">
                      <!-- Pernocte 1: Camas / Habitaciones -->
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

                      <!-- Pernocte 2: Carpas / Acampe -->
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

                      <!-- Pernocte 3: Paquete Fin de Semana Completo -->
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
                      <!-- Fiesta 1: Fiesta Nocturna (15 años, casamientos) -->
                      <div class="p-space-sm rounded-xl bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-space-xs border-2 border-purple-300 dark:border-purple-800 shadow-2xs">
                        <div class="flex items-center justify-between">
                          <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">Fiesta Nocturna (15 años / Bodas / Cenas)</span>
                          <span class="material-symbols-outlined text-[18px] text-purple-700 dark:text-purple-300">nightlife</span>
                        </div>
                        <div class="relative">
                          <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">$</span>
                          <input
                            v-model.number="selectedOffer.tariffs.fiestaNocturna"
                            type="number"
                            step="1000"
                            placeholder="120000"
                            class="w-full pl-7 pr-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
                          />
                        </div>
                        <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold">Franja nocturna hasta 05:00 hs • Potencia y limpieza</span>
                      </div>

                      <!-- Fiesta 2: Eventos Especiales Provinciales (FNS / Villicum) -->
                      <div class="p-space-sm rounded-xl bg-purple-50/70 dark:bg-purple-950/30 flex flex-col gap-space-xs border-2 border-purple-300 dark:border-purple-800 shadow-2xs">
                        <div class="flex items-center justify-between">
                          <span class="font-label-sm text-label-sm text-purple-900 dark:text-purple-200 font-bold">Eventos Especiales Provinciales</span>
                          <span class="material-symbols-outlined text-[18px] text-purple-700 dark:text-purple-300">festival</span>
                        </div>
                        <div class="relative">
                          <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">$</span>
                          <input
                            v-model.number="selectedOffer.tariffs.eventoEspecial"
                            type="number"
                            step="1000"
                            class="w-full pl-7 pr-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-headline-sm text-headline-sm font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 border border-purple-200 dark:border-purple-700"
                          />
                        </div>
                        <span class="font-label-sm text-label-sm text-purple-800 dark:text-purple-300 font-semibold">Alta demanda en la zona (FNS / Pernocte masivo)</span>
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
                        {{ tarifaSavedFeedback ? '¡Tarifas actualizadas y sincronizadas en la plataforma!' : 'Tarifas sincronizadas con la Red Oficial de Prestadores' }}
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

              <!-- SOLICITUD DE RE-VALIDACIÓN IN SITU (4 COLS) -->
              <section id="revalidacion-in-situ" class="lg:col-span-4 bg-surface-container-low rounded-xl p-space-lg shadow-sm flex flex-col justify-between border border-surface-container scroll-mt-20">
                <div class="flex flex-col gap-space-md">
                  <div class="flex items-center gap-space-xs">
                    <div class="w-10 h-10 rounded-lg bg-secondary/20 flex items-center justify-center shrink-0">
                      <span class="material-symbols-outlined text-secondary">fact_check</span>
                    </div>
                    <div>
                      <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Re-Validación In Situ</h2>
                      <span class="font-label-sm text-label-sm text-on-surface-variant">Inspección Técnica Oficial</span>
                    </div>
                  </div>

                  <p class="font-body-sm text-body-sm text-on-surface-variant">
                    ¿Realizó ampliaciones, instaló nuevo cerco en pileta o pintó el quincho? Solicite la visita presencial del inspector para certificar y renovar las fotos oficiales en la plataforma.
                  </p>

                  <form class="flex flex-col gap-space-sm" @submit.prevent="handleSolicitarRevalidacion">
                    <div>
                      <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Motivo principal de la visita</label>
                      <select
                        v-model="revalidacionForm.motivo"
                        class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container"
                      >
                        <option value="fotos">Actualización de fotos oficiales (Pintura / Quincho)</option>
                        <option value="pileta">Nuevo cerco perimetral de pileta reglamentario</option>
                        <option value="sanitarios">Ampliación de baños / Nuevos sanitarios</option>
                        <option value="electrico">Recertificación de disyuntores y seguridad eléctrica</option>
                        <option value="aforo">Solicitud de aumento de aforo homologado</option>
                      </select>
                    </div>

                    <div>
                      <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Fecha tentativa preferida</label>
                      <input
                        v-model="revalidacionForm.fecha"
                        type="date"
                        class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest border border-surface-container"
                      />
                    </div>

                    <div>
                      <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Notas para el Inspector Asignado</label>
                      <textarea
                        v-model="revalidacionForm.notas"
                        rows="3"
                        placeholder="Ej: Se instaló traba magnética en portón de pileta y cerámicas atérmicas nuevas..."
                        class="w-full p-2.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-highest resize-none border border-surface-container"
                      ></textarea>
                    </div>

                    <button
                      type="submit"
                      class="w-full mt-space-xs inline-flex items-center justify-center gap-space-xs px-space-md py-space-sm rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-sm font-bold cursor-pointer"
                    >
                      <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                      Solicitar Turno con Inspector
                    </button>
                  </form>
                </div>

                <div class="mt-space-md pt-space-sm text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1.5 border-t border-surface-container">
                  <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
                  Sin costo adicional para propietarios con Póliza Activa.
                </div>
              </section>
            </div>

            <!-- 3. ALMANAQUE / CALENDARIO INTERACTIVO DIURNO -->
            <section id="almanaque" class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container scroll-mt-20">
              <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-space-md">
                <div>
                  <div class="flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-primary text-[24px]">calendar_month</span>
                    <h2 class="font-headline-md text-headline-md text-primary font-bold">Calendario y Disponibilidad Diurna</h2>
                  </div>
                  <p class="font-body-sm text-body-sm text-on-surface-variant">
                    Haga clic en cualquier <strong class="text-secondary font-bold">día disponible</strong> para agendar directamente la reserva (Diurna, Multidía o Fiesta) con la fecha preseleccionada.
                  </p>
                </div>

                <!-- Acciones rápidas del calendario -->
                <div class="flex items-center gap-space-sm flex-wrap">
                  <!-- Navegación Universal de Mes y Año con Flechas -->
                  <div class="flex items-center gap-1 bg-surface-container-low px-2 py-1 rounded-lg border border-surface-container">
                    <button
                      type="button"
                      @click="prevMonth"
                      class="w-7 h-7 rounded flex items-center justify-center text-primary hover:bg-surface-container-high transition-colors cursor-pointer"
                      title="Mes anterior"
                    >
                      <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </button>

                    <div class="flex items-center gap-1">
                      <span class="material-symbols-outlined text-primary text-[16px]">calendar_month</span>
                      <select
                        v-model.number="selectedMonth"
                        class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
                      >
                        <option v-for="m in availableMonths" :key="m.value" :value="m.value">
                          {{ m.label }}
                        </option>
                      </select>
                      <select
                        v-model.number="selectedYear"
                        class="bg-transparent text-primary text-xs font-bold focus:outline-none cursor-pointer"
                      >
                        <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
                      </select>
                    </div>

                    <button
                      type="button"
                      @click="nextMonth"
                      class="w-7 h-7 rounded flex items-center justify-center text-primary hover:bg-surface-container-high transition-colors cursor-pointer"
                      title="Mes siguiente"
                    >
                      <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                  </div>

                  <!-- Botón Reservar Eventos Especiales (solo si el mes tiene eventos especiales) -->
                  <button
                    v-if="currentMonthEvents.length > 0"
                    type="button"
                    class="inline-flex items-center gap-1 px-space-md py-space-xs rounded-lg bg-purple-700 text-white hover:bg-purple-800 text-label-md font-label-md transition-all font-bold cursor-pointer shadow-xs animate-in fade-in duration-150"
                    title="Vincular fechas de este mes a eventos provinciales con tarifa diferenciada"
                    @click="openSpecialEventModal"
                  >
                    <span class="material-symbols-outlined text-[18px]">festival</span>
                    <span>Reservar Eventos Especiales</span>
                  </button>

                  <!-- Botón Bloquear Fechas (Familiar / Mantenimiento / Reserva Externa) -->
                  <button
                    type="button"
                    class="inline-flex items-center gap-1 px-space-md py-space-xs rounded-lg bg-surface-container-low hover:bg-surface-container-high text-on-surface font-label-md text-label-md transition-colors font-semibold cursor-pointer border border-surface-container"
                    title="Bloquear fechas para Uso Familiar, Mantenimiento o Reservas Particulares Fuera de Plataforma"
                    @click="openInternalBlockModal"
                  >
                    <span class="material-symbols-outlined text-[18px]">event_busy</span>
                    Bloquear Fechas / Uso Particular
                  </button>
                </div>
              </div>

              <!-- BANNER DE EVENTOS PROVINCIALES EN EL MES SELECCIONADO (SI LOS HAY) -->
              <div
                v-if="currentMonthEvents.length > 0"
                class="p-space-sm rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 flex flex-col sm:flex-row sm:items-center justify-between gap-space-sm"
              >
                <div class="flex items-center gap-space-sm">
                  <div class="w-9 h-9 rounded-lg bg-purple-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[20px]">festival</span>
                  </div>
                  <div>
                    <div class="flex items-center gap-1.5 flex-wrap">
                      <span class="font-label-md text-label-md font-bold text-purple-950 dark:text-purple-100">
                        Eventos Provinciales con Alta Demanda en este Mes:
                      </span>
                      <span
                        v-for="ev in currentMonthEvents"
                        :key="ev.id"
                        class="px-2 py-0.5 rounded-full bg-purple-200/70 dark:bg-purple-900/60 text-purple-900 dark:text-purple-200 text-xs font-bold"
                      >
                        {{ ev.title }} ({{ ev.startDate.split('-')[2] }}/{{ ev.startDate.split('-')[1] }} al {{ ev.endDate.split('-')[2] }}/{{ ev.endDate.split('-')[1] }})
                      </span>
                    </div>
                    <p class="font-body-xs text-[11px] text-purple-800 dark:text-purple-300">
                      Impactan sobre {{ selectedOffer.department }}. Puede bloquearlos para alquiler especial con pernocte y tarifa diferenciada.
                    </p>
                  </div>
                </div>

                <button
                  type="button"
                  @click="openSpecialEventModal"
                  class="px-space-md py-1.5 rounded-lg bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold shrink-0 cursor-pointer shadow-xs"
                >
                  Configurar Fechas del Evento
                </button>
              </div>

              <!-- Leyenda de colores del calendario -->
              <div class="flex items-center gap-space-md flex-wrap p-space-sm bg-surface-container-low rounded-lg text-label-sm font-label-sm border border-surface-container">
                <div class="flex items-center gap-1.5">
                  <div class="w-3.5 h-3.5 rounded-full bg-secondary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-secondary-container text-[10px]">check</span>
                  </div>
                  <span class="text-on-surface">Disponible para Alquiler</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <div class="w-3.5 h-3.5 rounded-full bg-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-primary text-[10px]">person</span>
                  </div>
                  <span class="text-on-surface">Reservado / Alquilado</span>
                </div>
                <!-- Morado: Evento Especial -->
                <div class="flex items-center gap-1.5">
                  <div class="w-3.5 h-3.5 rounded-full bg-purple-600 flex items-center justify-center shadow-2xs">
                    <span class="material-symbols-outlined text-white text-[10px]">festival</span>
                  </div>
                  <span class="text-on-surface font-bold text-purple-900 dark:text-purple-200">Evento Especial</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <div class="w-3.5 h-3.5 rounded-full bg-amber-200 border border-amber-400 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-950 text-[10px]">block</span>
                  </div>
                  <span class="text-on-surface font-semibold text-amber-900 dark:text-amber-300">Bloqueado</span>
                </div>
                <div class="flex items-center gap-1.5">
                  <div class="w-3.5 h-3.5 rounded-full bg-tertiary-fixed-dim flex items-center justify-center">
                    <span class="material-symbols-outlined text-tertiary text-[10px]">cottage</span>
                  </div>
                  <span class="text-on-surface">Uso Familiar / Mantenimiento</span>
                </div>
              </div>

              <!-- GRID DEL CALENDARIO -->
              <div class="w-full overflow-x-auto">
                <div class="min-w-[760px]">
                  <!-- Encabezados de días -->
                  <div class="grid grid-cols-7 gap-2 pb-2 text-center font-label-md text-label-md text-on-surface-variant font-bold">
                    <span>Lun</span>
                    <span>Mar</span>
                    <span>Mié</span>
                    <span>Jue</span>
                    <span>Vie</span>
                    <span class="text-secondary font-bold">Sáb</span>
                    <span class="text-secondary font-bold">Dom</span>
                  </div>

                  <!-- Cuadrícula de días -->
                  <div class="grid grid-cols-7 gap-2">
                    <!-- Días del mes dinámicos y sincronizados -->
                    <div
                      v-for="cDay in calendarDays"
                      :key="cDay.day"
                      :style="cDay.day === 1 ? { gridColumnStart: firstDayOffset } : {}"
                      class="calendar-cell h-24 p-2 rounded-xl flex flex-col justify-between transition-all select-none"
                      :class="{
                        'bg-surface-variant/40 text-on-surface-variant/70 border border-surface-container opacity-60 cursor-not-allowed': cDay.isPast && cDay.status !== 'booked' && cDay.status !== 'maintenance' && cDay.status !== 'blocked' && cDay.status !== 'special_event',
                        'bg-purple-100 text-purple-950 border-2 border-purple-400 dark:bg-purple-950/60 dark:text-purple-200 dark:border-purple-600 hover:bg-purple-200/90 cursor-pointer shadow-xs': cDay.status === 'special_event',
                        'bg-primary text-on-primary shadow-xs hover:opacity-95 cursor-pointer': cDay.status === 'booked',
                        'bg-secondary-container/50 text-on-surface hover:bg-secondary-container cursor-pointer': cDay.status === 'available' && !cDay.isPast,
                        'bg-amber-100 text-amber-950 border border-amber-300 dark:bg-amber-950/40 dark:text-amber-200 dark:border-amber-800 hover:bg-amber-200/80 cursor-pointer': cDay.status === 'unconfigured',
                        'bg-tertiary-fixed text-tertiary-container hover:bg-tertiary-fixed-dim cursor-pointer': cDay.status === 'maintenance',
                        'bg-surface-variant text-on-surface-variant hover:bg-surface-container-high cursor-pointer': cDay.status === 'blocked',
                      }"
                      @click="toggleCellState(cDay.day)"
                    >
                      <div class="flex items-center justify-between">
                        <span class="font-label-lg text-label-lg font-bold">{{ cDay.day }}</span>
                        <span
                          class="material-symbols-outlined text-[16px]"
                          :class="{
                            'text-purple-700 dark:text-purple-300 font-bold': cDay.status === 'special_event',
                            'text-secondary': cDay.status === 'available' && !cDay.isPast,
                            'text-amber-700 dark:text-amber-300': cDay.status === 'unconfigured',
                            'text-tertiary': cDay.status === 'maintenance',
                          }"
                        >
                          {{ cDay.icon || (cDay.status === 'special_event' ? 'festival' : (cDay.status === 'available' ? 'check_circle' : (cDay.status === 'unconfigured' ? 'block' : (cDay.isPast ? 'schedule' : 'event')))) }}
                        </span>
                      </div>
                      <div class="text-[11px] leading-tight truncate">
                        <p class="font-bold truncate" :class="{ 'text-purple-900 dark:text-purple-100': cDay.status === 'special_event', 'text-amber-900 dark:text-amber-200': cDay.status === 'unconfigured' }">
                          {{ cDay.title || (cDay.status === 'special_event' ? 'Evento FNS' : (cDay.isPast ? 'Concluido' : (cDay.status === 'available' ? 'Disponible' : (cDay.status === 'unconfigured' ? 'Bloqueado' : 'Bloqueado')))) }}
                        </p>
                        <p v-if="cDay.subtitle" class="opacity-90 truncate text-[10px]" :class="{ 'text-purple-800 dark:text-purple-300 font-bold': cDay.status === 'special_event' }">{{ cDay.subtitle }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            <!-- 4. TABLA DETALLADA DE PRÓXIMOS ALQUILERES AGENDADOS -->
            <section id="registro-inquilinos" class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container scroll-mt-20">
              <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-space-sm">
                <div>
                  <h2 class="font-headline-md text-headline-md text-primary font-bold">
                    {{ tenantTab === 'proximos' ? 'Próximos Alquileres Agendados' : 'Registro de Alquileres Pasados (Historial)' }}
                  </h2>
                  <span class="font-body-sm text-body-sm text-on-surface-variant">
                    {{ tenantTab === 'proximos'
                      ? 'Listado de inquilinos confirmados, seguros vigentes y control de turnos'
                      : 'Historial verificado de inquilinos con turnos concluidos, pólizas finalizadas y constancias' }}
                  </span>
                </div>

                <!-- Pestañas de Alquileres: Próximos vs Pasados -->
                <div class="flex items-center bg-surface-container-low rounded-lg p-1 border border-surface-container shrink-0">
                  <button
                    type="button"
                    class="px-space-md py-1.5 rounded font-label-sm text-label-sm font-bold cursor-pointer transition-all flex items-center gap-1.5"
                    :class="tenantTab === 'proximos'
                      ? 'bg-surface-container-lowest text-primary shadow-xs'
                      : 'text-on-surface-variant hover:text-on-surface'"
                    @click="tenantTab = 'proximos'"
                  >
                    <span class="material-symbols-outlined text-[16px]">upcoming</span>
                    <span>Próximos Alquileres</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-secondary-container text-on-secondary-container font-mono">
                      {{ selectedOffer.tenants?.length || 0 }}
                    </span>
                  </button>

                  <button
                    type="button"
                    class="px-space-md py-1.5 rounded font-label-sm text-label-sm font-bold cursor-pointer transition-all flex items-center gap-1.5"
                    :class="tenantTab === 'pasados'
                      ? 'bg-surface-container-lowest text-primary shadow-xs'
                      : 'text-on-surface-variant hover:text-on-surface'"
                    @click="tenantTab = 'pasados'"
                  >
                    <span class="material-symbols-outlined text-[16px]">history</span>
                    <span>Alquileres Pasados</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-surface-container-highest text-on-surface-variant font-mono">
                      {{ selectedOffer.pastTenants?.length || 0 }}
                    </span>
                  </button>
                </div>
              </div>

              <!-- Tabla Responsiva -->
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm">
                      <th class="py-space-sm px-space-md rounded-l-lg font-bold">Fecha &amp; Horario</th>
                      <th class="py-space-sm px-space-md font-bold">Inquilino Verificado</th>
                      <th class="py-space-sm px-space-md font-bold">Criterio Anfitrión</th>
                      <th class="py-space-sm px-space-md font-bold">Asistentes &amp; Evento</th>
                      <th class="py-space-sm px-space-md font-bold">Póliza / Seguro Homologado</th>
                      <th class="py-space-sm px-space-md rounded-r-lg text-right font-bold">Acciones</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-surface-container-high font-body-sm text-body-sm text-on-surface">
                    <!-- Vista de Próximos Alquileres -->
                    <template v-if="tenantTab === 'proximos'">
                      <tr
                        v-for="tenant in selectedOffer.tenants"
                        :key="tenant.id"
                        class="hover:bg-surface-container-low/60 transition-colors"
                      >
                        <td class="py-space-md px-space-md whitespace-nowrap">
                          <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                            <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.time }}</span>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <div>
                            <div class="flex items-center gap-1">
                              <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.name }}</span>
                              <span class="material-symbols-outlined text-secondary text-[16px]" title="DNI Verificado Renaper">verified</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">DNI {{ tenant.dni }} • San Juan</span>
                          </div>
                        </td>

                        <!-- Columna Criterio Anfitrión -->
                        <td class="py-space-md px-space-md whitespace-nowrap">
                          <div class="flex items-center gap-2">
                            <div
                              v-if="tenant.ownerRating === 'up'"
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold border border-secondary/20"
                              title="Inquilino recomendado con historial positivo"
                            >
                              <span class="material-symbols-outlined text-[15px]">thumb_up</span>
                              <span>Buen Inquilino</span>
                            </div>

                            <div
                              v-else-if="tenant.ownerRating === 'down'"
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                              title="Inquilino observado / No conforme"
                            >
                              <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                              <span>No Conforme</span>
                            </div>

                            <div
                              v-else
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                              title="Sin historial previo / Inquilino nuevo"
                            >
                              <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                              <span>Sin historial</span>
                            </div>

                            <div class="flex items-center gap-0.5 bg-surface-container-low p-0.5 rounded-lg border border-surface-container">
                              <button
                                type="button"
                                @click="setTenantRating(tenant, 'up')"
                                class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                                :class="tenant.ownerRating === 'up'
                                  ? 'bg-secondary text-on-secondary shadow-2xs'
                                  : 'text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'"
                                title="Marcar como Buen Inquilino (Pulgar arriba)"
                              >
                                <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                              </button>
                              <button
                                type="button"
                                @click="setTenantRating(tenant, 'down')"
                                class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                                :class="tenant.ownerRating === 'down'
                                  ? 'bg-error text-on-error shadow-2xs'
                                  : 'text-on-surface-variant hover:text-error hover:bg-surface-container-high'"
                                title="Marcar como No Conforme (Pulgar abajo)"
                              >
                                <span class="material-symbols-outlined text-[14px]">thumb_down</span>
                              </button>
                            </div>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <div class="flex flex-col">
                            <span class="font-label-md text-label-md text-primary font-bold">{{ tenant.guestsText }}</span>
                            <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.eventType }}</span>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-bold">
                            <span class="material-symbols-outlined text-[14px]">shield</span>
                            {{ tenant.insuranceBadge }}
                          </span>
                        </td>

                        <td class="py-space-md px-space-md text-right whitespace-nowrap">
                          <div class="inline-flex items-center gap-1">
                            <a
                              :href="`https://wa.me/${tenant.phone.replace(/[^0-9]/g, '')}`"
                              target="_blank"
                              class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary hover:bg-secondary hover:text-on-secondary flex items-center justify-center transition-all cursor-pointer"
                              title="Contactar por WhatsApp"
                            >
                              <span class="material-symbols-outlined text-[18px]">chat</span>
                            </a>
                            <button
                              type="button"
                              class="px-space-sm py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm transition-colors cursor-pointer font-bold"
                              @click="openTenantDetail(tenant)"
                            >
                              Ficha
                            </button>
                          </div>
                        </td>
                      </tr>
                      <tr v-if="!selectedOffer.tenants || selectedOffer.tenants.length === 0">
                        <td colspan="6" class="py-space-lg text-center text-on-surface-variant">
                          No hay próximos alquileres programados para esta propiedad.
                        </td>
                      </tr>
                    </template>

                    <!-- Vista de Alquileres Pasados (Historial) -->
                    <template v-else>
                      <tr
                        v-for="tenant in selectedOffer.pastTenants"
                        :key="tenant.id"
                        class="hover:bg-surface-container-low/60 transition-colors"
                      >
                        <td class="py-space-md px-space-md whitespace-nowrap">
                          <div class="flex flex-col">
                            <div class="flex items-center gap-1.5">
                              <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                              <span class="px-1.5 py-0.5 rounded text-[10px] bg-surface-container-high text-on-surface-variant font-bold">Concluido</span>
                            </div>
                            <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.time }}</span>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <div>
                            <div class="flex items-center gap-1">
                              <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.name }}</span>
                              <span class="material-symbols-outlined text-secondary text-[16px]" title="DNI Verificado Renaper">verified</span>
                            </div>
                            <span class="font-label-sm text-label-sm text-on-surface-variant">DNI {{ tenant.dni }} • San Juan</span>
                          </div>
                        </td>

                        <!-- Columna Criterio Anfitrión -->
                        <td class="py-space-md px-space-md whitespace-nowrap">
                          <div class="flex items-center gap-2">
                            <div
                              v-if="tenant.ownerRating === 'up'"
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold border border-secondary/20"
                              title="Inquilino recomendado con historial positivo"
                            >
                              <span class="material-symbols-outlined text-[15px]">thumb_up</span>
                              <span>Buen Inquilino</span>
                            </div>

                            <div
                              v-else-if="tenant.ownerRating === 'down'"
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                              title="Inquilino no conforme / Observado"
                            >
                              <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                              <span>No Conforme</span>
                            </div>

                            <div
                              v-else
                              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                              title="Sin historial previo / Inquilino nuevo"
                            >
                              <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                              <span>Sin historial</span>
                            </div>

                            <div class="flex items-center gap-0.5 bg-surface-container-low p-0.5 rounded-lg border border-surface-container">
                              <button
                                type="button"
                                @click="setTenantRating(tenant, 'up')"
                                class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                                :class="tenant.ownerRating === 'up'
                                  ? 'bg-secondary text-on-secondary shadow-2xs'
                                  : 'text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'"
                                title="Marcar como Buen Inquilino (Pulgar arriba)"
                              >
                                <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                              </button>
                              <button
                                type="button"
                                @click="setTenantRating(tenant, 'down')"
                                class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                                :class="tenant.ownerRating === 'down'
                                  ? 'bg-error text-on-error shadow-2xs'
                                  : 'text-on-surface-variant hover:text-error hover:bg-surface-container-high'"
                                title="Marcar como No Conforme (Pulgar abajo)"
                              >
                                <span class="material-symbols-outlined text-[14px]">thumb_down</span>
                              </button>
                            </div>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <div class="flex flex-col">
                            <span class="font-label-md text-label-md text-primary font-bold">{{ tenant.guestsText }}</span>
                            <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.eventType }}</span>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md">
                          <div class="flex flex-col gap-0.5">
                            <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold w-fit">
                              <span class="material-symbols-outlined text-[14px]">shield</span>
                              {{ tenant.insuranceBadge }}
                            </span>
                            <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                              <span class="material-symbols-outlined text-[13px] text-secondary">verified</span>
                              Turno Cumplido y Auditado
                            </span>
                          </div>
                        </td>

                        <td class="py-space-md px-space-md text-right whitespace-nowrap">
                          <div class="inline-flex items-center gap-1">
                            <button
                              type="button"
                              class="inline-flex items-center gap-1 px-space-sm py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm transition-colors cursor-pointer font-bold"
                              @click="openTenantDetail(tenant)"
                            >
                              <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                              Constancia
                            </button>
                          </div>
                        </td>
                      </tr>
                      <tr v-if="!selectedOffer.pastTenants || selectedOffer.pastTenants.length === 0">
                        <td colspan="6" class="py-space-lg text-center text-on-surface-variant">
                          No hay alquileres pasados registrados para esta propiedad.
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </section>

          </div>
        </div>
      </main>
    </div>

    <!-- ========================================== -->
    <!-- 5. MODAL AGENDAR NUEVA RESERVA             -->
    <!-- ========================================== -->
    <!-- ============================================================== -->
    <!-- 5. MODAL AGENDAR RESERVA / BLOQUEAR FECHAS (UNIFICADO)           -->
    <!-- ============================================================== -->
    <div
      v-if="showBookingModal"
      class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
    >
      <div class="bg-surface-container-lowest rounded-xl shadow-xl max-w-2xl w-full p-space-lg flex flex-col gap-space-md my-8 border border-surface-container animate-in fade-in duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-space-sm border-b border-surface-container">
          <div class="flex items-center gap-space-xs">
            <div
              class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
              :class="newBooking.modality === 'bloqueo'
                ? (newBooking.blockType === 'externo' ? 'bg-rose-100 dark:bg-rose-950/50 text-rose-700' : 'bg-amber-100 dark:bg-amber-950/50 text-amber-700')
                : 'bg-secondary/20 text-secondary'"
            >
              <span class="material-symbols-outlined">
                {{ newBooking.modality === 'bloqueo'
                  ? (newBooking.blockType === 'externo' ? 'no_crash' : (newBooking.blockType === 'familiar' ? 'cottage' : 'construction'))
                  : 'person_add' }}
              </span>
            </div>
            <div>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
                {{ newBooking.modality === 'bloqueo' ? 'Bloquear Fecha / Indisponibilidad' : 'Agendar Nueva Reserva de Inquilino' }}
              </h3>
              <span class="font-label-sm text-label-sm text-on-surface-variant">{{ selectedOffer.title }} ({{ selectedOffer.legajo }})</span>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition-colors cursor-pointer"
            @click="showBookingModal = false"
          >
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <!-- Form Content -->
        <form class="flex flex-col gap-space-md" @submit.prevent="handleAddBooking">
          <!-- 1. MODALIDAD DE ESTADÍAS, HORARIOS Y AFORO / BLOQUEO (4 Opciones) -->
          <div class="flex flex-col gap-space-xs">
            <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
              1. Modalidad de Estadías, Horarios y Aforo
            </span>

            <!-- Selector de 4 Modalidades -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 p-1.5 bg-surface-container-low rounded-xl border border-surface-container">
              <!-- Opción 1: Diurno -->
              <button
                type="button"
                @click="newBooking.modality = 'diurno'"
                class="p-2 rounded-lg flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                :class="newBooking.modality === 'diurno'
                  ? 'bg-secondary text-on-secondary shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'"
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
                :class="newBooking.modality === 'multidia'
                  ? 'bg-primary text-on-primary shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'"
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
                :class="newBooking.modality === 'fiesta_nocturna'
                  ? 'bg-purple-700 text-white shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'"
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
                :class="newBooking.modality === 'bloqueo'
                  ? 'bg-amber-600 text-white shadow-xs font-bold'
                  : 'text-on-surface-variant hover:bg-surface-container'"
              >
                <div class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[17px]">event_busy</span>
                  <span class="text-xs font-bold">Bloquear Fecha</span>
                </div>
                <span class="text-[10px] opacity-85">Familia, Mantenimiento, Externa</span>
              </button>
            </div>
          </div>

          <!-- ======================================================== -->
          <!-- SECCIÓN SI MODALIDAD ES BLOQUEAR FECHA                  -->
          <!-- ======================================================== -->
          <template v-if="newBooking.modality === 'bloqueo'">
            <!-- Tipo de Bloqueo / Motivo General -->
            <div class="flex flex-col gap-space-xs pt-space-xs">
              <span class="font-label-md text-label-md uppercase tracking-wider text-amber-700 dark:text-amber-400 font-bold">
                2. Motivo del Bloqueo e Indisponibilidad
              </span>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                <!-- Bloqueo 1: Uso Familiar -->
                <button
                  type="button"
                  @click="newBooking.blockType = 'familiar'; newBooking.reason = 'Uso Familiar'"
                  class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                  :class="newBooking.blockType === 'familiar'
                    ? 'bg-amber-100/70 border-amber-500 text-amber-950 font-bold ring-2 ring-amber-400/50 dark:bg-amber-950/50 dark:text-amber-200'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'"
                >
                  <span class="material-symbols-outlined text-[20px] text-amber-700 dark:text-amber-300">cottage</span>
                  <span class="text-xs font-bold">Uso Familiar</span>
                  <span class="text-[10px] text-amber-800 dark:text-amber-300">Privado</span>
                </button>

                <!-- Bloqueo 2: Mantenimiento -->
                <button
                  type="button"
                  @click="newBooking.blockType = 'mantenimiento'; newBooking.reason = 'Mantenimiento del Predio'"
                  class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                  :class="newBooking.blockType === 'mantenimiento'
                    ? 'bg-primary/10 border-primary text-primary font-bold ring-2 ring-primary/30'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'"
                >
                  <span class="material-symbols-outlined text-[20px] text-primary">construction</span>
                  <span class="text-xs font-bold">Mantenimiento</span>
                  <span class="text-[10px] text-on-surface-variant">Arreglos / Poda</span>
                </button>

                <!-- Bloqueo 3: Reserva Externa Particular -->
                <button
                  type="button"
                  @click="newBooking.blockType = 'externo'; newBooking.reason = 'Reserva Particular Fuera de Plataforma'"
                  class="p-2.5 rounded-xl border flex flex-col items-center gap-1 text-center transition-all cursor-pointer"
                  :class="newBooking.blockType === 'externo'
                    ? 'bg-rose-100/80 border-rose-500 text-rose-950 font-bold ring-2 ring-rose-400/50 dark:bg-rose-950/50 dark:text-rose-200'
                    : 'bg-surface-container-low border-surface-container text-on-surface-variant hover:bg-surface-container'"
                >
                  <span class="material-symbols-outlined text-[20px] text-rose-700 dark:text-rose-300">no_crash</span>
                  <span class="text-xs font-bold">Reserva Externa</span>
                  <span class="text-[10px] text-rose-800 dark:text-rose-300">Fuera de Plataforma</span>
                </button>
              </div>
            </div>

            <!-- Rango de Fechas a Bloquear (Un día o varios días) -->
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
                  :placeholder="newBooking.blockType === 'externo' ? 'Ej: Sin cobertura de Turismo Seguro' : 'Ej: Asado familiar / Reparación de bomba'"
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container"
                />
              </div>
            </div>

            <!-- ALERTA / CONSTANCIA LEGAL PARA RESERVAS EXTERNAS Y COBERTURA -->
            <div
              v-if="newBooking.blockType === 'externo'"
              class="p-space-sm rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-900 dark:text-rose-200 flex flex-col gap-1.5 animate-in fade-in duration-150"
            >
              <div class="flex items-center gap-1.5 font-bold">
                <span class="material-symbols-outlined text-[18px] text-rose-700 dark:text-rose-400">gavel</span>
                <span>Constancia Legal y Deslinde de Responsabilidad:</span>
              </div>
              <p class="text-[11px] leading-relaxed">
                Al declarar esta fecha como <strong>Bloqueada por Reserva Externa</strong>, queda registrado fehacientemente que el inmueble <strong>no estuvo disponible para alquiler dentro del sistema</strong>. Ante cualquier incidente, siniestro o reclamo de terceros, la plataforma y la aseguradora contarán con la constancia de que la estadía fue acordada fuera de Turismo Seguro San Juan, eximiendo de validación, certificación o amparo de póliza.
              </p>
            </div>
            <div v-else class="p-space-sm rounded-lg bg-surface-container text-xs text-on-surface-variant flex items-start gap-2">
              <span class="material-symbols-outlined text-primary text-[18px] shrink-0">info</span>
              <span>
                Al bloquear la(s) fecha(s), el buscador público y los turistas verán los días como no disponibles, preservando su predio para uso exclusivo.
              </span>
            </div>
          </template>

          <!-- ======================================================== -->
          <!-- SECCIÓN SI ES RESERVA DE INQUILINO                      -->
          <!-- ======================================================== -->
          <template v-else>
            <!-- 2. Datos personales del inquilino titular -->
            <div class="flex flex-col gap-space-xs">
              <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">
                2. Datos Personales del Inquilino Titular
              </span>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Nombre y Apellido</label>
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
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Celular con WhatsApp</label>
                  <input
                    v-model="newBooking.tenantPhone"
                    type="tel"
                    required
                    placeholder="Ej: +54 9 264 455-8822"
                    class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Localidad de Origen</label>
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
              <div v-if="newBooking.modality === 'diurno'" class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm animate-in fade-in duration-150">
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Fecha de la Jornada</label>
                  <input
                    v-model="newBooking.date"
                    type="date"
                    required
                    class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Turno Homologado</label>
                  <input
                    type="text"
                    disabled
                    :value="`Diurno (${selectedOffer.schedule.start} a ${selectedOffer.schedule.end} hs)`"
                    class="w-full px-3 py-2 rounded-lg bg-surface-container text-on-surface-variant font-label-sm text-label-sm border border-surface-container"
                  />
                </div>
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Tipo de Evento</label>
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
              <div v-else-if="newBooking.modality === 'multidia'" class="p-space-sm rounded-xl bg-primary/5 border border-primary/20 flex flex-col gap-space-sm animate-in fade-in duration-150">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
                  <div>
                    <label class="block font-label-sm text-label-sm text-primary font-bold mb-1">Check-in (Entrada)</label>
                    <input
                      v-model="newBooking.date"
                      type="date"
                      required
                      class="w-full px-3 py-2 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-body-sm focus:outline-none border border-surface-container font-bold"
                    />
                  </div>
                  <div>
                    <label class="block font-label-sm text-label-sm text-primary font-bold mb-1">Check-out (Salida)</label>
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
                    <label class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors" :class="newBooking.overnightType === 'camas' ? 'border-primary text-primary font-bold bg-primary/10' : 'border-surface-container text-on-surface-variant'">
                      <input type="radio" v-model="newBooking.overnightType" value="camas" class="text-primary" />
                      <span class="text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">bed</span>
                        En Camas / Habitaciones
                      </span>
                    </label>
                    <label class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors" :class="newBooking.overnightType === 'carpas' ? 'border-primary text-primary font-bold bg-primary/10' : 'border-surface-container text-on-surface-variant'">
                      <input type="radio" v-model="newBooking.overnightType" value="carpas" class="text-primary" />
                      <span class="text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">camping</span>
                        En Carpas / Acampe
                      </span>
                    </label>
                    <label class="p-2 rounded-lg border bg-surface-container-lowest flex items-center gap-2 cursor-pointer transition-colors" :class="newBooking.overnightType === 'ambas' ? 'border-primary text-primary font-bold bg-primary/10' : 'border-surface-container text-on-surface-variant'">
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
              <div v-else-if="newBooking.modality === 'fiesta_nocturna'" class="p-space-sm rounded-xl bg-purple-50/60 dark:bg-purple-950/30 border border-purple-300 dark:border-purple-800 flex flex-col gap-space-sm animate-in fade-in duration-150">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-sm">
                  <div>
                    <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">Fecha del Festejo</label>
                    <input
                      v-model="newBooking.date"
                      type="date"
                      required
                      class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700 font-bold"
                    />
                  </div>
                  <div>
                    <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">Hora Inicio</label>
                    <input
                      v-model="newBooking.nightStart"
                      type="time"
                      class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700"
                    />
                  </div>
                  <div>
                    <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">Hora Cierre</label>
                    <input
                      v-model="newBooking.nightEnd"
                      type="time"
                      class="w-full px-3 py-2 rounded-lg bg-white dark:bg-surface-container-lowest text-purple-950 dark:text-purple-100 font-body-sm text-body-sm focus:outline-none border border-purple-200 dark:border-purple-700"
                    />
                  </div>
                </div>

                <div>
                  <label class="block font-label-sm text-label-sm text-purple-950 dark:text-purple-200 font-bold mb-1">Tipo de Festejo Nocturno</label>
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
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Niños (hasta 12 años)</label>
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
                :class="isOverCapacity ? 'bg-error-container border-error/30' : 'bg-surface-container-low border-surface-container'"
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
              <span class="font-label-md text-label-md uppercase tracking-wider text-secondary font-bold">4. Seguro y Cobertura Obligatoria</span>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
                <div>
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Póliza Aplicada</label>
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
                  <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Estado de Verificación de Inquilino</label>
                  <div class="px-3 py-2 rounded-lg bg-surface-container-low text-secondary font-body-sm text-body-sm border border-surface-container flex items-center gap-1.5 font-bold">
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
              @click="showBookingModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg font-label-lg text-label-lg transition-all shadow-sm font-bold cursor-pointer"
              :class="newBooking.modality === 'bloqueo'
                ? (newBooking.blockType === 'externo' ? 'bg-rose-700 text-white hover:bg-rose-800' : 'bg-amber-700 text-white hover:bg-amber-800')
                : 'bg-secondary text-on-secondary hover:bg-on-secondary-container'"
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

    <!-- ========================================== -->
    <!-- 6. MODAL FICHA INTEGRAL DE INQUILINO       -->
    <!-- ========================================== -->
    <div
      v-if="showTenantModal && selectedTenant"
      class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4"
    >
      <div class="bg-surface-container-lowest rounded-xl shadow-xl max-w-lg w-full p-space-lg flex flex-col gap-space-md border border-surface-container animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
          <div class="flex items-center gap-space-xs">
            <span class="material-symbols-outlined text-secondary text-[24px]">assignment_ind</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Ficha Integral del Inquilino</h3>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
            @click="showTenantModal = false"
          >
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between border border-surface-container">
          <div>
            <h4 class="font-headline-sm text-headline-sm text-primary font-bold">{{ selectedTenant.name }}</h4>
            <span class="font-body-sm text-body-sm text-on-surface-variant">DNI {{ selectedTenant.dni }}</span>
          </div>
          <div class="text-right">
            <span class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold">
              <span class="material-symbols-outlined text-[14px]">verified</span> Validado
            </span>
          </div>
        </div>

        <div class="space-y-space-xs font-body-sm text-body-sm text-on-surface">
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Fecha de ingreso:</span>
            <strong class="text-primary font-bold">{{ selectedTenant.date }}</strong>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Turno contratado:</span>
            <span class="text-primary">{{ selectedTenant.time }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Motivo del evento:</span>
            <span class="text-primary">{{ selectedTenant.eventType }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Cantidad asistentes:</span>
            <strong class="text-secondary font-bold">{{ selectedTenant.guestsText }}</strong>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Póliza asegurada:</span>
            <span class="text-secondary font-bold flex items-center gap-1">
              <span class="material-symbols-outlined text-[15px]">shield</span>
              {{ selectedTenant.insuranceBadge }}
            </span>
          </div>
          <div class="flex justify-between py-1 border-b border-surface-container">
            <span class="text-on-surface-variant">Estado de cobertura:</span>
            <span class="text-secondary font-bold flex items-center gap-1">
              <span class="material-symbols-outlined text-[15px]">verified_user</span>
              {{ selectedTenant.totalPrice ? 'Turno Finalizado & Póliza Rendida' : 'Cobertura Activa y Asistida' }}
            </span>
          </div>
        </div>

        <div class="p-space-sm rounded-lg bg-surface-container flex flex-col gap-1">
          <span class="font-label-sm text-label-sm text-on-surface font-bold">Historial en Red Turismo Seguro San Juan:</span>
          <p class="font-body-sm text-body-sm text-secondary font-semibold">
            {{ selectedTenant.history }}
          </p>
        </div>

        <!-- Criterio Anfitrión en la Ficha -->
        <div class="p-space-sm rounded-lg bg-surface-container-low border border-surface-container flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
          <div>
            <span class="font-label-sm text-label-sm text-primary font-bold block">Tu Criterio como Anfitrión:</span>
            <span class="text-xs text-on-surface-variant">Calificación del inquilino para futuras reservas.</span>
          </div>
          <div class="flex items-center gap-1.5 shrink-0">
            <button
              type="button"
              @click="setTenantRating(selectedTenant, 'up')"
              class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
              :class="selectedTenant.ownerRating === 'up'
                ? 'bg-secondary text-on-secondary shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'"
            >
              <span class="material-symbols-outlined text-[16px]">thumb_up</span>
              <span>Buen Inquilino</span>
            </button>
            <button
              type="button"
              @click="setTenantRating(selectedTenant, 'down')"
              class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
              :class="selectedTenant.ownerRating === 'down'
                ? 'bg-error text-on-error shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-error hover:bg-surface-container-high'"
            >
              <span class="material-symbols-outlined text-[16px]">thumb_down</span>
              <span>No Conforme</span>
            </button>
          </div>
        </div>

        <div class="flex items-center justify-end gap-space-sm pt-space-xs">
          <button
            type="button"
            class="w-full py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold cursor-pointer"
            @click="showTenantModal = false"
          >
            Cerrar Ficha
          </button>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- 7. MODAL AGREGAR OFERTA                    -->
    <!-- ========================================== -->
    <div
      v-if="showAddOfferModal"
      class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto"
    >
      <div class="bg-surface-container-lowest rounded-xl shadow-xl max-w-xl w-full p-space-lg flex flex-col gap-space-md my-8 border border-surface-container animate-in fade-in duration-200">
        <div class="flex items-center justify-between pb-space-sm border-b border-surface-container">
          <div class="flex items-center gap-space-xs">
            <div class="w-10 h-10 rounded-lg bg-primary text-on-primary flex items-center justify-center">
              <span class="material-symbols-outlined text-secondary-fixed">add_business</span>
            </div>
            <div>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Agregar Nueva Oferta</h3>
              <p class="text-xs text-on-surface-variant">Publicá tu quinta o cabaña con inspección in situ oficial</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
            @click="showAddOfferModal = false"
          >
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <form class="flex flex-col gap-space-md" @submit.prevent="handleAddOffer">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm">
            <div class="sm:col-span-2">
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Nombre del Predio / Quinta</label>
              <input
                v-model="newOffer.title"
                type="text"
                required
                placeholder="Ej: Quinta Los Olivos del Médano"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              />
            </div>

            <div>
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Departamento</label>
              <select
                v-model="newOffer.department"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              >
                <option value="Rawson, San Juan">Rawson (Médano de Oro)</option>
                <option value="Pocito, San Juan">Pocito (Quinto Cuartel)</option>
                <option value="Zonda, San Juan">Zonda (Sierras Azules)</option>
                <option value="Ullum, San Juan">Ullum (Lago)</option>
                <option value="Rivadavia, San Juan">Rivadavia</option>
              </select>
            </div>

            <div>
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Capacidad Aforo</label>
              <input
                v-model.number="newOffer.capacity"
                type="number"
                min="5"
                max="120"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              />
            </div>

            <div class="sm:col-span-2">
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Dirección / Callejón</label>
              <input
                v-model="newOffer.location"
                type="text"
                required
                placeholder="Ej: Calle 11 s/n entre Ruta 40 y Vidart"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              />
            </div>

            <div>
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Tarifa Lunes a Jueves ($)</label>
              <input
                v-model.number="newOffer.rateWeekday"
                type="number"
                step="1000"
                required
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              />
            </div>

            <div>
              <label class="block font-label-sm text-label-sm text-on-surface mb-1 font-semibold">Tarifa Sábados ($)</label>
              <input
                v-model.number="newOffer.rateWeekend"
                type="number"
                step="1000"
                required
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container"
              />
            </div>
          </div>

          <div class="p-space-sm rounded-lg bg-secondary-container/40 border border-secondary/20 flex items-center gap-space-xs text-xs text-on-surface">
            <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
            <span>Al publicar se coordinará automáticamente la visita técnica in situ para homologar el predio.</span>
          </div>

          <div class="flex items-center justify-end gap-space-sm pt-space-xs border-t border-surface-container">
            <button
              type="button"
              class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors cursor-pointer font-semibold"
              @click="showAddOfferModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="inline-flex items-center gap-space-xs px-space-lg py-space-sm rounded-lg bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-sm font-bold cursor-pointer"
            >
              <span class="material-symbols-outlined text-[18px]">add_circle</span>
              Guardar y Solicitar Inspección
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: CONFIGURAR FRANJA DIURNA Y DÍAS DE ALQUILER -->
    <div
      v-if="showScheduleModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-scrim/40 backdrop-blur-xs"
      @click.self="showScheduleModal = false"
    >
      <div class="w-full max-w-lg bg-surface-container-lowest rounded-2xl shadow-xl border border-surface-container overflow-hidden flex flex-col max-h-[90vh]">
        <!-- Encabezado del modal -->
        <div class="p-space-md border-b border-surface-container flex items-center justify-between bg-surface-container-low">
          <div class="flex items-center gap-space-xs">
            <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
              <span class="material-symbols-outlined text-[20px]">tune</span>
            </div>
            <div>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Configurar Franja Diurna y Días</h3>
              <p class="font-body-sm text-body-sm text-on-surface-variant truncate max-w-xs">{{ selectedOffer.title }}</p>
            </div>
          </div>
          <button
            type="button"
            class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
            @click="showScheduleModal = false"
          >
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <div class="p-space-md overflow-y-auto flex flex-col gap-space-md">
          <!-- Franja Horaria -->
          <div>
            <label class="block font-label-md text-label-md text-primary font-bold mb-2">Horario de Atención Diurna</label>
            <div class="grid grid-cols-2 gap-space-sm">
              <div>
                <span class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Apertura (Check-in)</span>
                <input
                  v-model="selectedOffer.schedule.start"
                  type="time"
                  required
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container font-semibold"
                />
              </div>
              <div>
                <span class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Cierre (Check-out)</span>
                <input
                  v-model="selectedOffer.schedule.end"
                  type="time"
                  required
                  class="w-full px-3 py-2 rounded-lg bg-surface-container-low text-primary font-body-sm text-body-sm focus:outline-none focus:bg-surface-container border border-surface-container font-semibold"
                />
              </div>
            </div>
            <p class="mt-1.5 font-label-sm text-label-sm text-on-surface-variant">
              Duración total de jornada: <span class="font-bold text-primary">{{ calculateHoursDuration(selectedOffer.schedule.start, selectedOffer.schedule.end) }} horas</span> por turno homologado.
            </p>
          </div>

          <!-- Días Ofrecidos -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="block font-label-md text-label-md text-primary font-bold">Días de la Semana Habilitados</label>
              <div class="flex items-center gap-1">
                <button
                  type="button"
                  @click="setSchedulePreset('all')"
                  class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
                >
                  Todos
                </button>
                <span class="text-outline text-xs">•</span>
                <button
                  type="button"
                  @click="setSchedulePreset('thu-sun')"
                  class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
                >
                  Jue-Dom
                </button>
                <span class="text-outline text-xs">•</span>
                <button
                  type="button"
                  @click="setSchedulePreset('fri-sun')"
                  class="text-[11px] font-bold text-primary hover:underline px-1 py-0.5 cursor-pointer"
                >
                  Vie-Dom
                </button>
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <button
                v-for="day in weekDayDefs"
                :key="day.id"
                type="button"
                @click="toggleActiveDay(day.id)"
                class="p-2.5 rounded-lg border text-left flex items-center justify-between transition-all cursor-pointer"
                :class="selectedOffer.schedule.activeDays.includes(day.id)
                  ? 'bg-primary-container/20 border-primary text-primary font-bold'
                  : 'bg-surface-container-low border-surface-container text-on-surface-variant opacity-75'"
              >
                <span class="text-xs">{{ day.fullName }}</span>
                <span class="material-symbols-outlined text-[16px]">
                  {{ selectedOffer.schedule.activeDays.includes(day.id) ? 'check_circle' : 'do_not_disturb_on' }}
                </span>
              </button>
            </div>
          </div>

          <!-- Aviso de impacto en calendario -->
          <div class="p-space-sm rounded-lg bg-amber-50 border border-amber-300 dark:bg-amber-950/40 dark:border-amber-800 flex items-start gap-space-xs text-xs text-amber-950 dark:text-amber-200">
            <span class="material-symbols-outlined text-amber-700 dark:text-amber-300 text-[18px] shrink-0 mt-0.5">info</span>
            <div>
              <p class="font-bold">Impacto directo en el Calendario:</p>
              <p>Los días que no estén habilitados se mostrarán automáticamente con el estado <span class="font-bold text-amber-900 dark:text-amber-200 underline">Bloqueado</span> (color naranja) en el almanaque. Los días con reservas previas o fechas ya concluidas mantendrán su registro legal intacto.</p>
            </div>
          </div>
        </div>

        <!-- Acciones del modal -->
        <div class="p-space-md border-t border-surface-container flex items-center justify-end gap-space-sm bg-surface-container-low">
          <button
            type="button"
            class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold transition-colors cursor-pointer"
            @click="showScheduleModal = false"
          >
            Cerrar
          </button>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-space-lg py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container hover:text-on-primary-container transition-all font-bold cursor-pointer shadow-sm"
            @click="showScheduleModal = false"
          >
            <span class="material-symbols-outlined text-[18px]">check</span>
            Aplicar y Sincronizar
          </button>
        </div>
      </div>
    </div>

    <!-- ======================================================= -->
    <!-- 8. MODAL DE VINCULACIÓN A EVENTOS PROVINCIALES (FNS)   -->
    <!-- ======================================================= -->
    <div
      v-if="showSpecialEventModal"
      class="fixed inset-0 bg-primary/40 backdrop-blur-xs z-50 flex items-center justify-center p-space-md overflow-y-auto"
      @click.self="showSpecialEventModal = false"
    >
      <div class="bg-surface-container-lowest rounded-2xl max-w-xl w-full border border-purple-300 dark:border-purple-800 shadow-2xl flex flex-col max-h-[92vh] overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-150">
        <!-- Header del Modal -->
        <div class="p-space-lg border-b border-surface-container flex items-center justify-between bg-purple-50 dark:bg-purple-950/40">
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
            class="w-8 h-8 rounded-lg flex items-center justify-center text-purple-900 dark:text-purple-300 hover:bg-purple-200/50 transition-colors cursor-pointer"
            @click="showSpecialEventModal = false"
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
              Mostrando eventos configurados institucionalmente que impactan en {{ selectedOffer.department }} o toda la provincia.
            </span>
          </div>

          <!-- Tarjeta Informativa del Evento Seleccionado -->
          <div v-if="selectedSpecialEventDef" class="p-space-sm rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
              <span class="font-label-md text-label-md font-bold text-primary">{{ selectedSpecialEventDef.title }}</span>
              <span class="px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-950 text-purple-800 dark:text-purple-200 text-xs font-bold border border-purple-200 dark:border-purple-800">
                {{ selectedSpecialEventDef.venue }}
              </span>
            </div>
            <p class="text-xs text-on-surface-variant leading-relaxed">
              {{ selectedSpecialEventDef.description }}
            </p>
            <div class="text-[11px] text-purple-800 dark:text-purple-300 font-medium pt-1 border-t border-surface-container/60 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">calendar_today</span>
              <span>Fechas fijadas: {{ selectedSpecialEventDef.startDate }} hasta {{ selectedSpecialEventDef.endDate }}</span>
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
          <div class="p-space-md rounded-xl bg-purple-50/50 dark:bg-purple-950/20 border border-purple-200 dark:border-purple-800/60 flex flex-col gap-space-sm">
            <div>
              <label class="block font-label-md text-label-md text-purple-950 dark:text-purple-200 font-bold mb-1">
                Tarifa Especial por Jornada / Noche (ARS):
              </label>
              <div class="relative">
                <span class="absolute left-3 top-2.5 text-purple-800 dark:text-purple-300 font-label-lg text-label-lg font-bold">$</span>
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
            <label class="flex items-start gap-2.5 cursor-pointer pt-2 border-t border-purple-200/60 dark:border-purple-800/40 select-none">
              <input
                v-model="specialEventForm.allowsOvernight"
                type="checkbox"
                class="mt-1 w-4 h-4 rounded text-purple-700 focus:ring-purple-500 border-surface-container"
              />
              <div>
                <span class="font-label-md text-label-md font-bold text-purple-950 dark:text-purple-200 flex items-center gap-1">
                  <span class="material-symbols-outlined text-[16px]">bed</span>
                  Habilitar Pernocte (Camas para dormir tras el evento)
                </span>
                <span class="font-body-xs text-[11px] text-on-surface-variant block">
                  Permite al visitante pernoctar después del festival, recital o carrera del Villicum en lugar de limitarse a jornada diurna.
                </span>
              </div>
            </label>
          </div>

          <div class="p-space-sm rounded-lg bg-surface-container flex items-start gap-2 text-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-primary text-[18px] shrink-0">info</span>
            <span>
              Al vincular, los días del rango se destacarán en <strong class="text-purple-800 dark:text-purple-300">color morado</strong> en el calendario y el buscador mostrará esta tarifa diferenciada a quienes busquen hospedaje para esas fechas.
            </span>
          </div>
        </div>

        <!-- Footer del Modal -->
        <div class="p-space-md border-t border-surface-container flex items-center justify-end gap-space-sm bg-surface-container-low">
          <button
            type="button"
            class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg font-semibold transition-colors cursor-pointer"
            @click="showSpecialEventModal = false"
          >
            Cancelar
          </button>
          <button
            type="button"
            class="inline-flex items-center gap-1.5 px-space-lg py-space-sm rounded-lg bg-purple-700 text-white font-label-lg text-label-lg hover:bg-purple-800 transition-all font-bold cursor-pointer shadow-sm"
            @click="applySpecialEventToCalendar"
          >
            <span class="material-symbols-outlined text-[18px]">check</span>
            Vincular al Calendario
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
