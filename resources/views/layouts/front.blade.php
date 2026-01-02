<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'Nova Studio')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset(path: 'assets/css/style.css') }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                once: false
            });
        });
    </script>
</head>

<body>

    @include('partials.navbar')

    <x-toast />
    <x-subscribe-modal />


    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="{{ asset('assets/js/filter-system.js') }}"></script>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('datePicker', (initial) => ({
                show: false,
                selectedDate: '',
                displayDate: '',
                month: 0,
                year: 0,
                monthNames: [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ],

                init() {
                    const today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();

                    if (initial) {
                        const d = new Date(initial);
                        if (!isNaN(d)) {
                            this.month = d.getMonth();
                            this.year = d.getFullYear();
                            this.selectDate(d.getDate(), false);
                        }
                    }
                },

                get daysInMonth() {
                    return new Date(this.year, this.month + 1, 0).getDate();
                },

                get blanks() {
                    return new Array(new Date(this.year, this.month, 1).getDay()).fill(null);
                },

                selectDate(day, close = true) {
                    const d = new Date(this.year, this.month, day);
                    this.selectedDate = d.toISOString().slice(0, 10); // YYYY-MM-DD
                    this.displayDate =
                        `${d.getDate().toString().padStart(2,'0')}/${(d.getMonth()+1).toString().padStart(2,'0')}/${d.getFullYear()}`;
                    if (close) this.show = false;
                },

                selectToday() {
                    const today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.selectDate(today.getDate());
                },

                prevMonth() {
                    if (this.month === 0) {
                        this.month = 11;
                        this.year--;
                    } else this.month--;
                },

                nextMonth() {
                    if (this.month === 11) {
                        this.month = 0;
                        this.year++;
                    } else this.month++;
                },

                isSelected(day) {
                    return this.selectedDate === new Date(this.year, this.month, day).toISOString()
                        .slice(0, 10);
                }
            }));
        });
    </script>

    @stack('styles')
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @endpush
    @stack('scripts')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showSuccessToast === 'function') {
                    showSuccessToast(@json(session('success')));
                } else {
                    // fallback (if JS toast not yet loaded)
                    alert(@json(session('success')));
                }
            });
        </script>
    @endif

</body>

</html>
