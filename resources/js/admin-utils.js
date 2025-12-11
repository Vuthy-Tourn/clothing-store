// Modal Management System
class ModalManager {
    constructor() {
        this.modals = new Map();
    }

    register(name, modalElement) {
        this.modals.set(name, modalElement);
    }

    open(name) {
        const modal = this.modals.get(name);
        if (modal) modal.classList.remove("hidden");
    }

    close(name) {
        const modal = this.modals.get(name);
        if (modal) modal.classList.add("hidden");
    }

    closeAll() {
        this.modals.forEach((modal) => modal.classList.add("hidden"));
    }
}

// Initialize global modal manager
window.modalManager = new ModalManager();

// Delete Confirmation Modal
class DeleteModal {
    static open(type, id, name) {
        const modal = document.getElementById("deleteModal");
        const form = document.getElementById("deleteForm");
        const text = document.getElementById("deleteModalText");

        const routes = {
            product: `/admin/products/${id}`,
            carousel: `/admin/carousels/${id}`,
            category: `/admin/categories/${id}`,
            featured: `/admin/featured/${id}`,
            arrival: `/admin/new-arrivals/${id}`,
        };

        if (routes[type]) {
            form.action = routes[type];
            text.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            modal.classList.remove("hidden");
        }
    }

    static close() {
        document.getElementById("deleteModal").classList.add("hidden");
    }
}

// Export for global use
window.DeleteModal = DeleteModal;

// Form Validation Utilities
class FormValidator {
    static validateRequired(fields) {
        return fields.every((field) => field.value.trim() !== "");
    }

    static showError(field, message) {
        const errorElement = field.nextElementSibling;
        if (errorElement && errorElement.classList.contains("error-message")) {
            errorElement.textContent = message;
            errorElement.classList.remove("hidden");
        }
    }

    static hideError(field) {
        const errorElement = field.nextElementSibling;
        if (errorElement && errorElement.classList.contains("error-message")) {
            errorElement.classList.add("hidden");
        }
    }
}
