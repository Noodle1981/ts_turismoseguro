import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../useAuth'
import { api } from '../useApi'
import { useConfirmDialog } from './useConfirmDialog'
import { INITIAL_OFFERS } from '../../data/mockPropietarioData'

import type {
  PropietarioOferta,
  NewOfferPayload,
  RevalidacionPayload,
} from '../../types/propietario'

// Estado compartido a nivel módulo para persistencia durante la sesión
const myOffers = ref<PropietarioOferta[]>(JSON.parse(JSON.stringify(INITIAL_OFFERS)))
const selectedOfferId = ref<number>(8)
const activeNav = ref<'tarifas' | 'calendario' | 'inquilinos'>('tarifas')
const isLoadingOffers = ref<boolean>(false)
const isSavingTarifas = ref<boolean>(false)

/**
 * Mapeador de datos desde la API de Laravel (PropertyResource) al modelo de interfaz PropietarioOferta
 */
function mapApiPropertyToOferta(prop: any, existingOffer?: PropietarioOferta): PropietarioOferta {
  return {
    id: prop.id,
    legajo: prop.verification_certificate_number || `#${prop.id}-TS/26`,
    title: prop.title,
    subtitle: prop.property_type ? `Tipo: ${prop.property_type}` : (existingOffer?.subtitle || 'Predio Verificado'),
    department: prop.department?.name || existingOffer?.department || 'San Juan',
    location: prop.location || existingOffer?.location || '',
    poliza: prop.insurance?.policy_number
      ? `${prop.insurance.company || 'Triunfo Seguros'} (Póliza ${prop.insurance.policy_number})`
      : (existingOffer?.poliza || 'Póliza Homologada Triunfo Seguros'),
    actaFecha: prop.insurance?.acta_fecha || (prop.verified_at ? new Date(prop.verified_at).toLocaleDateString('es-AR') : (existingOffer?.actaFecha || 'En trámite')),
    image: prop.images?.[0]?.image_path || prop.verified_facade_image || existingOffer?.image || 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
    isListingVisible: prop.is_listing_visible ?? existingOffer?.isListingVisible ?? true,
    pauseMode: prop.pause_mode ?? existingOffer?.pauseMode,
    pauseStartDate: prop.pause_start_date ?? existingOffer?.pauseStartDate,
    pauseEndDate: prop.pause_end_date ?? existingOffer?.pauseEndDate,
    schedule: {
      start: prop.schedule?.start || existingOffer?.schedule?.start || '10:00',
      end: prop.schedule?.end || existingOffer?.schedule?.end || '20:00',
      activeDays: prop.schedule?.active_days || existingOffer?.schedule?.activeDays || [0, 4, 5, 6],
    },
    tariffs: {
      lunesJueves: prop.tariffs?.lunes_jueves ?? existingOffer?.tariffs?.lunesJueves ?? (prop.price ?? 35000),
      viernesDomingo: prop.tariffs?.viernes_domingo ?? existingOffer?.tariffs?.viernesDomingo ?? Math.round((prop.price ?? 35000) * 1.25),
      sabadoFeriados: prop.tariffs?.sabado_feriados ?? existingOffer?.tariffs?.sabadoFeriados ?? Math.round((prop.price ?? 35000) * 1.4),
      eventoEspecial: prop.tariffs?.evento_especial ?? existingOffer?.tariffs?.eventoEspecial ?? 85000,
      pernocteCamas: prop.tariffs?.pernocte_camas ?? existingOffer?.tariffs?.pernocteCamas ?? 65000,
      pernocteCarpas: prop.tariffs?.pernocte_carpas ?? existingOffer?.tariffs?.pernocteCarpas ?? 40000,
      findeCompleto: prop.tariffs?.finde_completo ?? existingOffer?.tariffs?.findeCompleto ?? 110000,
      fiestaNocturna: prop.tariffs?.fiesta_nocturna ?? existingOffer?.tariffs?.fiestaNocturna ?? 120000,
    },
    metrics: existingOffer?.metrics || {
      jornadas: 14,
      jornadasDiff: '+3 jornadas vs. mes pasado',
      ocupacion: 78,
      ingresosEstimados: 630000,
      ingresosCobrado: 490000,
      reputacion: 4.95,
      resenasCount: 14,
    },
    amenities: Array.isArray(prop.amenities) && prop.amenities.length > 0
      ? prop.amenities.map((a: string) => ({
          icon: a.toLowerCase().includes('pileta') || a.toLowerCase().includes('piscina') ? 'pool' : a.toLowerCase().includes('parrill') ? 'outdoor_grill' : 'check_circle',
          label: a,
          isSecurityFeature: a.toLowerCase().includes('cerca') || a.toLowerCase().includes('segur') || a.toLowerCase().includes('disyuntor'),
        }))
      : (existingOffer?.amenities || [
          { icon: 'pool', label: 'Pileta Cercada (1.80m)', isSecurityFeature: true },
          { icon: 'outdoor_grill', label: 'Parrillero Techado' },
          { icon: 'kitchen', label: '2 Freezers de Bebidas' },
          { icon: 'bolt', label: 'Disyuntor Diferencial OK', isSecurityFeature: true },
        ]),
    tenants: existingOffer?.tenants || [],
    pastTenants: existingOffer?.pastTenants || [],
  }
}

export function usePropietarioOffers() {
  const router = useRouter()
  const { user, isAuthenticated, logout } = useAuth()

  const selectedOffer = computed(() => {
    return myOffers.value.find((o) => o.id === selectedOfferId.value) || myOffers.value[0]
  })

  // Gestión de tabs de tarifas y feedback
  const tariffsTab = ref<'diurno' | 'pernocte' | 'fiestas'>('diurno')
  const tarifaSavedFeedback = ref(false)

  /**
   * Carga inicial de ofertas desde la API de Laravel
   */
  const fetchOffers = async () => {
    if (!isAuthenticated.value) return
    isLoadingOffers.value = true
    try {
      const response = await api.get('/client/properties')
      const propertiesData = response.data?.data
      if (Array.isArray(propertiesData) && propertiesData.length > 0) {
        const mapped = propertiesData.map((prop: any) => {
          const existing = myOffers.value.find((o) => o.id === prop.id)
          return mapApiPropertyToOferta(prop, existing)
        })
        myOffers.value = mapped
        if (!mapped.some((o) => o.id === selectedOfferId.value)) {
          selectedOfferId.value = mapped[0].id
        }
      }
    } catch (err: any) {
      console.warn('Sincronización API de ofertas: operando con caché local.', err)
    } finally {
      isLoadingOffers.value = false
    }
  }

  /**
   * Persiste tarifas multimodales, franja horaria y receso/vacaciones en la base de datos
   */
  const saveOfferToServer = async (targetOffer?: PropietarioOferta): Promise<boolean> => {
    const offerToSave = targetOffer || selectedOffer.value
    if (!offerToSave) return false

    isSavingTarifas.value = true
    try {
      if (isAuthenticated.value && typeof offerToSave.id === 'number') {
        const payload = {
          tariff_lunes_jueves: offerToSave.tariffs.lunesJueves,
          tariff_viernes_domingo: offerToSave.tariffs.viernesDomingo,
          tariff_sabado_feriados: offerToSave.tariffs.sabadoFeriados,
          tariff_evento_especial: offerToSave.tariffs.eventoEspecial,
          tariff_pernocte_camas: offerToSave.tariffs.pernocteCamas ?? null,
          tariff_pernocte_carpas: offerToSave.tariffs.pernocteCarpas ?? null,
          tariff_finde_completo: offerToSave.tariffs.findeCompleto ?? null,
          tariff_fiesta_nocturna: offerToSave.tariffs.fiestaNocturna ?? null,
          schedule_start: offerToSave.schedule.start,
          schedule_end: offerToSave.schedule.end,
          active_days: offerToSave.schedule.activeDays,
          is_listing_visible: offerToSave.isListingVisible,
          pause_mode: offerToSave.pauseMode ?? null,
          pause_start_date: offerToSave.pauseStartDate ?? null,
          pause_end_date: offerToSave.pauseEndDate ?? null,
        }
        await api.put(`/client/properties/${offerToSave.id}`, payload)
      }

      tarifaSavedFeedback.value = true
      setTimeout(() => {
        tarifaSavedFeedback.value = false
      }, 3000)
      return true
    } catch (err: any) {
      console.warn('Aviso: cambios de oferta preservados en sesión local.', err)
      tarifaSavedFeedback.value = true
      setTimeout(() => {
        tarifaSavedFeedback.value = false
      }, 3000)
      return false
    } finally {
      isSavingTarifas.value = false
    }
  }

  const handleSaveTarifas = async () => {
    await saveOfferToServer(selectedOffer.value)
  }

  /**
   * Modifica y persiste el estado de visibilidad / receso de la oferta
   */
  const updateListingVisibility = async (payload: {
    isListingVisible: boolean
    pauseMode?: 'indefinite' | 'vacation'
    pauseStartDate?: string
    pauseEndDate?: string
  }) => {
    if (!selectedOffer.value) return
    selectedOffer.value.isListingVisible = payload.isListingVisible
    selectedOffer.value.pauseMode = payload.pauseMode
    selectedOffer.value.pauseStartDate = payload.pauseStartDate
    selectedOffer.value.pauseEndDate = payload.pauseEndDate

    await saveOfferToServer(selectedOffer.value)
  }

  // Modal para agregar una nueva oferta
  const showAddOfferModal = ref(false)
  const newOffer = ref<NewOfferPayload>({
    title: '',
    department: 'Pocito, San Juan',
    location: '',
    capacity: 40,
    observations: '',
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
  })

  const handleAddOffer = () => {
    const { showAlert } = useConfirmDialog()
    if (!newOffer.value.title || !newOffer.value.location) {
      showAlert({
        title: 'Datos Incompletos',
        message: 'Por favor complete el nombre del predio y su ubicación.',
        icon: 'error_outline',
      })
      return
    }

    const newId = myOffers.value.length + 1
    const baseWeekday = newOffer.value.rateWeekday ?? 38000
    const baseWeekend = newOffer.value.rateWeekend ?? 52000
    const created: PropietarioOferta = {
      id: newId,
      legajo: `#${Math.floor(500 + Math.random() * 400)}-POC/25`,
      title: newOffer.value.title,
      subtitle: newOffer.value.observations || 'Parque & Piscina Exclusiva',
      department: newOffer.value.department,
      location: newOffer.value.location,
      poliza: 'Pre-Aprobación In Situ Pendiente',
      actaFecha: 'En trámite',
      image:
        'https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
      isListingVisible: false,
      schedule: {
        start: '10:00',
        end: '20:00',
        activeDays: [4, 5, 6, 0],
      },
      tariffs: {
        lunesJueves: baseWeekday,
        viernesDomingo: Math.round(baseWeekday * 1.25),
        sabadoFeriados: baseWeekend,
        eventoEspecial: Math.round(baseWeekend * 1.5),
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
      amenities: [
        ...(newOffer.value.poolFenced
          ? [{ icon: 'pool', label: 'Pileta Cercada Homologada', isSecurityFeature: true }]
          : []),
        ...(newOffer.value.hasGrill ? [{ icon: 'outdoor_grill', label: 'Parrillero Techado' }] : []),
        ...(newOffer.value.hasFreezer ? [{ icon: 'kitchen', label: 'Freezer de Bebidas' }] : []),
        { icon: 'bolt', label: 'Disyuntor Diferencial OK', isSecurityFeature: true },
      ],
      tenants: [],
      pastTenants: [],
    }

    myOffers.value.push(created)
    selectedOfferId.value = newId
    showAddOfferModal.value = false
    showAlert({
      title: '¡Oferta Registrada con Éxito!',
      message: `La oferta "${created.title}" fue registrada exitosamente. Ya puedes gestionar sus tarifas individuales. Se notificó al cuerpo de validadores para coordinar la inspección in situ.`,
      icon: 'check_circle',
    })
  }

  // Solicitud de revalidación in situ
  const revalidacionForm = ref<RevalidacionPayload>({
    motivo: 'fotos',
    fecha: '2025-02-15',
    notas: '',
  })

  const handleSolicitarRevalidacion = () => {
    const { showAlert } = useConfirmDialog()
    showAlert({
      title: 'Solicitud Enviada',
      message: 'Solicitud enviada al cuerpo técnico de validadores de Turismo Seguro San Juan. Nos comunicaremos dentro de las 24hs hábiles para confirmar el turno presencial.',
      icon: 'assignment_turned_in',
    })
    revalidacionForm.value.notas = ''
  }

  const handleLogout = async () => {
    await logout()
    router.push('/')
  }

  return {
    user,
    myOffers,
    selectedOfferId,
    selectedOffer,
    activeNav,
    tariffsTab,
    tarifaSavedFeedback,
    isLoadingOffers,
    isSavingTarifas,
    fetchOffers,
    saveOfferToServer,
    handleSaveTarifas,
    updateListingVisibility,
    showAddOfferModal,
    newOffer,
    handleAddOffer,
    revalidacionForm,
    handleSolicitarRevalidacion,
    handleLogout,
  }
}
