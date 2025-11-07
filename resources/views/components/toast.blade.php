<div id="toastContainer" class="fixed top-5 right-5 flex flex-col gap-3 z-50 pointer-events-none"></div>

@push('scripts')
    <script>
        const toastContainer = document.getElementById('toastContainer');

        window.showToast = function(title, message, type = 'success', duration = 3000) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className =
                `flex items-center gap-4 p-4 rounded-lg shadow-lg transform -translate-y-5 opacity-0 transition-all duration-300 pointer-events-auto max-w-sm`;

            const icon = document.createElement('div');
            icon.className = 'flex-shrink-0 text-2xl';

            const content = document.createElement('div');
            content.className = 'flex-grow';
            const toastTitle = document.createElement('p');
            toastTitle.className = 'font-semibold';
            toastTitle.textContent = title;
            const toastMessage = document.createElement('p');
            toastMessage.className = 'text-sm';
            toastMessage.textContent = message;

            // Progress bar
            const progressContainer = document.createElement('div');
            progressContainer.className = 'h-1 mt-2 rounded-full overflow-hidden relative bg-opacity-20';
            const progressBar = document.createElement('div');
            progressBar.className = 'absolute top-0 left-0 h-full transition-all duration-[3000ms] rounded-full';
            progressContainer.appendChild(progressBar);

            content.appendChild(toastTitle);
            content.appendChild(toastMessage);
            content.appendChild(progressContainer);

            toast.appendChild(icon);
            toast.appendChild(content);

            // Set colors and icon based on type
            switch (type) {
                case 'success':
                    toast.classList.add('bg-green-600', 'text-white');
                    progressBar.classList.add('bg-green-300');
                    icon.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon>';
                    break;
                case 'error':
                    toast.classList.add('bg-red-600', 'text-white');
                    progressBar.classList.add('bg-red-400');
                    icon.innerHTML = '<ion-icon name="close-circle-outline"></ion-icon>';
                    break;
                case 'warning':
                    toast.classList.add('bg-yellow-400', 'text-black');
                    progressBar.classList.add('bg-yellow-300');
                    icon.innerHTML = '<ion-icon name="alert-circle-outline"></ion-icon>';
                    break;
            }

            // Append to container
            toastContainer.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', '-translate-y-5');
                toast.classList.add('opacity-100', 'translate-y-0');
                progressBar.style.width = '100%';
            });

            // Remove after duration
            setTimeout(() => {
                toast.classList.add('-translate-y-5', 'opacity-0');
                progressBar.style.width = '0';
                toast.addEventListener('transitionend', () => toast.remove());
            }, duration);
        };

        // Convenience functions
        window.showSuccessToast = (message, duration = 3000) => showToast('Success', message, 'success', duration);
        window.showErrorToast = (message, duration = 3000) => showToast('Error', message, 'error', duration);
        window.showWarningToast = (message, duration = 3000) => showToast('Warning', message, 'warning', duration);
    </script>
@endpush
