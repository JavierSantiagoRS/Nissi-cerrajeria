document.addEventListener("DOMContentLoaded", function () {
  // Menú móvil
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
  const mainNav = document.querySelector(".main-nav");

  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener("click", function () {
      mainNav.classList.toggle("active");

      // Cambiar el ícono
      const icon = this.querySelector("i");
      if (icon.classList.contains("fa-bars")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // Navegación activa
  const sections = document.querySelectorAll("section[id]");
  const navLinks = document.querySelectorAll(".main-nav a");

  function highlightNavLink() {
    const scrollY = window.pageYOffset;

    sections.forEach((section) => {
      const sectionHeight = section.offsetHeight;
      const sectionTop = section.offsetTop - 100;
      const sectionId = section.getAttribute("id");

      if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === `#${sectionId}`) {
            link.classList.add("active");
          }
        });
      }
    });
  }

  window.addEventListener("scroll", highlightNavLink);

  // Slider de testimonios
  const testimonialSlides = document.querySelectorAll(".testimonial-slide");
  const dots = document.querySelectorAll(".dot");
  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");
  let currentSlide = 0;

  function showSlide(n) {
    testimonialSlides.forEach((slide) => slide.classList.remove("active"));
    dots.forEach((dot) => dot.classList.remove("active"));

    currentSlide = (n + testimonialSlides.length) % testimonialSlides.length;

    testimonialSlides[currentSlide].classList.add("active");
    dots[currentSlide].classList.add("active");
  }

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", () => showSlide(currentSlide - 1));
    nextBtn.addEventListener("click", () => showSlide(currentSlide + 1));
  }

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => showSlide(index));
  });

  // Cambiar slide automáticamente cada 5 segundos
  setInterval(() => {
    showSlide(currentSlide + 1);
  }, 5000);

  // Animación de elementos al hacer scroll
  const animateOnScroll = function () {
    const elements = document.querySelectorAll(
      ".service-card, .feature, .stat, .gallery-item, .contact-card"
    );

    elements.forEach((element) => {
      const elementPosition = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;

      if (elementPosition < windowHeight - 100) {
        element.style.opacity = "1";
        element.style.transform = "translateY(0)";
      }
    });
  };

  // Inicializar elementos con opacidad 0
  document
    .querySelectorAll(
      ".service-card, .feature, .stat, .gallery-item, .contact-card"
    )
    .forEach((element) => {
      element.style.opacity = "0";
      element.style.transform = "translateY(20px)";
      element.style.transition = "opacity 0.5s ease, transform 0.5s ease";
    });

  window.addEventListener("scroll", animateOnScroll);

  // Ejecutar una vez al cargar la página
  animateOnScroll();

  // Añadir estilos al menú móvil
  if (window.innerWidth <= 768) {
    const styleElement = document.createElement("style");
    styleElement.textContent = `
            .main-nav {
                position: fixed;
                top: 90px;
                left: 0;
                width: 100%;
                background-color: var(--white);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                padding: 20px;
                display: none;
                z-index: 999;
            }
            
            .main-nav.active {
                display: block;
            }
            
            .main-nav ul {
                flex-direction: column;
            }
            
            .main-nav li {
                margin: 10px 0;
            }
        `;
    document.head.appendChild(styleElement);
  }
});
