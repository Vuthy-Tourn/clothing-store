// Toast Notification System
class Toast {
    constructor() {
        this.container = null;
        this.initializeContainer();
    }
    
    initializeContainer() {
        // Create toast container if it doesn't exist
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }
    
    show(type, message, duration = 5000) {
        const toastId = 'toast-' + Date.now() + Math.random().toString(36).substr(2, 9);
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle',
            warning: 'fas fa-exclamation-triangle'
        };
        
        const titles = {
            success: this.getTranslatedTitle('success'),
            error: this.getTranslatedTitle('error'),
            info: this.getTranslatedTitle('info'),
            warning: this.getTranslatedTitle('warning')
        };
        
        const colors = {
            success: 'text-green-500',
            error: 'text-red-500',
            info: 'text-blue-500',
            warning: 'text-yellow-500'
        };
        
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = `toast toast-${type}`;
        
        toast.innerHTML = `
            <div class="toast-content">
                <button class="toast-close" onclick="Toast.getInstance().remove('${toastId}')">
                    <i class="fas fa-times"></i>
                </button>
                <div class="flex items-start gap-3">
                    <div class="mt-0.5">
                        <i class="${icons[type]} text-lg ${colors[type]}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 mb-1">${titles[type]}</h4>
                        <p class="text-sm text-gray-600">${message}</p>
                    </div>
                </div>
                <div class="toast-progress">
                    <div class="toast-progress-bar"></div>
                </div>
            </div>
        `;
        
        this.container.appendChild(toast);
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Auto remove
        const timer = setTimeout(() => {
            this.remove(toastId);
        }, duration);
        
        // Store timer reference
        toast.dataset.timer = timer;
        
        return toastId;
    }
    
    remove(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('show');
            clearTimeout(toast.dataset.timer);
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
    }
    
    success(message, duration = 5000) {
        return this.show('success', message, duration);
    }
    
    error(message, duration = 5000) {
        return this.show('error', message, duration);
    }
    
    info(message, duration = 5000) {
        return this.show('info', message, duration);
    }
    
    warning(message, duration = 5000) {
        return this.show('warning', message, duration);
    }
    
    getTranslatedTitle(type) {
        // This will be overridden by window.toastTitles if available
        const defaultTitles = {
            success: 'Success',
            error: 'Error',
            info: 'Info',
            warning: 'Warning'
        };
        
        if (window.toastTitles && window.toastTitles[type]) {
            return window.toastTitles[type];
        }
        
        return defaultTitles[type];
    }
    
    // Singleton pattern
    static instance = null;
    
    static getInstance() {
        if (!Toast.instance) {
            Toast.instance = new Toast();
        }
        return Toast.instance;
    }
}

// Initialize toast system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.ToastSystem = Toast.getInstance();
    
    // Shortcut functions
    window.showToast = (type, message, duration) => Toast.getInstance().show(type, message, duration);
    window.showSuccess = (message, duration) => Toast.getInstance().success(message, duration);
    window.showError = (message, duration) => Toast.getInstance().error(message, duration);
    window.showInfo = (message, duration) => Toast.getInstance().info(message, duration);
    window.showWarning = (message, duration) => Toast.getInstance().warning(message, duration);
});

// Export for ES modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Toast;
}