document.addEventListener('DOMContentLoaded', function () {
    // --- Base de Datos Simulada (a futuro esto vendrá de una API/Backend) ---
    const alquileresDB = {
        "123": { // ID del alquiler
            nombre: "Refréscate en el Medanito de Rawson",
            ubicacion: "Rawson, San Juan",
            precio: 25000,
            tipoPrecio: "por día",
            descripcion: "Disfruta de un día de pileta y relax en esta hermosa quinta ubicada en El Medanito, Rawson. Ideal para familias y grupos de amigos. Cuenta con una amplia piscina, quincho con parrilla, y todas las comodidades para pasar un día inolvidable. Fácil acceso y total privacidad.",
            imagenes: [
                "img/propiedades/medanopileta.jpg",
                "https://via.placeholder.com/800x500/6c757d/ffffff?text=Medanito+Foto+2",
                "https://via.placeholder.com/800x500/6c757d/ffffff?text=Medanito+Foto+3"
            ],
            habitaciones: 0, // Es para pasar el día, podría no tener hab. de pernocte
            banos: 2,
            capacidad: 15, // Capacidad para el día
            comodidades: ["Piscina", "Parrilla", "WiFi", "Estacionamiento", "Baños equipados"],
            reglas: ["No se permiten mascotas.", "Horario de uso de 10:00 a 20:00.", "Música a volumen moderado."],
            anfitrionWhatsapp: "549264XXXXXXX", // Reemplazar con número real o dejar null
            anfitrionTelefono: "264XXXXXXX"    // Reemplazar con número real o dejar null
        },
        "456": {
            nombre: "La Posada del Angel",
            ubicacion: "Zonda, San Juan",
            precio: 35000,
            tipoPrecio: "/noche",
            descripcion: "Encantadora posada en el corazón de Zonda, rodeada de montañas y viñedos. Ofrece un ambiente tranquilo y acogedor, perfecto para una escapada romántica o unas vacaciones en familia. Disfruta de nuestras cómodas habitaciones, piscina y vistas espectaculares.",
            imagenes: [
                "img/propiedades/laposadadelangel.jpg",
                "https://via.placeholder.com/800x500/90EE90/000000?text=Posada+Angel+Foto+2",
                "https://via.placeholder.com/800x500/90EE90/000000?text=Posada+Angel+Foto+3",
                "https://via.placeholder.com/800x500/90EE90/000000?text=Posada+Angel+Foto+4"
            ],
            habitaciones: 3,
            banos: 2,
            capacidad: 6,
            comodidades: ["Piscina", "Parrilla", "WiFi", "Cochera", "Aire Acondicionado", "Vistas a la Montaña"],
            // reglas: [], // Si no hay reglas específicas, no se muestra la sección
            anfitrionWhatsapp: null, 
            anfitrionTelefono: "264YYYYYYY"
        },
        "789": {
            nombre: "Casona la Robertita",
            ubicacion: "Santa Lucía, San Juan",
            precio: 60000,
            tipoPrecio: "/noche",
            descripcion: "Amplia casona con gran parque y piscina, ideal para grandes familias o grupos. Ubicada en una zona tranquila de Santa Lucía, ofrece todas las comodidades para una estadía confortable y divertida. Cuenta con múltiples habitaciones, quincho cerrado y un hermoso jardín.",
            imagenes: [
                "img/propiedades/CasonalaRobertita.jpg",
                "https://via.placeholder.com/800x500/E0BBE4/000000?text=Robertita+Foto+2"
            ],
            habitaciones: 4,
            banos: 3,
            capacidad: 10,
            comodidades: ["Piscina Privada", "Parrilla Grande", "WiFi de Alta Velocidad", "Cochera Cubierta", "Jardín Amplio", "TV Cable"],
            reglas: ["No se permiten fiestas ni eventos sin autorización previa.", "Respetar horarios de descanso."],
            anfitrionWhatsapp: "549264ZZZZZZZ", 
            anfitrionTelefono: null
        }
        // ...Añadir más alquileres con sus IDs
    };

    // --- Funciones para cargar datos ---
    function cargarDetallesAlquiler(alquilerId) {
        const alquiler = alquileresDB[alquilerId];

        if (!alquiler) {
            document.getElementById('property-details-main').innerHTML = '<h1 class="text-danger">Alquiler no encontrado</h1><p>El alquiler que buscas no existe o ha sido eliminado. <a href="alquileres.html">Volver al listado</a></p>';
            document.getElementById('property-sidebar').style.display = 'none'; // Ocultar sidebar si no hay alquiler
            document.title = "Alquiler no encontrado - Turismo Seguro San Juan";
            return;
        }

        // Cambiar título de la página
        document.title = `${alquiler.nombre} - Turismo Seguro San Juan`;

        // Cargar datos en el HTML
        document.getElementById('propertyTitle').textContent = alquiler.nombre;
        document.getElementById('propertyLocation').textContent = alquiler.ubicacion;
        
        // Galería de Imágenes
        const carouselInner = document.getElementById('carouselInner');
        const carouselIndicators = document.getElementById('carouselIndicators');
        carouselInner.innerHTML = ''; // Limpiar placeholders
        carouselIndicators.innerHTML = '';
        alquiler.imagenes.forEach((imgUrl, index) => {
            const activeClass = index === 0 ? 'active' : '';
            carouselInner.innerHTML += `
                <div class="carousel-item ${activeClass}">
                    <img src="${imgUrl}" class="d-block w-100" alt="Imagen ${index + 1} de ${alquiler.nombre}">
                </div>`;
            carouselIndicators.innerHTML += `
                <button type="button" data-bs-target="#propertyImageCarousel" data-bs-slide-to="${index}" class="${activeClass}" aria-current="${activeClass ? 'true' : 'false'}" aria-label="Slide ${index + 1}"></button>`;
        });


        document.getElementById('propertyDescription').textContent = alquiler.descripcion;
        document.getElementById('propertyBedrooms').textContent = alquiler.habitaciones > 0 ? `${alquiler.habitaciones}` : 'N/A (ej. solo día)';
        document.getElementById('propertyBathrooms').textContent = alquiler.banos;
        document.getElementById('propertyCapacity').textContent = alquiler.capacidad;
        
        const amenitiesList = document.getElementById('propertyAmenities');
        amenitiesList.innerHTML = ''; // Limpiar
        alquiler.comodidades.forEach(com => {
            // Intentar encontrar un ícono de Font Awesome (esto es muy básico)
            let iconClass = 'fa-check-square'; // Icono por defecto
            if (com.toLowerCase().includes('piscina')) iconClass = 'fa-swimmer';
            if (com.toLowerCase().includes('wifi')) iconClass = 'fa-wifi';
            if (com.toLowerCase().includes('parrilla')) iconClass = 'fa-utensils'; // o fa-fire
            if (com.toLowerCase().includes('cochera') || com.toLowerCase().includes('estacionamiento')) iconClass = 'fa-car';
            if (com.toLowerCase().includes('aire')) iconClass = 'fa-snowflake';
            if (com.toLowerCase().includes('mascotas')) iconClass = 'fa-paw';

            amenitiesList.innerHTML += `<li><i class="fas ${iconClass} fa-fw"></i> ${com}</li>`;
        });

        // Reglas de la casa
        const rulesCard = document.getElementById('propertyRulesCard');
        const rulesList = document.getElementById('propertyRulesList');
        if (alquiler.reglas && alquiler.reglas.length > 0) {
            rulesList.innerHTML = '';
            alquiler.reglas.forEach(regla => {
                rulesList.innerHTML += `<li><i class="fas fa-gavel fa-fw text-muted"></i> ${regla}</li>`;
            });
            rulesCard.style.display = 'block';
        } else {
            rulesCard.style.display = 'none';
        }


        // Sidebar
        document.getElementById('propertyPrice').innerHTML = `$${alquiler.precio.toLocaleString('es-AR')} <span class="fs-6 text-muted">${alquiler.tipoPrecio}</span>`;
        
        // Lógica de botones de contacto directo
        const whatsappBtn = document.getElementById('contactHostWhatsapp');
        const phoneBtn = document.getElementById('contactHostPhone');

        if(alquiler.anfitrionWhatsapp) {
            whatsappBtn.href = `https://wa.me/${alquiler.anfitrionWhatsapp.replace(/\D/g,'')}?text=${encodeURIComponent(`Hola, te contacto desde Turismo Seguro por el alquiler: ${alquiler.nombre}`)}`;
            whatsappBtn.style.display = 'block';
        } else {
            whatsappBtn.style.display = 'none';
        }

        if(alquiler.anfitrionTelefono) {
            phoneBtn.href = `tel:${alquiler.anfitrionTelefono.replace(/\D/g,'')}`;
            phoneBtn.style.display = 'block';
        } else {
            phoneBtn.style.display = 'none';
        }
    }

    // --- Obtener ID del Alquiler de la URL y Cargar ---
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const alquilerIdFromUrl = getQueryParam('id');
    if (alquilerIdFromUrl) {
        cargarDetallesAlquiler(alquilerIdFromUrl);
    } else {
        // Manejar caso donde no hay ID (mostrar error o redirigir)
        document.getElementById('property-details-main').innerHTML = '<h1 class="text-danger">Error</h1><p>No se especificó un alquiler para mostrar. <a href="alquileres.html">Volver al listado</a></p>';
        document.getElementById('property-sidebar').style.display = 'none';
        document.title = "Error - Turismo Seguro San Juan";
    }

    // --- Manejo del Formulario de Consulta (simulado) ---
    const bookingForm = document.getElementById('bookingForm');
    const formBookingMessage = document.getElementById('formBookingMessage');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const checkin = document.getElementById('checkinDate').value;
            const checkout = document.getElementById('checkoutDate').value;
            const guests = document.getElementById('guestCount').value;

            if (!checkin || !checkout || !guests) {
                formBookingMessage.className = 'alert alert-danger';
                formBookingMessage.textContent = 'Por favor, completa todos los campos de fecha y huéspedes.';
                return;
            }
            if (new Date(checkout) <= new Date(checkin)) {
                formBookingMessage.className = 'alert alert-danger';
                formBookingMessage.textContent = 'La fecha de salida debe ser posterior a la fecha de llegada.';
                return;
            }

            // Simulación de envío
            formBookingMessage.className = 'alert alert-success';
            formBookingMessage.textContent = `Consulta enviada para ${guests} huéspedes, desde ${checkin} hasta ${checkout}. Nos pondremos en contacto pronto. (Simulación)`;
            
            // bookingForm.reset(); // Opcional: resetear el formulario
            console.log(`Consulta simulada: Alquiler ID ${alquilerIdFromUrl}, Check-in: ${checkin}, Check-out: ${checkout}, Huéspedes: ${guests}`);
        });
    }
    const currentYearFooter = document.getElementById('currentYear'); // Asegúrate que el ID sea único o usa uno específico para esta página si es necesario
    if (currentYearFooter) {
        currentYearFooter.textContent = new Date().getFullYear();
    }
});