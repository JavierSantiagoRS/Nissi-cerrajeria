document.addEventListener("DOMContentLoaded", () => {
  // Referencias a elementos del DOM
  const toggleMenu = document.querySelector(".toggle-menu");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");
  const messageSearch = document.getElementById("messageSearch");
  const statusFilter = document.getElementById("statusFilter");
  const categoryFilter = document.getElementById("categoryFilter");
  const sortFilter = document.getElementById("sortFilter");
  const showUnreadOnly = document.getElementById("showUnreadOnly");
  const messageModal = document.getElementById("messageModal");
  const replyModal = document.getElementById("replyModal");
  const modalOverlay = document.querySelector(".modal-overlay");
  const btnRefresh = document.getElementById("btnRefresh");

  // Datos de mensajes (simulados)
  const messagesData = [
    {
      id: 1,
      sender: {
        name: "María González",
        email: "maria.gonzalez@email.com",
        phone: "+34 612 345 678",
        avatar: "/placeholder.svg?height=40&width=40",
      },
      subject: "Consulta sobre servicio de cerrajería",
      content:
        "Hola, necesito información sobre el servicio de cambio de cerradura para mi casa. Me gustaría saber los precios y disponibilidad para esta semana. La cerradura actual está dando problemas y necesito una solución rápida. ¿Podrían enviarme un presupuesto? Gracias.",
      category: "consulta",
      status: "nuevo",
      date: "Hoy 14:30",
      isRead: false,
      isStarred: false,
      isUrgent: false,
    },
    {
      id: 2,
      sender: {
        name: "Carlos Ruiz",
        email: "carlos.ruiz@empresa.com",
        phone: "+34 623 456 789",
        avatar: "/placeholder.svg?height=40&width=40",
      },
      subject: "Solicitud de cita urgente",
      content:
        "Buenos días, necesito una cita urgente para hoy si es posible. Se me ha roto la cerradura de la puerta principal de mi oficina y no puedo acceder. Es una situación muy urgente ya que tengo reuniones importantes. Por favor, contacten conmigo lo antes posible.",
      category: "cita",
      status: "respondido",
      date: "Hoy 12:15",
      isRead: true,
      isStarred: true,
      isUrgent: true,
    },
    {
      id: 3,
      sender: {
        name: "Ana Martínez",
        email: "ana.martinez@email.com",
        phone: "+34 634 567 890",
        avatar: "/placeholder.svg?height=40&width=40",
      },
      subject: "Problema con servicio realizado",
      content:
        "Quiero presentar un reclamo sobre el servicio que realizaron ayer en mi domicilio. La cerradura que instalaron no funciona correctamente y la puerta no cierra bien. Necesito que vengan a revisar el trabajo lo antes posible. No estoy satisfecha con el resultado.",
      category: "reclamo",
      status: "nuevo",
      date: "Ayer 16:45",
      isRead: false,
      isStarred: false,
      isUrgent: false,
    },
    {
      id: 4,
      sender: {
        name: "Pedro López",
        email: "pedro.lopez@email.com",
        phone: "+34 645 678 901",
        avatar: "/placeholder.svg?height=40&width=40",
      },
      subject: "Solicitud de cotización para oficina",
      content:
        "Estimados, necesito una cotización para cambiar todas las cerraduras de mi oficina. Son aproximadamente 8 puertas y necesito cerraduras de alta seguridad. También me interesa un sistema de llaves maestras. ¿Podrían enviarme un presupuesto detallado?",
      category: "cotizacion",
      status: "leido",
      date: "2 días",
      isRead: true,
      isStarred: false,
      isUrgent: false,
    },
    {
      id: 5,
      sender: {
        name: "Laura Fernández",
        email: "laura.fernandez@email.com",
        phone: "+34 656 789 012",
        avatar: "/placeholder.svg?height=40&width=40",
      },
      subject: "Agradecimiento por excelente servicio",
      content:
        "Quería agradecerles por el excelente servicio que brindaron. Todo quedó perfecto y el técnico fue muy profesional. Definitivamente los recomendaré a mis conocidos. Muchas gracias por su profesionalismo y calidad de trabajo.",
      category: "general",
      status: "archivado",
      date: "3 días",
      isRead: true,
      isStarred: true,
      isUrgent: false,
    },
  ];

  let currentMessages = [...messagesData];
  let selectedMessage = null;

  // Inicializar eventos
  initEvents();
  renderMessages();

  function initEvents() {
    // Toggle sidebar
    if (toggleMenu) {
      toggleMenu.addEventListener("click", () => {
        sidebar.classList.toggle("sidebar-collapsed");
        mainContent.classList.toggle("expanded");
      });
    }

    // Búsqueda de mensajes
    if (messageSearch) {
      messageSearch.addEventListener("input", filterMessages);
    }

    // Filtros
    if (statusFilter) statusFilter.addEventListener("change", filterMessages);
    if (categoryFilter)
      categoryFilter.addEventListener("change", filterMessages);
    if (sortFilter) sortFilter.addEventListener("change", filterMessages);
    if (showUnreadOnly)
      showUnreadOnly.addEventListener("change", filterMessages);

    // Botón de actualizar
    if (btnRefresh) {
      btnRefresh.addEventListener("click", () => {
        // Simular actualización
        btnRefresh.innerHTML =
          '<i class="fas fa-sync-alt fa-spin"></i> Actualizando...';
        setTimeout(() => {
          btnRefresh.innerHTML = '<i class="fas fa-sync-alt"></i> Actualizar';
          renderMessages();
        }, 1000);
      });
    }

    // Cerrar modales
    document.querySelectorAll(".modal-close, .modal-cancel").forEach((btn) => {
      btn.addEventListener("click", closeModals);
    });

    if (modalOverlay) {
      modalOverlay.addEventListener("click", closeModals);
    }

    // Eventos de modal de mensaje
    const markAsReadBtn = document.getElementById("markAsRead");
    const toggleStarBtn = document.getElementById("toggleStar");
    const replyMessageBtn = document.getElementById("replyMessage");
    const deleteMessageBtn = document.getElementById("deleteMessage");

    if (markAsReadBtn)
      markAsReadBtn.addEventListener("click", toggleReadStatus);
    if (toggleStarBtn)
      toggleStarBtn.addEventListener("click", toggleStarStatus);
    if (replyMessageBtn)
      replyMessageBtn.addEventListener("click", openReplyModal);
    if (deleteMessageBtn)
      deleteMessageBtn.addEventListener("click", deleteMessage);

    // Enviar respuesta
    const sendReplyBtn = document.getElementById("sendReply");
    if (sendReplyBtn) sendReplyBtn.addEventListener("click", sendReply);
  }

  function renderMessages() {
    const messagesList = document.querySelector(".messages-list");
    if (!messagesList) return;

    messagesList.innerHTML = "";

    currentMessages.forEach((message) => {
      const messageElement = createMessageElement(message);
      messagesList.appendChild(messageElement);
    });

    // Actualizar contadores
    updateSummaryCounters();
  }

  function createMessageElement(message) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `message-item ${message.isRead ? "" : "unread"}`;
    messageDiv.setAttribute("data-id", message.id);

    messageDiv.innerHTML = `
            <div class="message-checkbox">
                <input type="checkbox" class="message-check">
            </div>
            <div class="message-star ${message.isStarred ? "starred" : ""}">
                <i class="${message.isStarred ? "fas" : "far"} fa-star"></i>
            </div>
            <div class="message-sender">
                <div class="sender-avatar">
                    <img src="${message.sender.avatar}" alt="${
      message.sender.name
    }">
                </div>
                <div class="sender-info">
                    <h4>${message.sender.name}</h4>
                    <p>${message.sender.email}</p>
                </div>
            </div>
            <div class="message-content">
                <div class="message-subject">
                    <span class="category-tag ${
                      message.category
                    }">${getCategoryLabel(message.category)}</span>
                    ${message.subject}
                    ${
                      message.isUrgent
                        ? '<i class="fas fa-exclamation-circle urgent-icon" title="Urgente"></i>'
                        : ""
                    }
                </div>
                <div class="message-preview">
                    ${message.content.substring(0, 100)}...
                </div>
            </div>
            <div class="message-meta">
                <div class="message-date">${message.date}</div>
                <div class="message-status">
                    <span class="status ${message.status}">${getStatusLabel(
      message.status
    )}</span>
                </div>
            </div>
            <div class="message-actions">
                <button class="btn-icon view-message" title="Ver mensaje"><i class="fas fa-eye"></i></button>
                <button class="btn-icon reply-message" title="Responder"><i class="fas fa-reply"></i></button>
                <button class="btn-icon delete-message" title="Eliminar"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;

    // Eventos para este mensaje
    const starBtn = messageDiv.querySelector(".message-star");
    const viewBtn = messageDiv.querySelector(".view-message");
    const replyBtn = messageDiv.querySelector(".reply-message");
    const deleteBtn = messageDiv.querySelector(".delete-message");

    starBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      toggleMessageStar(message.id);
    });

    viewBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      openMessageModal(message);
    });

    replyBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      selectedMessage = message;
      openReplyModal();
    });

    deleteBtn.addEventListener("click", (e) => {
      e.stopPropagation();

      Swal.fire({
        title: "¿Estás seguro?",
        text: "Este mensaje se eliminará permanentemente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          deleteMessageById(message.id);
          Swal.fire(
            "Eliminado",
            "El mensaje fue eliminado con éxito",
            "success"
          );
        }
      });
    });

    // Click en el mensaje para abrirlo
    messageDiv.addEventListener("click", () => {
      openMessageModal(message);
    });

    return messageDiv;
  }

  function getCategoryLabel(category) {
    const labels = {
      consulta: "Consulta",
      cita: "Cita",
      reclamo: "Reclamo",
      cotizacion: "Cotización",
      general: "General",
    };
    return labels[category] || category;
  }

  function getStatusLabel(status) {
    const labels = {
      nuevo: "Nuevo",
      leido: "Leído",
      respondido: "Respondido",
      archivado: "Archivado",
    };
    return labels[status] || status;
  }

  function filterMessages() {
    const searchTerm = messageSearch ? messageSearch.value.toLowerCase() : "";
    const statusValue = statusFilter ? statusFilter.value : "";
    const categoryValue = categoryFilter ? categoryFilter.value : "";
    const sortValue = sortFilter ? sortFilter.value : "fecha-desc";
    const unreadOnly = showUnreadOnly ? showUnreadOnly.checked : false;

    // Filtrar mensajes
    currentMessages = messagesData.filter((message) => {
      const matchesSearch =
        !searchTerm ||
        message.sender.name.toLowerCase().includes(searchTerm) ||
        message.sender.email.toLowerCase().includes(searchTerm) ||
        message.subject.toLowerCase().includes(searchTerm) ||
        message.content.toLowerCase().includes(searchTerm);

      const matchesStatus = !statusValue || message.status === statusValue;
      const matchesCategory =
        !categoryValue || message.category === categoryValue;
      const matchesUnread = !unreadOnly || !message.isRead;

      return matchesSearch && matchesStatus && matchesCategory && matchesUnread;
    });

    // Ordenar mensajes
    currentMessages.sort((a, b) => {
      switch (sortValue) {
        case "fecha-asc":
          return new Date(a.date) - new Date(b.date);
        case "fecha-desc":
          return new Date(b.date) - new Date(a.date);
        case "nombre-asc":
          return a.sender.name.localeCompare(b.sender.name);
        case "nombre-desc":
          return b.sender.name.localeCompare(a.sender.name);
        default:
          return 0;
      }
    });

    renderMessages();
  }

  function updateSummaryCounters() {
    const totalMessages = messagesData.length;
    const unreadMessages = messagesData.filter((m) => !m.isRead).length;
    const importantMessages = messagesData.filter((m) => m.isStarred).length;
    const urgentMessages = messagesData.filter((m) => m.isUrgent).length;

    const summaryValues = document.querySelectorAll(".summary-value");
    if (summaryValues.length >= 4) {
      summaryValues[0].textContent = totalMessages;
      summaryValues[1].textContent = unreadMessages;
      summaryValues[2].textContent = importantMessages;
      summaryValues[3].textContent = urgentMessages;
    }
  }

  function toggleMessageStar(messageId) {
    const message = messagesData.find((m) => m.id === messageId);
    if (message) {
      message.isStarred = !message.isStarred;
      renderMessages();
    }
  }

  function openMessageModal(message) {
    selectedMessage = message;

    // Marcar como leído si no lo está
    if (!message.isRead) {
      message.isRead = true;
      message.status = "leido";
      renderMessages();
    }

    // Llenar datos del modal
    document.getElementById("modalSubject").textContent = message.subject;
    document.getElementById("modalSenderName").textContent =
      message.sender.name;
    document.getElementById("modalSenderEmail").textContent =
      message.sender.email;
    document.getElementById(
      "modalSenderPhone"
    ).innerHTML = `<i class="fas fa-phone"></i> ${message.sender.phone}`;
    document.getElementById("modalSenderAvatar").src = message.sender.avatar;
    document.getElementById("modalCategory").textContent = getCategoryLabel(
      message.category
    );
    document.getElementById(
      "modalCategory"
    ).className = `category-tag ${message.category}`;
    document.getElementById("modalStatus").textContent = getStatusLabel(
      message.status
    );
    document.getElementById(
      "modalStatus"
    ).className = `status ${message.status}`;
    document.getElementById("modalDate").textContent = message.date;
    document.getElementById("modalContent").textContent = message.content;

    // Actualizar botones
    const markAsReadBtn = document.getElementById("markAsRead");
    const toggleStarBtn = document.getElementById("toggleStar");

    if (markAsReadBtn) {
      markAsReadBtn.textContent = message.isRead
        ? "Marcar como no leído"
        : "Marcar como leído";
    }

    if (toggleStarBtn) {
      toggleStarBtn.textContent = message.isStarred
        ? "Quitar estrella"
        : "Agregar estrella";
    }

    // Mostrar modal
    messageModal.style.display = "block";
    modalOverlay.style.display = "block";
    document.body.style.overflow = "hidden";
  }

  function openReplyModal() {
    if (!selectedMessage) return;

    document.getElementById("replyTo").value = selectedMessage.sender.email;
    document.getElementById(
      "replySubject"
    ).value = `Re: ${selectedMessage.subject}`;
    document.getElementById("replyMessage").value = "";

    closeModals();
    replyModal.style.display = "block";
    modalOverlay.style.display = "block";
    document.body.style.overflow = "hidden";
  }

  function toggleReadStatus() {
    if (!selectedMessage) return;

    selectedMessage.isRead = !selectedMessage.isRead;
    selectedMessage.status = selectedMessage.isRead ? "leido" : "nuevo";

    const markAsReadBtn = document.getElementById("markAsRead");
    if (markAsReadBtn) {
      markAsReadBtn.textContent = selectedMessage.isRead
        ? "Marcar como no leído"
        : "Marcar como leído";
    }

    renderMessages();
  }

  function toggleStarStatus() {
    if (!selectedMessage) return;

    selectedMessage.isStarred = !selectedMessage.isStarred;

    const toggleStarBtn = document.getElementById("toggleStar");
    if (toggleStarBtn) {
      toggleStarBtn.textContent = selectedMessage.isStarred
        ? "Quitar estrella"
        : "Agregar estrella";
    }

    renderMessages();
  }

  function deleteMessage() {
    if (!selectedMessage) return;

    if (confirm("¿Estás seguro de que quieres eliminar este mensaje?")) {
      deleteMessageById(selectedMessage.id);
      closeModals();
    }
  }

  function deleteMessageById(messageId) {
    const index = messagesData.findIndex((m) => m.id === messageId);
    if (index > -1) {
      messagesData.splice(index, 1);
      filterMessages();
    }
  }

  function sendReply() {
    const replyTo = document.getElementById("replyTo").value;
    const replySubject = document.getElementById("replySubject").value;
    const replyMessage = document.getElementById("replyMessage").value;

    if (!replyMessage.trim()) {
      alert("Por favor, escribe un mensaje antes de enviar.");
      return;
    }

    // Simular envío de respuesta
    alert("Respuesta enviada correctamente");

    // Actualizar estado del mensaje original
    if (selectedMessage) {
      selectedMessage.status = "respondido";
      renderMessages();
    }

    // Limpiar formulario y cerrar modal
    document.getElementById("replyForm").reset();
    closeModals();
  }

  function closeModals() {
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
      modal.style.display = "none";
    });

    if (modalOverlay) {
      modalOverlay.style.display = "none";
    }

    document.body.style.overflow = "";
  }

  // Selección múltiple de mensajes
  const selectAllCheckbox = document.querySelector(".select-all");
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const messageCheckboxes = document.querySelectorAll(".message-check");
      messageCheckboxes.forEach((checkbox) => {
        checkbox.checked = this.checked;
      });
    });
  }

  // Acciones masivas
  document.addEventListener("click", (e) => {
    if (e.target.matches(".bulk-action")) {
      const selectedMessages = document.querySelectorAll(
        ".message-check:checked"
      );
      if (selectedMessages.length === 0) {
        alert("Selecciona al menos un mensaje");
        return;
      }

      const action = e.target.dataset.action;
      switch (action) {
        case "mark-read":
          selectedMessages.forEach((checkbox) => {
            const messageId = Number.parseInt(
              checkbox.closest(".message-item").dataset.id
            );
            const message = messagesData.find((m) => m.id === messageId);
            if (message) {
              message.isRead = true;
              message.status = "leido";
            }
          });
          break;
        case "mark-unread":
          selectedMessages.forEach((checkbox) => {
            const messageId = Number.parseInt(
              checkbox.closest(".message-item").dataset.id
            );
            const message = messagesData.find((m) => m.id === messageId);
            if (message) {
              message.isRead = false;
              message.status = "nuevo";
            }
          });
          break;
        case "delete":
          if (
            confirm(
              `¿Estás seguro de que quieres eliminar ${selectedMessages.length} mensajes?`
            )
          ) {
            selectedMessages.forEach((checkbox) => {
              const messageId = Number.parseInt(
                checkbox.closest(".message-item").dataset.id
              );
              deleteMessageById(messageId);
            });
          }
          break;
      }

      renderMessages();
    }
  });

  // Atajos de teclado
  document.addEventListener("keydown", (e) => {
    // Escape para cerrar modales
    if (e.key === "Escape") {
      closeModals();
    }

    // Ctrl/Cmd + R para actualizar
    if ((e.ctrlKey || e.metaKey) && e.key === "r") {
      e.preventDefault();
      if (btnRefresh) {
        btnRefresh.click();
      }
    }
  });

  // Auto-actualización cada 30 segundos
  setInterval(() => {
    // Simular nuevos mensajes ocasionalmente
    if (Math.random() < 0.1) {
      // 10% de probabilidad
      console.log("Simulando nuevo mensaje...");
      // Aquí se podría agregar lógica para obtener nuevos mensajes del servidor
    }
  }, 30000);
});
