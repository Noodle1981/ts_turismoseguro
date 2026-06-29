document.addEventListener('DOMContentLoaded', function () {
    const experienciasGrid = document.getElementById('experienciasGrid');
    const todasLasExperienciasItems = Array.from(experienciasGrid.querySelectorAll('.experiencia-item'));
    
    const filterDepartamento = document.getElementById('filterExpDepartamento');
    const filterTipo = document.getElementById('filterExpTipo');
    const filterDuracion = document.getElementById('filterExpDuracion');
    const filterPrecioMax = document.getElementById('filterExpPrecioMax');
    // Checkboxes de "Incluye"
    const checkGuia = document.getElementById('checkGuia');
    const checkTraslado = document.getElementById('checkTraslado');
    const checkEquipamiento = document.getElementById('checkEquipamiento');

    const applyFiltersBtn = document.getElementById('applyExpFiltersBtn');
    const resetFiltersBtn = document.getElementById('resetExpFiltersBtn');
    const noResultsMessage = document.getElementById('noExpResultsMessage');
    const resultsTitle = document.getElementById('expResultsTitle');
    const currentYearFooter = document.getElementById('currentYearFooter'); // Asumiendo que tienes un ID único en el footer

    if (currentYearFooter) {
        currentYearFooter.textContent = new Date().getFullYear();
    }

    function aplicarFiltrosExperiencias() {
        const deptoSeleccionado = filterDepartamento.value;
        const tipoSeleccionado = filterTipo.value;
        const duracionSeleccionada = filterDuracion.value;
        const precioMaxSeleccionado = parseInt(filterPrecioMax.value) || Infinity;

        const incluyeSeleccionados = [];
        if (checkGuia.checked) incluyeSeleccionados.push(checkGuia.value);
        if (checkTraslado.checked) incluyeSeleccionados.push(checkTraslado.value);
        if (checkEquipamiento.checked) incluyeSeleccionados.push(checkEquipamiento.value);

        let resultadosVisibles = 0;
        
        todasLasExperienciasItems.forEach(item => {
            const deptoItem = item.dataset.departamento || "";
            const tipoItemArray = item.dataset.tipo ? item.dataset.tipo.split(',') : []; // Puede tener múltiples tipos
            const duracionItem = item.dataset.duracion || "";
            const precioItem = parseInt(item.dataset.precio) || 0;
            const incluyeItemArray = item.dataset.incluye ? item.dataset.incluye.split(',') : [];

            let cumpleDepto = !deptoSeleccionado || deptoItem === deptoSeleccionado;
            let cumpleTipo = !tipoSeleccionado || tipoItemArray.includes(tipoSeleccionado);
            let cumpleDuracion = !duracionSeleccionada || duracionItem === duracionSeleccionada;
            let cumplePrecio = precioItem <= precioMaxSeleccionado;
            
            let cumpleIncluye = true;
            if (incluyeSeleccionados.length > 0) {
                cumpleIncluye = incluyeSeleccionados.every(sel => incluyeItemArray.includes(sel));
            }

            if (cumpleDepto && cumpleTipo && cumpleDuracion && cumplePrecio && cumpleIncluye) {
                item.style.display = '';
                resultadosVisibles++;
            } else {
                item.style.display = 'none';
            }
        });

        noResultsMessage.style.display = resultadosVisibles === 0 ? 'block' : 'none';
        
        if (deptoSeleccionado) {
            resultsTitle.textContent = `Experiencias en ${deptoSeleccionado}`;
        } else if (tipoSeleccionado) {
             resultsTitle.textContent = `Experiencias de tipo ${tipoSeleccionado}`;
        }
        else {
            resultsTitle.textContent = 'Todas las Experiencias';
        }
    }

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', aplicarFiltrosExperiencias);
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            filterDepartamento.value = '';
            filterTipo.value = '';
            filterDuracion.value = '';
            filterPrecioMax.value = '';
            checkGuia.checked = false;
            checkTraslado.checked = false;
            checkEquipamiento.checked = false;
            aplicarFiltrosExperiencias();
            resultsTitle.textContent = 'Todas las Experiencias';
        });
    }

    // Pre-filtro (ej. si vienes de un enlace específico)
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const tipoDesdeUrl = getQueryParam('tipo');
    if (tipoDesdeUrl && filterTipo) {
        filterTipo.value = tipoDesdeUrl;
        aplicarFiltrosExperiencias();
    } else {
        // Si no hay pre-filtro, aplicar una vez para que se muestren los items por defecto
        // o para ocultar/mostrar el mensaje de no resultados si la lista inicial está vacía.
        if (todasLasExperienciasItems.length > 0) { // Solo si hay items para mostrar/ocultar
             aplicarFiltrosExperiencias();
        } else {
            noResultsMessage.style.display = 'block'; // Si no hay items, mostrar mensaje
        }
    }
});