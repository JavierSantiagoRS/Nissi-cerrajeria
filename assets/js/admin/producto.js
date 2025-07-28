document.addEventListener("DOMContentLoaded", function () {
  // Toggle Sidebar
  const toggleMenu = document.querySelector(".toggle-menu");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");

  if (toggleMenu) {
    toggleMenu.addEventListener("click", function () {
      sidebar.classList.toggle("sidebar-collapsed");
      mainContent.classList.toggle("expanded");
    });
  }

  // Cambiar vista de inventario  tabla / cuadrícula;
  const viewBtns = document.querySelectorAll(".view-btn");
  const inventoryViews = document.querySelectorAll(".inventory-view");

  viewBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const view = this.getAttribute("data-view");

      // Cambiar botones activos
      viewBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      // Cambiar vista activa
      inventoryViews.forEach((v) => v.classList.remove("active"));
      document.querySelector(`.${view}-view`).classList.add("active");
    });
  });

  // Búsqueda de inventarios
  const productSearch = document.getElementById("productSearch");

  if (productSearch) {
    productSearch.addEventListener("keyup", function () {
      // Aquí iría la lógica de búsqueda
      console.log("Buscando: " + this.value);
    });
  }

  // Filtros de categoría y stock
  const categoryFilter = document.getElementById("categoryFilter");
  const stockFilter = document.getElementById("stockFilter");
  const sortFilter = document.getElementById("sortFilter");

  // Aplicar filtros cuando cambian
  [categoryFilter, stockFilter, sortFilter].forEach((filter) => {
    if (filter) {
      filter.addEventListener("change", function () {
        applyFilters();
      });
    }
  });

  function applyFilters() {
    // Aquí iría la lógica para aplicar todos los filtros
    console.log("Aplicando filtros:");
    console.log("Categoría:", categoryFilter.value);
    console.log("Stock:", stockFilter.value);
    console.log("Ordenar por:", sortFilter.value);
  }

  // Checkboxes de inventarios
  const selectAll = document.getElementById("selectAll");
  const productCheckboxes = document.querySelectorAll(".product-checkbox");

  if (selectAll) {
    selectAll.addEventListener("change", function () {
      productCheckboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
      updateSelectedCount();
    });
  }

  productCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      updateSelectedCount();
      // Verificar si todos están seleccionados para actualizar selectAll
      if (selectAll) {
        const allChecked = [...productCheckboxes].every((c) => c.checked);
        selectAll.checked = allChecked;
      }
    });
  });

  function updateSelectedCount() {
    const selectedCount = document.getElementById("selectedCount");
    if (selectedCount) {
      const count = [...productCheckboxes].filter((c) => c.checked).length;
      selectedCount.textContent = count;
    }
  }

  // Modal para añadir/editar inventario  const btnAddProduct = document.getElementById("btnAddProduct");
  const productModal = document.getElementById("productModal");
  const modalOverlay = document.querySelector(".modal-overlay");
  const modalCloseButtons = document.querySelectorAll(
    ".modal-close, .modal-cancel"
  );

  if (btnAddProduct) {
    btnAddProduct.addEventListener("click", function () {
      openModal(productModal);
    });
  }

  // Modal de importar/exportar
  const btnImportExport = document.getElementById("btnImportExport");
  const importExportModal = document.getElementById("importExportModal");

  if (btnImportExport) {
    btnImportExport.addEventListener("click", function () {
      openModal(importExportModal);
    });
  }

  // Cerrar modales
  modalCloseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      closeAllModals();
    });
  });

  // Cerrar modal al hacer clic en el overlay
  if (modalOverlay) {
    modalOverlay.addEventListener("click", function () {
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

  // Tabs de importar/exportar
  const tabBtns = document.querySelectorAll(".tab-btn");
  const tabPanes = document.querySelectorAll(".tab-pane");
  const importExportAction = document.getElementById("importExportAction");

  tabBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const tab = this.getAttribute("data-tab");

      // Cambiar botones activos
      tabBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      // Cambiar pestaña activa
      tabPanes.forEach((p) => p.classList.remove("active"));
      document.getElementById(tab).classList.add("active");

      // Cambiar texto del botón de acción
      if (importExportAction) {
        importExportAction.textContent =
          tab === "import" ? "Importar" : "Exportar";
      }
    });
  });

  // Vista previa de archivo de imagen
  const productImage = document.getElementById("productImage");
  const filePreview = document.querySelector(".file-preview");

  if (productImage && filePreview) {
    productImage.addEventListener("change", function () {
      const file = this.files[0];

      if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
          filePreview.innerHTML = `
                        <div style="position: relative; margin-right: 10px;">
                            <img src="${e.target.result}" alt="Vista previa" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            <button type="button" class="remove-file" style="position: absolute; top: -8px; right: -8px; width: 20px; height: 20px; border-radius: 50%; background-color: #f44336; color: white; border: none; font-size: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <span>${file.name}</span>
                    `;

          // Agregar evento para eliminar archivo
          const removeFileBtn = filePreview.querySelector(".remove-file");
          if (removeFileBtn) {
            removeFileBtn.addEventListener("click", function () {
              productImage.value = "";
              filePreview.innerHTML = "";
            });
          }
        };

        reader.readAsDataURL(file);
      }
    });
  }

  // Vista previa de archivo de importación
  const importFile = document.getElementById("importFile");
  const fileInfo = document.querySelector(".file-info");

  if (importFile && fileInfo) {
    importFile.addEventListener("change", function () {
      const file = this.files[0];

      if (file) {
        fileInfo.innerHTML = `
                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-file-excel" style="font-size: 24px; color: #4CAF50; margin-right: 10px;"></i>
                        <div>
                            <div>${file.name}</div>
                            <div style="font-size: 12px; color: #757575;">${formatFileSize(
                              file.size
                            )}</div>
                        </div>
                        <button type="button" class="remove-file" style="margin-left: 15px; background: none; border: none; color: #f44336; cursor: pointer;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

        // Agregar evento para eliminar archivo
        const removeFileBtn = fileInfo.querySelector(".remove-file");
        if (removeFileBtn) {
          removeFileBtn.addEventListener("click", function () {
            importFile.value = "";
            fileInfo.innerHTML = "";
          });
        }
      }
    });
  }

  // Formatear tamaño de archivo
  function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + " bytes";
    else if (bytes < 1048576) return (bytes / 1024).toFixed(2) + " KB";
    else return (bytes / 1048576).toFixed(2) + " MB";
  }

  // Guardar inventario  const saveProduct = document.getElementById("saveProduct");
  const productForm = document.getElementById("productForm");

  if (saveProduct && productForm) {
    saveProduct.addEventListener("click", function () {
      // Validar formulario
      if (productForm.checkValidity()) {
        // Aquí iría la lógica para guardar el inventario        alert("Producto guardado correctamente");
        closeAllModals();
        productForm.reset();

        if (filePreview) {
          filePreview.innerHTML = "";
        }
      } else {
        // Trigger HTML5 validation
        productForm.reportValidity();
      }
    });
  }

  // Acción para importar/exportar
  if (importExportAction) {
    importExportAction.addEventListener("click", function () {
      const activeTab = document.querySelector(".tab-pane.active");

      if (activeTab.id === "import") {
        // Lógica para importar
        const importFile = document.getElementById("importFile");

        if (importFile && importFile.files.length > 0) {
          alert("Importando archivo: " + importFile.files[0].name);
          closeAllModals();
        } else {
          alert("Por favor seleccione un archivo para importar");
        }
      } else {
        // Lógica para exportar
        const format = document.getElementById("exportFormat").value;

        if (document.getElementById("exportAll").checked) {
          alert("Exportando todos los inventarios en formato " + format);
        } else if (document.getElementById("exportLowStock").checked) {
          alert("Exportando inventarios con stock bajo en formato " + format);
        } else if (document.getElementById("exportSelected").checked) {
          const count = document.getElementById("selectedCount").textContent;

          if (parseInt(count) > 0) {
            alert(
              "Exportando " +
                count +
                " inventarios seleccionados en formato " +
                format
            );
          } else {
            alert("No hay inventarios seleccionados para exportar");
            return;
          }
        }

        closeAllModals();
      }
    });
  }

  // Subcategorías dependiendo de la categoría
  const productCategory = document.getElementById("productCategory");
  const productSubcategory = document.getElementById("productSubcategory");

  if (productCategory && productSubcategory) {
    productCategory.addEventListener("change", function () {
      const category = this.value;

      // Limpiar opciones actuales
      productSubcategory.innerHTML =
        '<option value="">Seleccionar subcategoría</option>';

      // Añadir subcategorías según la categoría seleccionada
      if (category === "cerraduras") {
        addSubcategoryOptions([
          "Cerraduras Embutidas",
          "Cerraduras de Sobreponer",
          "Cerraduras Multipunto",
          "Cerraduras para Muebles",
          "Cerrojos",
        ]);
      } else if (category === "llaves") {
        addSubcategoryOptions([
          "Llaves Planas",
          "Llaves Tetra",
          "Llaves de Puntos",
          "Llaves Cruciformes",
          "Llaves de Automóvil",
        ]);
      } else if (category === "candados") {
        addSubcategoryOptions([
          "Candados Tradicionales",
          "Candados de Alta Seguridad",
          "Candados para Exteriores",
          "Candados de Combinación",
          "Candados de Latón",
        ]);
      } else if (category === "digital") {
        addSubcategoryOptions([
          "Cerraduras Digitales",
          "Cerraduras Biométricas",
          "Sistemas de Control de Acceso",
          "Cerraduras con Código",
          "Cerraduras Inteligentes",
        ]);
      } else if (category === "accesorios") {
        addSubcategoryOptions([
          "Bisagras",
          "Pomos y Manillas",
          "Cilindros",
          "Herrajes",
          "Topes de Puerta",
          "Mirillas",
        ]);
      }
    });

    function addSubcategoryOptions(options) {
      options.forEach((option) => {
        const optElement = document.createElement("option");
        optElement.value = option.toLowerCase().replace(/\s+/g, "-");
        optElement.textContent = option;
        productSubcategory.appendChild(optElement);
      });
    }
  }

  // Acciones de tabla (ver, editar, eliminar)
  const viewButtons = document.querySelectorAll(
    '.btn-icon[title="Ver detalles"]'
  );
  const editButtons = document.querySelectorAll('.btn-icon[title="Editar"]');
  const deleteButtons = document.querySelectorAll(
    '.btn-icon[title="Eliminar"]'
  );

  viewButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const productName = row.querySelector("td:nth-child(4)").textContent;
        alert("Ver detalles de: " + productName);
      } else {
        // Para vista de cuadrícula
        const card = this.closest(".product-card");
        if (card) {
          const productName = card.querySelector("h4").textContent;
          alert("Ver detalles de: " + productName);
        }
      }
    });
  });

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const productName = row.querySelector("td:nth-child(4)").textContent;
        openModal(productModal);
        document.querySelector(".modal-header h2").textContent =
          "Editar Producto";
        document.getElementById("productName").value = productName;
        // Aquí se cargarían todos los datos del inventario      } else {
        // Para vista de cuadrícula
        const card = this.closest(".product-card");
        if (card) {
          const productName = card.querySelector("h4").textContent;
          openModal(productModal);
          document.querySelector(".modal-header h2").textContent =
            "Editar Producto";
          document.getElementById("productName").value = productName;
          // Aquí se cargarían todos los datos del inventario
        }
      }
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");

      if (row) {
        const productName = row.querySelector("td:nth-child(4)").textContent;
        if (
          confirm(
            "¿Está seguro de que desea eliminar el inventario: " +
              productName +
              "?"
          )
        ) {
          // Aquí iría la lógica para eliminar el inventario          alert("Producto eliminado: " + productName);
        }
      } else {
        // Para vista de cuadrícula
        const card = this.closest(".product-card");
        if (card) {
          const productName = card.querySelector("h4").textContent;
          if (
            confirm(
              "¿Está seguro de que desea eliminar el inventario: " +
                productName +
                "?"
            )
          ) {
            // Aquí iría la lógica para eliminar el inventario            alert("Producto eliminado: " + productName);
          }
        }
      }
    });
  });

  // Botones de reordenar stock bajo
  const reorderButtons = document.querySelectorAll(".low-stock-card .btn");

  reorderButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const card = this.closest(".low-stock-card");
      const productName = card.querySelector("h4").textContent;

      alert("Reordenando inventario: " + productName);
    });
  });
});
