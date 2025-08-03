document.addEventListener("DOMContentLoaded", () => {
  // Toggle Sidebar
  const toggleMenu = document.querySelector(".toggle-menu");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");

  if (toggleMenu) {
    toggleMenu.addEventListener("click", () => {
      sidebar.classList.toggle("sidebar-collapsed");
      mainContent.classList.toggle("expanded");
    });
  }

  // Cambiar vista de clientes (tabla/cuadrícula)
  const viewBtns = document.querySelectorAll(".view-btn");
  const clientsViews = document.querySelectorAll(".clients-view");

  viewBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const view = this.getAttribute("data-view");

      // Cambiar botones activos
      viewBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      // Cambiar vista activa
      clientsViews.forEach((v) => v.classList.remove("active"));
      document.querySelector(`.${view}-view`).classList.add("active");
    });
  });

  // Búsqueda de clientes
  const clientSearch = document.getElementById("clientSearch");

  if (clientSearch) {
    clientSearch.addEventListener("keyup", function () {
      // Aquí iría la lógica de búsqueda
      console.log("Buscando: " + this.value);
    });
  }

  // Filtros de tipo y estado
  const typeFilter = document.getElementById("typeFilter");
  const statusFilter = document.getElementById("statusFilter");
  const sortFilter = document.getElementById("sortFilter");

  // Aplicar filtros cuando cambian
  [typeFilter, statusFilter, sortFilter].forEach((filter) => {
    if (filter) {
      filter.addEventListener("change", () => {
        applyFilters();
      });
    }
  });

  function applyFilters() {
    // Aquí iría la lógica para aplicar todos los filtros
    console.log("Aplicando filtros:");
    console.log("Tipo:", typeFilter.value);
    console.log("Estado:", statusFilter.value);
    console.log("Ordenar por:", sortFilter.value);
  }

  // Checkboxes de clientes
  const selectAll = document.getElementById("selectAll");
  const clientCheckboxes = document.querySelectorAll(".client-checkbox");

  if (selectAll) {
    selectAll.addEventListener("change", function () {
      clientCheckboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
    });
  }

  clientCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", () => {
      // Verificar si todos están seleccionados para actualizar selectAll
      if (selectAll) {
        const allChecked = [...clientCheckboxes].every((c) => c.checked);
        selectAll.checked = allChecked;
      }
    });
  });

  // Modal para añadir/editar cliente
  const btnAddClient = document.getElementById("btnAddClient");
  const clientModal = document.getElementById("clientModal");
  const modalOverlay = document.querySelector(".modal-overlay");
  const modalCloseButtons = document.querySelectorAll(
    ".modal-close, .modal-cancel"
  );

  if (btnAddClient) {
    btnAddClient.addEventListener("click", () => {
      openModal(clientModal);
    });
  }

  // Cerrar modales
  modalCloseButtons.forEach((button) => {
    button.addEventListener("click", () => {
      closeAllModals();
    });
  });

  // Cerrar modal al hacer clic en el overlay
  if (modalOverlay) {
    modalOverlay.addEventListener("click", () => {
      closeAllModals();
    });
  }

  // Funciones para abrir y cerrar modales
  function openModal(modal) {
    if (modal && modalOverlay) {
      modal.style.display = "block";
      modalOverlay.style.display = "block";
      // Evitar que el scroll del body funcione
      document.body.style.overflow = "hidden";
    }
  }

  function closeAllModals() {
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
      modal.style.display = "none";
    });

    if (modalOverlay) {
      modalOverlay.style.display = "none";
    }

    document.body.style.overflow = "";
  }

  // Tabs del formulario de cliente
  const formTabBtns = document.querySelectorAll(".form-tabs .tab-btn");
  const formTabPanes = document.querySelectorAll(".tab-pane");

  formTabBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const tab = this.getAttribute("data-tab");

      // Cambiar botones activos
      formTabBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      // Cambiar pestaña activa
      formTabPanes.forEach((p) => p.classList.remove("active"));
      document.getElementById(tab).classList.add("active");
    });
  });

  // Vista previa de archivo de imagen
  const clientAvatar = document.getElementById("clientAvatar");
  const filePreview = document.querySelector(".file-preview");

  if (clientAvatar && filePreview) {
    clientAvatar.addEventListener("change", function () {
      const file = this.files[0];

      if (file) {
        const reader = new FileReader();

        reader.onload = (e) => {
          filePreview.innerHTML = `
                        <div style="position: relative; margin-right: 10px;">
                            <img src="${e.target.result}" alt="Vista previa" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                            <button type="button" class="remove-file" style="position: absolute; top: -8px; right: -8px; width: 20px; height: 20px; border-radius: 50%; background-color: #f44336; color: white; border: none; font-size: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <span>${file.name}</span>
                    `;

          // Agregar evento para eliminar archivo
          const removeFileBtn = filePreview.querySelector(".remove-file");
          if (removeFileBtn) {
            removeFileBtn.addEventListener("click", () => {
              clientAvatar.value = "";
              filePreview.innerHTML = "";
            });
          }
        };

        reader.readAsDataURL(file);
      }
    });
  }

  // Guardar cliente
  const saveClient = document.getElementById("saveClient");
  const clientForm = document.getElementById("clientForm");

  if (saveClient && clientForm) {
    saveClient.addEventListener("click", () => {
      // Validar formulario
      const activeTab = document.querySelector(".tab-pane.active");
      const requiredFields = activeTab.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add("invalid");
        } else {
          field.classList.remove("invalid");
        }
      });

      if (isValid) {
        // Aquí iría la lógica para guardar el cliente
        alert("Cliente guardado correctamente");
        closeAllModals();
        clientForm.reset();

        if (filePreview) {
          filePreview.innerHTML = "";
        }
      } else {
        alert("Por favor complete todos los campos obligatorios");
      }
    });
  }

  // Acciones de tabla (ver, editar, eliminar)
  const viewButtons = document.querySelectorAll(
    '.btn-icon[title="Ver detalles"], .btn-sm[title="Ver detalles"]'
  );
  const editButtons = document.querySelectorAll(
    '.btn-icon[title="Editar"], .btn-sm[title="Editar"]'
  );
  const deleteButtons = document.querySelectorAll(
    '.btn-icon[title="Eliminar"], .btn-sm[title="Eliminar"]'
  );

  viewButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const clientName = row.querySelector(".client-info h4").textContent;
        alert("Ver detalles de: " + clientName);
      } else {
        // Para vista de cuadrícula
        const card = this.closest(".client-card");
        if (card) {
          const clientName = card.querySelector("h3").textContent;
          alert("Ver detalles de: " + clientName);
        }
      }
    });
  });

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const clientName = row.querySelector(".client-info h4").textContent;
        openModal(clientModal);
        document.querySelector(".modal-header h2").textContent =
          "Editar Cliente";
        document.getElementById("clientName").value = clientName;
        // Aquí se cargarían todos los datos del cliente
      } else {
        // Para vista de cuadrícula
        const card = this.closest(".client-card");
        if (card) {
          const clientName = card.querySelector("h3").textContent;
          openModal(clientModal);
          document.querySelector(".modal-header h2").textContent =
            "Editar Cliente";
          document.getElementById("clientName").value = clientName;
          // Aquí se cargarían todos los datos del cliente
        }
      }
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const clientName = row.querySelector(".client-info h4").textContent;
        if (
          confirm(
            "¿Está seguro de que desea eliminar el cliente: " + clientName + "?"
          )
        ) {
          // Aquí iría la lógica para eliminar el cliente
          alert("Cliente eliminado: " + clientName);
        }
      } else {
        // Para vista de cuadrícula
        const card = this.closest(".client-card");
        if (card) {
          const clientName = card.querySelector("h3").textContent;
          if (
            confirm(
              "¿Está seguro de que desea eliminar el cliente: " +
                clientName +
                "?"
            )
          ) {
            // Aquí iría la lógica para eliminar el cliente
            alert("Cliente eliminado: " + clientName);
          }
        }
      }
    });
  });

  // Botón de exportar clientes
  const btnExportClients = document.getElementById("btnExportClients");

  if (btnExportClients) {
    btnExportClients.addEventListener("click", () => {
      const format = prompt(
        "Seleccione el formato de exportación (csv, excel, pdf):",
        "excel"
      );

      if (format) {
        alert("Exportando clientes en formato " + format);
      }
    });
  }

  // Ver detalles de clientes recientes
  const recentClientButtons = document.querySelectorAll(
    '.recent-client-contact .btn-icon[title="Ver detalles"]'
  );

  recentClientButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const card = this.closest(".recent-client-card");
      const clientName = card.querySelector("h4").textContent;

      alert("Ver detalles de: " + clientName);
    });
  });

  // Ver detalles de clientes principales
  const topClientButtons = document.querySelectorAll(
    ".top-client-actions .btn"
  );

  topClientButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const card = this.closest(".top-client-card");
      const clientName = card.querySelector("h4").textContent;

      alert("Ver detalles de: " + clientName);
    });
  });
});
