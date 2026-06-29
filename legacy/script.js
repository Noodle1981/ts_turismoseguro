document.addEventListener('DOMContentLoaded', function() {

    // Navbar activa al hacer scroll
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const sections = document.querySelectorAll('section, header'); // Incluimos header para el "Inicio"

    function changeLinkState() {
        let index = sections.length;

        while(--index && window.scrollY + 100 < sections[index].offsetTop) {} // +100 para un offset
        
        navLinks.forEach((link) => link.classList.remove('active'));
        // Asegurarse de que el link exista antes de agregar la clase active
        if (navLinks[index]) {
            navLinks[index].classList.add('active');
        }
    }

    // Activar al cargar y al hacer scroll
    if (sections.length > 0 && navLinks.length > 0) {
        changeLinkState(); // Llamar al cargar la página
        window.addEventListener('scroll', changeLinkState);
    }


    // Smooth scroll para anclas (opcional si ya usas scroll-behavior: smooth en CSS, pero más robusto)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Solo prevenir si el href es solo un # o si es un link de nav interno
            if (this.getAttribute('href') === '#' || this.closest('.navbar-nav')) {
                 e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    // Ajuste por la altura del navbar fijo
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
                
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });

                    // Cerrar el menú hamburguesa en móviles si está abierto y es un link del nav
                    const navbarToggler = document.querySelector('.navbar-toggler');
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarToggler && navbarCollapse.classList.contains('show')) {
                        if (!navbarToggler.classList.contains('collapsed')) {
                             navbarToggler.click(); // Simula un clic para cerrar
                        }
                    }
                }
            }
        });
    });

    // Actualizar año en el footer
    const currentYearSpan = document.getElementById('currentYear');
    if (currentYearSpan) {
        currentYearSpan.textContent = new Date().getFullYear();
    }

    // Manejo del formulario de contacto (simulado)
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    if (contactForm && formMessage) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            formMessage.textContent = 'Enviando mensaje...';
            formMessage.className = 'mt-3'; // Reset class

            // Simulación de envío
            setTimeout(() => {
                // Aquí iría la lógica real de envío (fetch a un backend, etc.)
                const isSuccess = Math.random() > 0.2; // Simula éxito o error

                if (isSuccess) {
                    formMessage.textContent = '¡Mensaje enviado con éxito! Nos pondremos en contacto pronto.';
                    formMessage.classList.add('success');
                    contactForm.reset();
                } else {
                    formMessage.textContent = 'Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.';
                    formMessage.classList.add('error');
                }
            }, 1500);
        });
    }

    // Pequeño efecto al hacer scroll sobre elementos (opcional)
    // Podrías usar Intersection Observer para animaciones más complejas al entrar en viewport
    const animatedElements = document.querySelectorAll('.feature-card, .listing-card, .process-step');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-on-scroll');
            }
        });
    }, { threshold: 0.1 }); // Se activa cuando el 10% del elemento es visible

    // Para que esto funcione, necesitas una clase CSS .animate-on-scroll
    // Por ejemplo:
    // .animate-on-scroll { animation: fadeInUp 0.8s ease-out; }
    // @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    // Lo he omitido para no alargar más el CSS, pero es una buena adición "innovadora".
    // animatedElements.forEach(el => observer.observe(el));

});