import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../useAuth'
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

export function usePropietarioOffers() {
  const router = useRouter()
  const { user, logout } = useAuth()

  const selectedOffer = computed(() => {
    return myOffers.value.find((o) => o.id === selectedOfferId.value) || myOffers.value[0]
  })

  // Gestión de formulario y guardado de tarifas
  const tariffsTab = ref<'diurno' | 'pernocte' | 'fiestas'>('diurno')
  const tarifaSavedFeedback = ref(false)

  const handleSaveTarifas = () => {
    tarifaSavedFeedback.value = true
    setTimeout(() => {
      tarifaSavedFeedback.value = false
    }, 3000)
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
    handleSaveTarifas,
    showAddOfferModal,
    newOffer,
    handleAddOffer,
    revalidacionForm,
    handleSolicitarRevalidacion,
    handleLogout,
  }
}
