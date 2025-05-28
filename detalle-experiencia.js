document.addEventListener('DOMContentLoaded', function () {
    const experienciasDB = {
        "exp101": {
            nombre: "Tour de las estrellas en el Parque Nacional El Leoncito",
            ubicacionPrincipal: "Barreal, Calingasta",
            departamento: "Calingasta",
            tipo: ["Nocturna", "Naturaleza", "Científica"],
            precioPorPersona: 12000,
            descripcionCorta: "Una noche mágica bajo uno de los cielos más limpios del mundo.",
            descripcionLarga: "Sumérgete en la inmensidad del cosmos desde el Parque Nacional El Leoncito, un sitio privilegiado por su atmósfera diáfana. Esta experiencia incluye una charla introductoria sobre astronomía, observación con telescopios profesionales de cúmulos estelares, nebulosas y planetas, guiada por expertos. Aprenderás a reconocer constelaciones y te maravillarás con la Vía Láctea como nunca antes la viste.",
            imagenes: [
                "img/experiencies/cartelparqueleoncito.jpg",
                "https://via.placeholder.com/800x500/000080/FFFFFF?text=Leoncito+Estrellas+1",
                "https://via.placeholder.com/800x500/191970/FFFFFF?text=Leoncito+Estrellas+2"
            ],
            duracionEstimada: "3-4 horas (nocturno)",
            queIncluye: ["Guía astronómico especializado", "Uso de telescopios", "Charla introductoria", "Chocolate caliente (en invierno)"],
            queNoIncluye: ["Traslado al Parque Nacional (consultar opciones)", "Cena"],
            recomendaciones: ["Llevar abrigo incluso en verano", "Calzado cómodo", "No usar linternas de luz blanca durante la observación"],
            operadorVerificado: {
                nombre: "AstroTours San Juan",
                whatsapp: "5492641234567",
                email: "info@astrotourssj.com"
            }
        },
        "exp102": {
            nombre: "Excursión a Ischigualasto (Valle de la Luna)",
            ubicacionPrincipal: "Parque Provincial Ischigualasto, Valle Fértil",
            departamento: "Valle Fértil",
            tipo: ["Naturaleza", "Cultural", "Paleontología"],
            precioPorPersona: 20000,
            descripcionCorta: "Viaja millones de años al pasado en un paisaje de otro planeta, Patrimonio de la Humanidad.",
            descripcionLarga: "Descubre las geoformas erosionadas por el viento y el agua que dan a Ischigualasto su apariencia lunar. Esta excursión de día completo te llevará a través del circuito tradicional del parque, visitando formaciones icónicas como El Hongo, El Submarino y la Cancha de Bochas. Aprende sobre la rica historia paleontológica del lugar, hogar de algunos de los fósiles de dinosaurios más antiguos del mundo. Incluye visita al museo del sitio.",
            imagenes: [
                "img/experiencies/valledelaluna.jpg",
                "https://via.placeholder.com/800x500/DEB887/000000?text=Ischigualasto+Paisaje+1",
                "https://via.placeholder.com/800x500/CD853F/FFFFFF?text=Ischigualasto+Fósil+1"
            ],
            duracionEstimada: "Día completo (aprox. 8-10 horas con traslados)",
            queIncluye: ["Traslado desde San Juan Capital (opcional con costo extra)", "Entrada al Parque Provincial", "Guía oficial del parque dentro del circuito", "Visita al Centro de Interpretación (Museo)"],
            queNoIncluye: ["Almuerzo y bebidas", "Propinas"],
            recomendaciones: ["Llevar sombrero o gorra", "Protector solar", "Agua abundante", "Calzado cómodo para caminar"],
             operadorVerificado: {
                nombre: "Aventuras Triásicas",
                whatsapp: "5492647654321",
                email: "contacto@aventurastriasicas.tur.ar"
            }
        },
        // Añade exp103 a exp106 aquí con detalles similares
        "exp103": {
            nombre: "Santuario Difunta Correa: Fe y Tradición",
            ubicacionPrincipal: "Vallecito, Caucete",
            departamento: "Caucete",
            tipo: ["Cultural", "Religioso"],
            precioPorPersona: 8000, // Estimado si es un tour guiado
            descripcionCorta: "Conoce uno de los santuarios populares más importantes de Argentina.",
            descripcionLarga: "Visita el paraje de Vallecito, epicentro de la devoción popular a Deolinda Correa. Recorre las capillas llenas de ofrendas, conoce la historia y las leyendas que rodean a esta figura mítica. Un viaje al corazón de la fe y las tradiciones sanjuaninas.",
            imagenes: ["img/experiencies/difuntacorrea.jpg"],
            duracionEstimada: "Medio día",
            queIncluye: ["Guía cultural", "Tiempo libre para recorrer el santuario"],
            recomendaciones: ["Respeto por el lugar y los devotos", "Cámara de fotos"],
            operadorVerificado: { nombre: "Tradiciones Sanjuaninas Tours" }
        },
        "exp104": {
            nombre: "Adrenalina en Cuesta del Viento",
            ubicacionPrincipal: "Dique Cuesta del Viento, Rodeo, Iglesia",
            departamento: "Iglesia",
            tipo: ["Aventura", "Deportiva"],
            precioPorPersona: 25000, // Estimado para clase + alquiler equipo
            descripcionCorta: "Desafía el famoso viento en uno de los mejores spots del mundo.",
            descripcionLarga: "Siente la emoción del windsurf o kitesurf en el Dique Cuesta del Viento, conocido internacionalmente por sus condiciones ideales. Clases para principiantes y alquiler de equipo para experimentados. Paisajes imponentes y pura adrenalina.",
            imagenes: ["img/experiencies/cuestadelviento.JPG"],
            duracionEstimada: "Variable (clases de 2-3hs, alquiler por hora/día)",
            queIncluye: ["Instructor (si es clase)", "Equipo completo (tabla, vela/kite, traje)"],
            recomendaciones: ["Saber nadar (básico)", "Protector solar resistente al agua", "Traje de baño"],
            operadorVerificado: { nombre: "Viento Extremo San Juan" }
        },
        "exp105": {
            nombre: "Carrovelismo en la Pampa El Leoncito",
            ubicacionPrincipal: "Barreal Blanco (Pampa El Leoncito), Calingasta",
            departamento: "Calingasta",
            tipo: ["Aventura", "Deportiva"],
            precioPorPersona: 18000,
            descripcionCorta: "Siente la velocidad impulsado por el viento en un paisaje imponente.",
            descripcionLarga: "Experimenta la sensación única de deslizarte sobre la vasta planicie de la Pampa El Leoncito en un carro a vela. Una actividad emocionante y accesible para todas las edades, rodeado por la majestuosidad de la Cordillera de los Andes.",
            imagenes: ["img/experiencies/carrovelismo.jpg"],
            duracionEstimada: "1-2 horas (incluye instrucción)",
            queIncluye: ["Instrucción básica", "Alquiler de carrovelas", "Casco y elementos de seguridad"],
            recomendaciones: ["Ropa cómoda", "Gafas de sol", "Protector solar"],
            operadorVerificado: { nombre: "Barreal Carrovelas Aventura" }
        },
        "exp106": {
            nombre: "Relax y Aventura en los Diques",
            ubicacionPrincipal: "Diques de Ullum, Punta Negra, Cuesta del Viento",
            departamento: "Ullum,Zonda,Rivadavia,Iglesia", // Múltiples deptos
            tipo: ["Naturaleza", "Relax", "Deportiva"],
            precioPorPersona: 0, // Puede ser libre o actividades con costo
            descripcionCorta: "Disfruta de deportes acuáticos, paisajes y esparcimiento.",
            descripcionLarga: "Los embalses de San Juan son oasis perfectos para el esparcimiento. Desde actividades náuticas como kayak, paddle surf, hasta simplemente relajarse en sus orillas y disfrutar del paisaje. Cada dique ofrece una experiencia particular.",
            imagenes: ["img/experiencies/dique2.jpg"],
            duracionEstimada: "Libre / Variable según actividad",
            queIncluye: ["Acceso a zonas públicas (generalmente)", "Alquiler de equipos (kayak, botes, etc. con costo)"],
            recomendaciones: ["Consultar actividades y servicios en cada dique", "Traje de baño", "Protector solar"],
            // Podría no tener un operador único, sino varios por dique o ser de acceso libre
            operadorVerificado: null 
        }
    };

    // (El resto del JS para cargar los detalles en la página detalle-experiencia.html sería muy similar al de detalle-alquiler.js,
    //  pero accediendo a los campos de 'experienciasDB' y actualizando los IDs de los elementos HTML correspondientes.
    //  Necesitarás crear una página detalle-experiencia.html con los IDs correctos para mostrar esta información)
    function cargarDetallesExperiencia(expId) {
        const experiencia = experienciasDB[expId];

        if (!experiencia) {
            // Manejar experiencia no encontrada
            document.getElementById('experience-details-container').innerHTML = '<h1 class="text-danger">Experiencia no encontrada</h1><p>La experiencia que buscas no existe o ha sido eliminada. <a href="experiencias-listado.html">Volver al listado</a></p>';
            document.title = "Experiencia no encontrada - Turismo Seguro San Juan";
            if(document.getElementById('experience-sidebar')) document.getElementById('experience-sidebar').style.display = 'none';
            return;
        }

        document.title = `${experiencia.nombre} - Turismo Seguro San Juan`;

        // Cargar título, ubicación, etc. Similar a detalle-alquiler.js
        // Ejemplo:
        document.getElementById('experienceTitle').textContent = experiencia.nombre;
        document.getElementById('experienceLocation').textContent = experiencia.ubicacionPrincipal;
        
        // Galería de Imágenes (adaptar IDs si son diferentes)
        const carouselInner = document.getElementById('experienceImageCarouselInner'); // Asume un ID específico
        const carouselIndicators = document.getElementById('experienceImageCarouselIndicators'); // Asume un ID específico
        if (carouselInner && carouselIndicators) {
            carouselInner.innerHTML = '';
            carouselIndicators.innerHTML = '';
            experiencia.imagenes.forEach((imgUrl, index) => {
                const activeClass = index === 0 ? 'active' : '';
                carouselInner.innerHTML += `
                    <div class="carousel-item ${activeClass}">
                        <img src="${imgUrl}" class="d-block w-100" alt="Imagen ${index + 1} de ${experiencia.nombre}">
                    </div>`;
                carouselIndicators.innerHTML += `
                    <button type="button" data-bs-target="#experienceImageCarousel" data-bs-slide-to="${index}" class="${activeClass}" aria-current="${activeClass ? 'true' : 'false'}" aria-label="Slide ${index + 1}"></button>`;
            });
        }


        document.getElementById('experienceDescription').innerHTML = experiencia.descripcionLarga.replace(/\n/g, '<br>');
        document.getElementById('experienceDuration').textContent = experiencia.duracionEstimada;
        
        const includesList = document.getElementById('experienceIncludes');
        if (includesList && experiencia.queIncluye) {
            includesList.innerHTML = '';
            experiencia.queIncluye.forEach(item => {
                includesList.innerHTML += `<li><i class="fas fa-check text-success me-2"></i>${item}</li>`;
            });
        }
        
        const notIncludesList = document.getElementById('experienceNotIncludes');
         if (notIncludesList && experiencia.queNoIncluye && experiencia.queNoIncluye.length > 0) {
            notIncludesList.innerHTML = '';
            experiencia.queNoIncluye.forEach(item => {
                notIncludesList.innerHTML += `<li><i class="fas fa-times text-danger me-2"></i>${item}</li>`;
            });
            document.getElementById('notIncludesSection').style.display = 'block';
        } else if (notIncludesList) {
            document.getElementById('notIncludesSection').style.display = 'none';
        }


        const recommendationsList = document.getElementById('experienceRecommendations');
        if (recommendationsList && experiencia.recomendaciones && experiencia.recomendaciones.length > 0) {
            recommendationsList.innerHTML = '';
            experiencia.recomendaciones.forEach(item => {
                recommendationsList.innerHTML += `<li><i class="fas fa-info-circle text-info me-2"></i>${item}</li>`;
            });
             document.getElementById('recommendationsSection').style.display = 'block';
        } else if (recommendationsList) {
            document.getElementById('recommendationsSection').style.display = 'none';
        }


        // Sidebar
        const priceElement = document.getElementById('experiencePrice');
        if (priceElement) {
            if (experiencia.precioPorPersona > 0) {
                priceElement.innerHTML = `$${experiencia.precioPorPersona.toLocaleString('es-AR')} <span class="fs-6 text-muted">/persona</span>`;
            } else {
                priceElement.innerHTML = `Consultar Precio <span class="fs-6 text-muted">/o libre</span>`;
            }
        }
        
        // Operador Verificado
        const operatorNameEl = document.getElementById('verifiedOperatorName');
        const operatorWhatsappEl = document.getElementById('verifiedOperatorWhatsapp');
        const operatorEmailEl = document.getElementById('verifiedOperatorEmail');
        const operatorSectionEl = document.getElementById('verifiedOperatorSection');

        if (experiencia.operadorVerificado && experiencia.operadorVerificado.nombre && operatorSectionEl) {
            operatorNameEl.textContent = experiencia.operadorVerificado.nombre;
            if (experiencia.operadorVerificado.whatsapp && operatorWhatsappEl) {
                operatorWhatsappEl.href = `https://wa.me/${experiencia.operadorVerificado.whatsapp.replace(/\D/g,'')}?text=${encodeURIComponent(`Hola ${experiencia.operadorVerificado.nombre}, te contacto desde Turismo Seguro por la experiencia: ${experiencia.nombre}`)}`;
                operatorWhatsappEl.style.display = 'inline-block';
            } else if (operatorWhatsappEl) {
                 operatorWhatsappEl.style.display = 'none';
            }
            if (experiencia.operadorVerificado.email && operatorEmailEl) {
                operatorEmailEl.href = `mailto:${experiencia.operadorVerificado.email}?subject=Consulta%20Experiencia:%20${encodeURIComponent(experiencia.nombre)}%20(via%20Turismo%20Seguro)`;
                operatorEmailEl.style.display = 'inline-block';
            } else if (operatorEmailEl) {
                operatorEmailEl.style.display = 'none';
            }
             operatorSectionEl.style.display = 'block';
        } else if (operatorSectionEl) {
            operatorSectionEl.style.display = 'none';
        }


    }

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const experienciaIdFromUrl = getQueryParam('id');
    if (experienciaIdFromUrl) {
        cargarDetallesExperiencia(experienciaIdFromUrl);
    } else {
        if(document.getElementById('experience-details-container')) {
            document.getElementById('experience-details-container').innerHTML = '<h1 class="text-danger">Error</h1><p>No se especificó una experiencia para mostrar. <a href="experiencias-listado.html">Volver al listado</a></p>';
        }
        if(document.getElementById('experience-sidebar')) {
            document.getElementById('experience-sidebar').style.display = 'none';
        }
        document.title = "Error - Turismo Seguro San Juan";
    }
    
    const currentYearFooter = document.getElementById('currentYear');
    if (currentYearFooter) {
        currentYearFooter.textContent = new Date().getFullYear();
    }
});