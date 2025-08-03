document.addEventListener("DOMContentLoaded", () => {
  // Product Gallery
  const mainImage = document.getElementById("mainImage");
  const thumbnails = document.querySelectorAll(".thumbnail");
  const zoomBtn = document.querySelector(".zoom-btn");
  const zoomModal = document.getElementById("imageZoomModal");
  const zoomedImage = document.getElementById("zoomedImage");
  const zoomClose = document.querySelector(".zoom-close");
  const navBtns = document.querySelectorAll(".nav-btn");

  let currentImageIndex = 0;
  const images = Array.from(thumbnails).map((thumb) =>
    thumb.getAttribute("data-image")
  );

  // Thumbnail navigation
  thumbnails.forEach((thumbnail, index) => {
    thumbnail.addEventListener("click", () => {
      setActiveImage(index);
    });
  });

  // Navigation buttons
  navBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      if (btn.classList.contains("prev")) {
        currentImageIndex =
          currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
      } else {
        currentImageIndex =
          currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
      }
      setActiveImage(currentImageIndex);
    });
  });

  function setActiveImage(index) {
    currentImageIndex = index;
    const newImageSrc = images[index];

    // Update main image
    mainImage.src = newImageSrc;

    // Update active thumbnail
    thumbnails.forEach((thumb) => thumb.classList.remove("active"));
    thumbnails[index].classList.add("active");

    // Update navigation buttons
    navBtns[0].disabled = index === 0;
    navBtns[1].disabled = index === images.length - 1;
  }

  // Image zoom
  if (zoomBtn) {
    zoomBtn.addEventListener("click", () => {
      zoomedImage.src = mainImage.src;
      zoomModal.style.display = "block";
      document.body.style.overflow = "hidden";
    });
  }

  // Close zoom modal
  if (zoomClose) {
    zoomClose.addEventListener("click", closeZoomModal);
  }

  zoomModal?.addEventListener("click", (e) => {
    if (e.target === zoomModal) {
      closeZoomModal();
    }
  });

  function closeZoomModal() {
    zoomModal.style.display = "none";
    document.body.style.overflow = "";
  }

  // Product Options
  const colorOptions = document.querySelectorAll('input[name="color"]');
  const selectedColorSpan = document.querySelector(".selected-option");
  const installationOptions = document.querySelectorAll(
    'input[name="installation"]'
  );
  const quantityInput = document.getElementById("quantity");
  const qtyBtns = document.querySelectorAll(".qty-btn");
  const totalPriceElement = document.querySelector(".total-price");

  let currentPrice = basePrice;

  // Color selection
  colorOptions.forEach((option) => {
    option.addEventListener("change", () => {
      const selectedColor =
        option.nextElementSibling.getAttribute("data-color");
      selectedColorSpan.textContent = selectedColor;
      updatePrice();
    });
  });

  // Installation selection
  installationOptions.forEach((option) => {
    option.addEventListener("change", () => {
      updatePrice();
    });
  });

  // Quantity controls
  qtyBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const isPlus = btn.classList.contains("plus");
      const currentValue = Number.parseInt(quantityInput.value);
      const min = Number.parseInt(quantityInput.getAttribute("min"));
      const max = Number.parseInt(quantityInput.getAttribute("max"));

      if (isPlus && currentValue < max) {
        quantityInput.value = currentValue + 1;
      } else if (!isPlus && currentValue > min) {
        quantityInput.value = currentValue - 1;
      }

      updatePrice();
    });
  });

  quantityInput?.addEventListener("change", () => {
    const value = Number.parseInt(quantityInput.value);
    const min = Number.parseInt(quantityInput.getAttribute("min"));
    const max = Number.parseInt(quantityInput.getAttribute("max"));

    if (value < min) quantityInput.value = min;
    if (value > max) quantityInput.value = max;

    updatePrice();
  });

  function updatePrice() {
    const quantity = Number.parseInt(quantityInput?.value || 1);
    const installationSelected = document.querySelector(
      'input[name="installation"]:checked'
    );

    let total = basePrice * quantity;

    if (installationSelected?.value === "professional") {
      total += 50;
    }

    currentPrice = total;
    if (totalPriceElement) {
      totalPriceElement.textContent = `€${total.toFixed(2)}`;
    }
  }

  // Add to Cart
  const addToCartBtn = document.querySelector(".add-to-cart");
  const buyNowBtn = document.querySelector(".buy-now");
  const cartCount = document.querySelector(".cart-count");

  buyNowBtn?.addEventListener("click", () => {
    showNotification("Redirigiendo al checkout...", "info");
    // Simulate redirect
    setTimeout(() => {
      window.location.href = "#checkout";
    }, 1000);
  });

  // Secondary actions
  const wishlistBtn = document.querySelector(".wishlist-btn");
  const compareBtn = document.querySelector(".compare-btn");
  const shareBtn = document.querySelector(".share-btn");

  wishlistBtn?.addEventListener("click", function () {
    const icon = this.querySelector("i");
    const isActive = icon.classList.contains("fas");

    if (isActive) {
      icon.classList.remove("fas");
      icon.classList.add("far");
      this.style.color = "";
      showNotification("Eliminado de favoritos", "info");
    } else {
      icon.classList.remove("far");
      icon.classList.add("fas");
      this.style.color = "#f44336";
      showNotification("Añadido a favoritos", "success");
    }
  });

  compareBtn?.addEventListener("click", () => {
    showNotification("Producto añadido para comparar", "info");
  });

  shareBtn?.addEventListener("click", () => {
    if (navigator.share) {
      navigator.share({
        title: document.title,
        url: window.location.href,
      });
    } else {
      // Fallback: copy to clipboard
      navigator.clipboard.writeText(window.location.href);
      showNotification("Enlace copiado al portapapeles", "success");
    }
  });

  // Product Tabs
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabPanels = document.querySelectorAll(".tab-panel");

  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const targetTab = button.getAttribute("data-tab");

      // Remove active class from all buttons and panels
      tabButtons.forEach((btn) => {
        btn.classList.remove("active");
        btn.setAttribute("aria-selected", "false");
      });
      tabPanels.forEach((panel) => panel.classList.remove("active"));

      // Add active class to clicked button and corresponding panel
      button.classList.add("active");
      button.setAttribute("aria-selected", "true");
      document.getElementById(targetTab)?.classList.add("active");
    });
  });

  // Reviews
  const filterBtns = document.querySelectorAll(".filter-btn");
  const helpfulBtns = document.querySelectorAll(".helpful-btn");
  const loadMoreBtn = document.querySelector(".load-more-btn");

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterBtns.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      const filter = btn.getAttribute("data-filter");
      // Here you would implement the actual filtering logic
      console.log("Filtering reviews by:", filter);
    });
  });

  helpfulBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      if (this.disabled) return;

      const countMatch = this.textContent.match(/$$(\d+)$$/);
      const currentCount = countMatch ? Number.parseInt(countMatch[1]) : 0;
      const newCount = currentCount + 1;

      this.innerHTML = `<i class="fas fa-thumbs-up"></i> Útil (${newCount})`;
      this.style.color = "var(--primary-blue)";
      this.disabled = true;

      showNotification("Gracias por tu valoración", "success");
    });
  });

  loadMoreBtn?.addEventListener("click", () => {
    showNotification("Cargando más reseñas...", "info");
    // Simulate loading more reviews
  });

  // Related Products
  const quickAddBtns = document.querySelectorAll(".quick-add");

  // Mobile menu
  const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
  const mainNav = document.querySelector(".main-nav");

  mobileMenuBtn?.addEventListener("click", () => {
    mainNav?.classList.toggle("active");
  });

  // Notification system
  function showNotification(message, type = "info") {
    // Remove existing notifications
    const existingNotification = document.querySelector(".notification");
    if (existingNotification) {
      existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Add styles
    Object.assign(notification.style, {
      position: "fixed",
      top: "20px",
      right: "20px",
      padding: "15px 20px",
      borderRadius: "8px",
      color: "white",
      fontWeight: "600",
      zIndex: "9999",
      transform: "translateX(100%)",
      transition: "transform 0.3s ease",
      maxWidth: "300px",
    });

    // Set background color based on type
    const colors = {
      success: "#4caf50",
      error: "#f44336",
      warning: "#ff9800",
      info: "#2196f3",
    };
    notification.style.backgroundColor = colors[type] || colors.info;

    // Add to DOM
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
      notification.style.transform = "translateX(0)";
    }, 100);

    // Remove after delay
    setTimeout(() => {
      notification.style.transform = "translateX(100%)";
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 3000);
  }

  // Keyboard navigation
  document.addEventListener("keydown", (e) => {
    // ESC to close modals
    if (e.key === "Escape") {
      if (zoomModal?.style.display === "block") {
        closeZoomModal();
      }
    }

    // Arrow keys for image navigation
    if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
      const galleryFocused =
        document.activeElement?.closest(".product-gallery");
      if (galleryFocused) {
        e.preventDefault();
        if (e.key === "ArrowLeft") {
          currentImageIndex =
            currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
        } else {
          currentImageIndex =
            currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
        }
        setActiveImage(currentImageIndex);
      }
    }
  });

  // Initialize
  updatePrice();
  setActiveImage(0);

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Intersection Observer for animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe elements for animation
  document
    .querySelectorAll(".product-card, .trust-item, .step")
    .forEach((el) => {
      el.style.opacity = "0";
      el.style.transform = "translateY(20px)";
      el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
      observer.observe(el);
    });
});

document
  .querySelector(".share-btn")
  .addEventListener("click", async function () {
    const url = window.location.href;
    if (navigator.share) {
      try {
        await navigator.share({
          title: document.title,
          text: "¡Mira este inventario!",
          url: url,
        });
      } catch (error) {
        alert("Error al compartir.");
      }
    } else {
      // Fallback: copiar al portapapeles
      try {
        await navigator.clipboard.writeText(url);
        alert("Enlace copiado al portapapeles.");
      } catch (error) {
        alert("No se pudo copiar el enlace.");
      }
    }
  });
