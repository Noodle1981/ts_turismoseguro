export interface AmenityBadge {
  icon: string
  label: string
  isSecurityFeature?: boolean
}

export interface Tenant {
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
  sena?: string
  saldo?: string
  totalPrice?: string
  history: string
  status: string
  ownerRating?: 'up' | 'down' | 'none'
}

export interface PropietarioTariffs {
  lunesJueves: number
  viernesDomingo: number
  sabadoFeriados: number
  eventoEspecial: number
  pernocteCamas?: number
  pernocteCarpas?: number
  findeCompleto?: number
  fiestaNocturna?: number
}

export interface PropietarioSchedule {
  start: string
  end: string
  activeDays: number[]
}

export interface PropietarioMetrics {
  jornadas: number
  jornadasDiff: string
  ocupacion: number
  ingresosEstimados: number
  ingresosCobrado: number
  reputacion: number
  resenasCount: number
}

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
  tariffs: PropietarioTariffs
  schedule: PropietarioSchedule
  isListingVisible: boolean
  metrics: PropietarioMetrics
  amenities: AmenityBadge[]
  tenants: Tenant[]
  pastTenants: Tenant[]
}

export interface CalendarDay {
  day: number
  dateStr: string
  status: 'available' | 'booked' | 'blocked' | 'maintenance' | 'past' | 'unconfigured' | 'special_event' | 'external_booking'
  isPast?: boolean
  title?: string
  subtitle?: string
  icon?: string
  specialEventSlug?: string
  specialRate?: number
  allowsOvernight?: boolean
}

export interface ConfirmDialogState {
  isOpen: boolean
  title: string
  message: string
  confirmText?: string
  cancelText?: string
  type?: 'confirm' | 'alert' | 'danger'
  icon?: string
  onConfirm?: () => void
  onCancel?: () => void
}


export interface ProvincialEvent {
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

export interface NewBookingPayload {
  modality: 'diurno' | 'multidia' | 'fiesta_nocturna' | 'bloqueo'
  blockType: 'familiar' | 'mantenimiento' | 'externo'
  tenantName: string
  tenantDni: string
  tenantPhone: string
  tenantCity: string
  date: string
  endDate: string
  nightStart: string
  nightEnd: string
  overnightType: 'camas' | 'carpas' | 'ambas'
  reason: string
  adults: number
  children: number
  insurance: string
  notes: string
  sena: string
  saldo: string
}

export interface RevalidacionPayload {
  motivo: string
  fecha: string
  notas: string
}

export interface NewOfferPayload {
  title: string
  department: string
  location: string
  capacity: number
  observations?: string
  rateWeekday?: number
  rateWeekend?: number
  poolFenced: boolean
  hasGrill: boolean
  hasFreezer: boolean
}

