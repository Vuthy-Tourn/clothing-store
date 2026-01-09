@extends('layouts.front')
@section('content')
    <!-- Include SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include QRCode.js -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12 mt-5">
        <div class="max-w-xl mx-auto px-4">
            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <!-- Order Header -->
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-store text-white"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">NOVA STUDIO</p>
                                <p class="text-gray-300 text-xs">{{ __('messages.order_number') }}
                                    {{ substr($order->order_number, -6) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-bold text-xl">${{ number_format($order->total_amount, 2) }}</p>
                            <p class="text-gray-300 text-sm">≈ ៛{{ number_format($amountKHR, 0) }}
                                {{ __('messages.khr_currency') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Content -->
                <div class="p-6">
                    <!-- Timer -->
                    <div class="mb-8 text-center">
                        <div
                            class="inline-flex items-center gap-3 px-5 py-3 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border border-amber-200 shadow-sm">
                            <div class="relative">
                                <i class="fas fa-clock text-amber-600 text-lg"></i>
                                <div class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-medium text-amber-800">{{ __('messages.time_remaining') }}</p>
                                <p id="countdown" class="font-mono font-bold text-gray-900 text-xl">10:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="text-center mb-8">
                        <div class="relative inline-block">
                            <!-- QR Container -->
                            <div class="relative bg-white p-4 rounded-xl border-2 border-gray-300 shadow-lg">
                                <div id="qrcode" class="w-64 h-64 mx-auto"></div>

                                <!-- Loading Spinner -->
                                <div id="qr-loading"
                                    class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-90 rounded-xl">
                                    <div class="text-center">
                                        <div
                                            class="w-12 h-12 border-4 border-gray-200 border-t-black rounded-full animate-spin mx-auto mb-2">
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ __('messages.generating_qr') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Badge -->
                            <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2">
                                <div
                                    class="px-4 py-2 rounded-full shadow-lg 
                                @if ($bank == 'aba') bg-blue-600 
                                @elseif($bank == 'acleda') bg-green-600 
                                @else bg-purple-600 @endif">
                                    <div class="flex items-center gap-2">
                                        @if ($bank == 'aba')
                                            <i class="fas fa-building text-white"></i>
                                            <span
                                                class="text-white font-medium text-sm">{{ __('messages.aba_bank') }}</span>
                                        @elseif($bank == 'acleda')
                                            <i class="fas fa-university text-white"></i>
                                            <span
                                                class="text-white font-medium text-sm">{{ __('messages.acleda_bank') }}</span>
                                        @elseif($bank == 'wing')
                                            <i class="fas fa-mobile-alt text-white"></i>
                                            <span
                                                class="text-white font-medium text-sm">{{ __('messages.wing_bank') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-blue-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ __('messages.how_to_pay') }}</h3>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div
                                    class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                    1</div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('messages.step1_qr_payment') }}</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div
                                    class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                    2</div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('messages.step2_qr_payment') }}</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div
                                    class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                    3</div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('messages.step3_qr_payment') }}</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                <div
                                    class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                                    4</div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ __('messages.step4_qr_payment') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <button onclick="markPaymentComplete()"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-3">
                            <i class="fas fa-check-circle text-lg"></i>
                            <span>{{ __('messages.i_have_paid') }}</span>
                        </button>

                        <button onclick="cancelPayment()"
                            class="w-full border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 py-3 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>{{ __('messages.back_to_checkout') }}</span>
                        </button>
                    </div>

                    <!-- Support -->
                    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                        <p class="text-gray-500 text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-headset text-gray-400"></i>
                            <span>{{ __('messages.need_help_qr') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Auto Refresh -->
            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-sync-alt animate-spin mr-2"></i>
                    {{ __('messages.auto_refresh_note') }}
                </p>
            </div>
        </div>
    </div>

<script>
    let timeLeft = 600; // 10 minutes in seconds
    let timerInterval;
    let orderNumber = '{{ $order->order_number }}';
    let isPaymentVerified = false;
    
    // Get current language from body data attribute
    function getCurrentLanguage() {
        return document.body.getAttribute('data-lang') || 'en';
    }
    
    let currentLanguage = getCurrentLanguage();

    // Language texts for SweetAlert
    const alertTexts = {
        'en': {
            confirmTitle: 'Confirm Payment Completion',
            confirmText: 'Have you successfully scanned and paid with QR code?',
            confirmYes: 'Yes, I have paid',
            confirmNo: 'No, go back',
            cancelTitle: 'Cancel Payment?',
            cancelText: 'You will be returned to checkout page',
            cancelYes: 'Yes, go back',
            cancelNo: 'Continue payment',
            successTitle: 'Payment Successful! 🎉',
            successText: 'Your order #ORDER# has been confirmed!',
            redirectText: 'You will be redirected to your order details',
            viewOrder: 'View Order Details',
            timeoutTitle: 'Payment Timeout ⏰',
            timeoutText: 'Your payment session has expired. Please try again.',
            returnCheckout: 'Return to Checkout',
            backCart: 'Back to Cart',
            copied: 'Copied!',
            copiedText: 'Order number copied to clipboard'
        },
        'km': {
            confirmTitle: 'បញ្ជាក់ការទូទាត់ប្រាក់',
            confirmText: 'តើអ្នកបានស្កេន និងទូទាត់ប្រាក់តាម QR Code ដោយជោគជ័យហើយឬទេ?',
            confirmYes: 'បាទ/ចាស ខ្ញុំបានទូទាត់រួចរាល់',
            confirmNo: 'ទេ ត្រលប់ក្រោយ',
            cancelTitle: 'បោះបង់ការទូទាត់?',
            cancelText: 'អ្នកនឹងត្រូវត្រលប់ទៅកាន់ទំព័របញ្ជាទិញ',
            cancelYes: 'បាទ/ចាស ត្រលប់ក្រោយ',
            cancelNo: 'បន្តការទូទាត់',
            successTitle: 'ទូទាត់ប្រាក់ជោគជ័យ! 🎉',
            successText: 'ការកម្មង់លេខ #ORDER# របស់អ្នកត្រូវបានបញ្ជាក់ហើយ!',
            redirectText: 'អ្នកនឹងត្រូវបញ្ជូនទៅកាន់ព័ត៌មានការកម្មង់របស់អ្នក',
            viewOrder: 'មើលព័ត៌មានការកម្មង់',
            timeoutTitle: 'ការទូទាត់ផុតកំណត់ ⏰',
            timeoutText: 'ពេលវេលាទូទាត់ប្រាក់របស់អ្នកបានផុតកំណត់។ សូមព្យាយាមម្តងទៀត។',
            returnCheckout: 'ត្រឡប់ទៅ Checkout',
            backCart: 'ត្រឡប់ទៅរទេះទិញឥវ៉ាន់',
            copied: 'បានចម្លង!',
            copiedText: 'លេខកូដកម្មង់ត្រូវបានចម្លងទៅក្តារតម្បៀតខ្ទាស់'
        }
    };

    // Generate QR code using QRCode.js with better options
    function generateQRCode() {
        // QR data
        const qrData = JSON.stringify({
            order: orderNumber,
            amount: {{ $order->total_amount }},
            currency: 'USD',
            bank: '{{ $bank }}',
            timestamp: Date.now(),
            merchant: 'Nova Studio'
        });

        // Clear container
        document.getElementById('qrcode').innerHTML = '';

        // Generate QR with better options
        new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 256,
            height: 256,
            colorDark: "#111827",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Hide loading spinner after QR is generated
        setTimeout(() => {
            document.getElementById('qr-loading').classList.add('hidden');
        }, 1000);
    }

    function startCountdown() {
        clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                showTimeoutAlert();
                return;
            }

            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;

            document.getElementById('countdown').textContent =
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            // Change color when less than 1 minute
            if (timeLeft < 60) {
                document.getElementById('countdown').classList.add('text-red-600');
            }
        }, 1000);
    }

    function copyOrderNumber() {
        navigator.clipboard.writeText(orderNumber).then(() => {
            // Update current language before showing alert
            currentLanguage = getCurrentLanguage();
            const texts = alertTexts[currentLanguage];
            
            Swal.fire({
                icon: 'success',
                title: texts.copied,
                text: texts.copiedText,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#10b981',
                color: 'white'
            });
        });
    }

    function checkPaymentStatus() {
        if (isPaymentVerified) return;

        fetch('{{ route('orders.show', $order->order_number) }}')
            .then(response => response.text())
            .then(data => {
                if (data.includes('confirmed') || data.includes('paid')) {
                    isPaymentVerified = true;
                    clearInterval(timerInterval);
                    showSuccessAlert();
                }
            })
            .catch(error => console.error('Error checking payment:', error));
    }

    function markPaymentComplete() {
        // Update current language before showing alert
        currentLanguage = getCurrentLanguage();
        const texts = alertTexts[currentLanguage];

        Swal.fire({
            title: texts.confirmTitle,
            text: texts.confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: texts.confirmYes,
            cancelButtonText: texts.confirmNo,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('checkout.qr.verify.post', $order->order_number) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function cancelPayment() {
        // Update current language before showing alert
        currentLanguage = getCurrentLanguage();
        const texts = alertTexts[currentLanguage];

        Swal.fire({
            title: texts.cancelTitle,
            text: texts.cancelText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#ef4444',
            confirmButtonText: texts.cancelYes,
            cancelButtonText: texts.cancelNo,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route('checkout') }}';
            }
        });
    }

    function showSuccessAlert() {
        // Update current language before showing alert
        currentLanguage = getCurrentLanguage();
        const texts = alertTexts[currentLanguage];
        const successText = texts.successText.replace('#ORDER#', orderNumber);

        Swal.fire({
            title: texts.successTitle,
            html: `
        <div class="text-center">
            <p class="text-gray-700 mb-2">${successText}</p>
            <p class="text-gray-600 text-sm mb-4">${texts.redirectText}</p>
            <div class="mt-4 pt-4 border-0">
                <a href="{{ route('orders.show', $order->order_number) }}" 
                   class="inline-block px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 border-0 border-transparent transition-colors">
                    ${texts.viewOrder}
                </a>
            </div>
        </div>
    `,
            icon: 'success',
            showConfirmButton: false,
            timer: 10000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                setTimeout(() => {
                    window.location.href = '{{ route('orders.show', $order->order_number) }}';
                }, 10000);
            }
        });
    }

    function showTimeoutAlert() {
        // Update current language before showing alert
        currentLanguage = getCurrentLanguage();
        const texts = alertTexts[currentLanguage];

        Swal.fire({
            title: texts.timeoutTitle,
            html: `
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-4">
                <i class="fas fa-clock text-red-500 text-6xl"></i>
            </div>
            <p class="text-gray-700 mb-4">${texts.timeoutText}</p>
            <div class="space-y-2">
                <a href="{{ route('checkout') }}" 
                   class="block w-full px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors text-center">
                    ${texts.returnCheckout}
                </a>
                <a href="{{ route('cart') }}" 
                   class="block w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center">
                    ${texts.backCart}
                </a>
            </div>
        </div>
    `,
            icon: 'error',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    }

    // Start countdown and auto-check on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Generate QR code
        generateQRCode();

        startCountdown();

        // Check payment status every 5 seconds
        setInterval(checkPaymentStatus, 5000);

        // Also check when page becomes visible again
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden && !isPaymentVerified) {
                checkPaymentStatus();
            }
        });
    });
    
    // Listen for language changes (if using AJAX language switcher)
    document.addEventListener('languageChanged', function() {
        currentLanguage = getCurrentLanguage();
    });
</script>

    <style>
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        #qrcode canvas {
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        #qrcode:hover canvas {
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }
    </style>
@endsection
