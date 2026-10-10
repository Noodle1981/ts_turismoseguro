document.addEventListener('DOMContentLoaded', function () {
    const alquileresGrid = document.getElementById('alquileresGrid');
    // Guardamos una copia de todos los items originales para poder resetear
    const todosLosAlquileresItems = Array.from(alquileresGrid.querySelectorAll('.alquiler-item'));
    
    const filterDepartamento = document.getElementById('filterDepartamento');
    const filterTipo = document.getElementById('filterTipo');
    const filterCapacidad = document.getElementById('filterCapacidad');
    const filterPrecioMax = document.getElementById('filterPrecioMax');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const resultsTitle = document.getElementById('resultsTitle');
    const currentYearFooter = document.getElementById('currentYearFooter');

    if (currentYearFooter) {
        currentYearFooter.textContent = new Date().getFullYear();
    }

    // --- Lógica de filtrado ---
    function aplicarFiltros() {
        const deptoSeleccionado = filterDepartamento.value;
        const tipoSeleccionado = filterTipo.value;
        const capacidadSeleccionada = parseInt(filterCapacidad.value) || 0;
        const precioMaxSeleccionado = parseInt(filterPrecioMax.value) || Infinity;
        
        const comodidadesSeleccionadas = [];
        document.querySelectorAll('#filters-sidebar .form-check-input:checked').forEach(checkbox => {
            comodidadesSeleccionadas.push(checkbox.value);
        });

        let resultadosVisibles = 0;
        
        todosLosAlquileresItems.forEach(item => {
            const deptoItem = item.dataset.departamento;
            const tipoItem = item.dataset.tipo;
            const capacidadItem = parseInt(item.dataset.capacidad);
            const precioItem = parseInt(item.dataset.precio);
            const comodidadesItem = item.dataset.comodidades ? item.dataset.comodidades.split(',') : [];

            let cumpleDepto = !deptoSeleccionado || deptoItem === deptoSeleccionado;
            let cumpleTipo = !tipoSeleccionado || tipoItem === tipoSeleccionado;
            let cumpleCapacidad = capacidadSeleccionada === 0 || capacidadItem >= capacidadSeleccionada;
            let cumplePrecio = precioItem <= precioMaxSeleccionado;
            
            let cumpleComodidades = true;
            if (comodidadesSeleccionadas.length > 0) {
                cumpleComodidades = comodidadesSeleccionadas.every(com => comodidadesItem.includes(com));
            }

            if (cumpleDepto && cumpleTipo && cumpleCapacidad && cumplePrecio && cumpleComodidades) {
                item.style.display = ''; // Mostrar item
                resultadosVisibles++;
            } else {
                item.style.display = 'none'; // Ocultar item
            }
        });

        // Mostrar u ocultar mensaje de "no resultados"
        if (resultadosVisibles === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
        
        // Actualizar título (opcional)
        if (deptoSeleccionado) {
            resultsTitle.textContent = `Alquileres en ${deptoSeleccionado}`;
        } else {
            resultsTitle.textContent = 'Todos los Alquileres';
        }

        // Aquí iría la lógica de paginación después de filtrar
        // Por ahora, solo mostramos/ocultamos
    }

    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', aplicarFiltros);
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function() {
            filterDepartamento.value = '';
            filterTipo.value = '';
            filterCapacidad.value = '';
            filterPrecioMax.value = '';
            document.querySelectorAll('#filters-sidebar .form-check-input:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            aplicarFiltros(); // Re-aplicar para mostrar todos
            resultsTitle.textContent = 'Todos los Alquileres';
        });
    }
    
    // --- Lógica para pre-filtrar por departamento desde la URL (ej: alquileres.html?departamento=Calingasta) ---
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const deptoDesdeUrl = getQueryParam('departamento');
    if (deptoDesdeUrl && filterDepartamento) {
        filterDepartamento.value = deptoDesdeUrl;
        aplicarFiltros(); // Aplicar filtros automáticamente si viene un depto en la URL
    }

    // Simulación inicial o si no hay filtros pre-seleccionados
    // aplicarFiltros(); // Puedes descomentar esto si quieres que se filtren al cargar, aunque con el pre-filtro de URL ya se hace.
});