document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM
  const sidebar = document.querySelector(".sidebar");
  const menuToggle = document.querySelector(".menu-toggle");
  const form = document.getElementById("servicioForm");
  const backBtn = document.getElementById("backBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const saveBtn = document.getElementById("saveBtn");
  const addImageBtn = document.getElementById("addImageBtn");
  const imagePreviewContainer = document.getElementById(
    "imagePreviewContainer"
  );
  const emptyImageState = document.getElementById("emptyImageState");
  const successAlert = document.getElementById("successAlert");
  const estadoToggle = document.getElementById("estado");
  const estadoText = document.getElementById("estadoText");

  // Estado del servicio
  let servicioData = {
    id: "",
    nombre: "",
    descripcion: "",
    categoria: "",
    precio: 0,
    duracion: "",
    imagenes: [],
    estado: true,
    destacado: false,
    creado: new Date().toISOString().split("T")[0],
    actualizado: new Date().toISOString().split("T")[0],
  };

  // Inicializar eventos
  initEvents();

  // Función para inicializar eventos
  function initEvents() {
    // Toggle sidebar en móviles
    if (menuToggle) {
      menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("active");
      });
    }

    // Botón volver
    backBtn.addEventListener("click", () => {
      window.location.href = "servicios.html";
    });

    // Botón cancelar
    cancelBtn.addEventListener("click", () => {
      if (
        confirm(
          "¿Estás seguro de que quieres cancelar? Se perderán todos los cambios."
        )
      ) {
        window.location.href = "servicios.html";
      }
    });

    // Toggle de estado
    estadoToggle.addEventListener("change", function () {
      servicioData.estado = this.checked;
      estadoText.textContent = this.checked ? "Activo" : "Inactivo";
    });

    // Checkbox destacado
    document
      .getElementById("destacado")
      .addEventListener("change", function () {
        servicioData.destacado = this.checked;
      });

    // Botón añadir imagen
    addImageBtn.addEventListener("click", addImage);

    // Cerrar alerta
    if (document.querySelector(".close-alert")) {
      document.querySelector(".close-alert").addEventListener("click", () => {
        successAlert.classList.add("hidden");
      });
    }

    // Formulario
    form.addEventListener("submit", handleSubmit);

    // Campos del formulario
    document.getElementById("nombre").addEventListener("input", (e) => {
      servicioData.nombre = e.target.value;
      clearError("nombre");
    });

    document.getElementById("descripcion").addEventListener("input", (e) => {
      servicioData.descripcion = e.target.value;
      clearError("descripcion");
    });

    document.getElementById("categoria").addEventListener("change", (e) => {
      servicioData.categoria = e.target.value;
      clearError("categoria");
    });

    document.getElementById("precio").addEventListener("input", (e) => {
      servicioData.precio = Number.parseFloat(e.target.value) || 0;
      clearError("precio");
    });

    document.getElementById("duracion").addEventListener("input", (e) => {
      servicioData.duracion = e.target.value;
      clearError("duracion");
    });
  }

  // Función para manejar el envío del formulario
  function handleSubmit(e) {
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    // Mostrar estado de carga
    setLoading(true);

    // Simular guardado (en un caso real, aquí iría la llamada a la API)
    setTimeout(() => {
      // Generar ID único
      servicioData.id = "serv-" + Date.now();

      console.log("Servicio guardado:", servicioData);

      // Mostrar alerta de éxito
      showSuccessAlert();

      // Resetear formulario después de un tiempo
      setTimeout(() => {
        resetForm();
        setLoading(false);
      }, 1000);
    }, 1500);
  }

  // Validar formulario
  function validateForm() {
    let isValid = true;
    const errors = {};

    // Validar nombre
    if (!servicioData.nombre.trim()) {
      errors.nombre = "El nombre del servicio es obligatorio";
      isValid = false;
    }

    // Validar descripción
    if (!servicioData.descripcion.trim()) {
      errors.descripcion = "La descripción es obligatoria";
      isValid = false;
    }

    // Validar categoría
    if (!servicioData.categoria) {
      errors.categoria = "Debes seleccionar una categoría";
      isValid = false;
    }

    // Validar precio
    if (servicioData.precio <= 0) {
      errors.precio = "El precio debe ser mayor que 0";
      isValid = false;
    }

    // Validar duración
    if (!servicioData.duracion.trim()) {
      errors.duracion = "La duración estimada es obligatoria";
      isValid = false;
    }

    // Mostrar errores
    if (!isValid) {
      Object.keys(errors).forEach((key) => {
        const errorElement = document.getElementById(key + "Error");
        const inputElement = document.getElementById(key);

        if (errorElement && inputElement) {
          errorElement.textContent = errors[key];
          inputElement.classList.add("error");
        }
      });
    }

    return isValid;
  }

  // Limpiar error específico
  function clearError(field) {
    const errorElement = document.getElementById(field + "Error");
    const inputElement = document.getElementById(field);

    if (errorElement && inputElement) {
      errorElement.textContent = "";
      inputElement.classList.remove("error");
    }
  }

  // Añadir imagen
  function addImage() {
    // En un caso real, aquí iría la lógica para subir imágenes
    // Por ahora, simulamos con una imagen de placeholder
    const imageIndex = servicioData.imagenes.length + 1;
    const imageSrc = `/placeholder.svg?height=200&width=200&text=Imagen+${imageIndex}`;

    servicioData.imagenes.push(imageSrc);
    updateImagePreviews();
  }

  // Eliminar imagen
  function removeImage(index) {
    servicioData.imagenes.splice(index, 1);
    updateImagePreviews();
  }

  // Actualizar vista previa de imágenes
  function updateImagePreviews() {
    if (servicioData.imagenes.length > 0) {
      imagePreviewContainer.classList.remove("hidden");
      emptyImageState.classList.add("hidden");

      imagePreviewContainer.innerHTML = servicioData.imagenes
        .map(
          (src, index) => `
                <div class="image-preview">
                    <img src="${src}" alt="Vista previa ${index + 1}">
                    <button type="button" class="image-remove" onclick="removeImage(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `
        )
        .join("");
    } else {
      imagePreviewContainer.classList.add("hidden");
      emptyImageState.classList.remove("hidden");
      imagePreviewContainer.innerHTML = "";
    }
  }

  // Mostrar alerta de éxito
  function showSuccessAlert() {
    successAlert.classList.remove("hidden");

    // Auto ocultar después de 5 segundos
    setTimeout(() => {
      successAlert.classList.add("hidden");
    }, 5000);
  }

  // Resetear formulario
  function resetForm() {
    form.reset();

    servicioData = {
      id: "",
      nombre: "",
      descripcion: "",
      categoria: "",
      precio: 0,
      duracion: "",
      imagenes: [],
      estado: true,
      destacado: false,
      creado: new Date().toISOString().split("T")[0],
      actualizado: new Date().toISOString().split("T")[0],
    };

    // Resetear imágenes
    updateImagePreviews();

    // Resetear errores
    document
      .querySelectorAll(".error-message")
      .forEach((el) => (el.textContent = ""));
    document
      .querySelectorAll(".error")
      .forEach((el) => el.classList.remove("error"));

    // Resetear estado
    estadoText.textContent = "Activo";
  }

  // Cambiar estado de carga
  function setLoading(isLoading) {
    if (isLoading) {
      saveBtn.innerHTML = '<span class="spinner"></span> Guardando...';
      saveBtn.disabled = true;
      cancelBtn.disabled = true;
    } else {
      saveBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Servicio';
      saveBtn.disabled = false;
      cancelBtn.disabled = false;
    }
  }

  // Hacer accesible la función removeImage globalmente para los botones inline
  window.removeImage = removeImage;
});

const iconInput = document.getElementById("iconInput");
const iconPreview = document.getElementById("iconPreview");

iconInput.addEventListener("input", () => {
  iconPreview.innerHTML = `<i class="${iconInput.value}"></i>`;
});
