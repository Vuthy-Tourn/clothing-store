@extends('admin.layouts.app')

@section('content')
    <div class="mb-8" data-aos="fade-down">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('admin.emails.title') }}</h1>
                <p class="text-gray-600 text-lg">{{ __('admin.emails.subtitle') }}</p>
            </div>
            <button onclick="showTestEmailModal()"
                class="flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-Ocean to-Ocean/80 text-white rounded-xl transition-all duration-300 hover:from-Ocean/90 hover:to-Ocean/70 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fas fa-envelope mr-2"></i> {{ __('admin.emails.send_test') }}
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">{{ __('admin.emails.stats.total_subscribers') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" id="total-subscribers">{{ $subscribers->count() }}</p>
                    <p class="text-blue-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-users mr-1"></i> {{ __('admin.emails.stats.all_time') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="150">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">{{ __('admin.emails.stats.active_now') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalActive }}</p>
                    <p class="text-green-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('admin.emails.stats.currently_subscribed') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">{{ __('admin.emails.stats.inactive') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalInactive }}</p>
                    <p class="text-purple-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-user-times mr-1"></i> {{ __('admin.emails.stats.unsubscribed') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center">
                    <i class="fas fa-user-slash text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-xl shadow-sm transform hover:-translate-y-1 transition-transform duration-300"
            data-aos="fade-up" data-aos-delay="250">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">{{ __('admin.emails.stats.growth_30_days') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">+{{ $last30DaysGrowth }}</p>
                    <p class="text-orange-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-chart-line mr-1"></i> {{ __('admin.emails.stats.new_subscribers') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-500 flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Newsletter Section -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg mb-8 overflow-hidden" data-aos="fade-up"
        data-aos-delay="300">
        <div class="bg-gradient-to-r from-gray-700 to-gray-600 p-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-paper-plane mr-3 text-blue-300"></i>{{ __('admin.emails.compose.title') }}
            </h2>
            <p class="text-gray-300 text-sm mt-1">{{ __('admin.emails.compose.subtitle') }}</p>
        </div>

        <form id="newsletter-form" class="p-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-heading mr-2 text-gray-500"></i>{{ __('admin.emails.compose.subject') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="subject" id="subject" required
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400"
                            placeholder="{{ __('admin.emails.compose.subject_placeholder') }}">
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ __('admin.emails.compose.subject_placeholder') }}</span>
                            <span id="subject-counter" class="text-xs font-medium">0/60</span>
                        </div>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-eye mr-2 text-gray-500"></i> {{ __('admin.emails.compose.preview_text') }}
                        </label>
                        <input type="text" name="preview_text" id="preview_text"
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400"
                            placeholder="{{ __('admin.emails.compose.preview_placeholder') }}">
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ __('admin.emails.compose.preview_desc') }}</span>
                            <span id="preview-counter" class="text-xs font-medium">0/120</span>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> {{ __('admin.emails.tips.title') }}
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-xs"></i> {{ __('admin.emails.tips.best_time') }}
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-xs"></i> {{ __('admin.emails.tips.personalize') }}
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle mr-2 text-xs"></i> {{ __('admin.emails.tips.test_first') }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-edit mr-2 text-gray-500"></i> {{ __('admin.emails.compose.content') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="flex space-x-2">
                                {{-- <button type="button" onclick="insertToken('{{first_name}}')" 
                                        class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                                    First Name
                                </button>
                                <button type="button" onclick="insertToken('{{last_name}}')"
                                        class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded">
                                    Last Name
                                </button> --}}
                            </div>
                        </div>
                        <textarea name="message" id="message" rows="8" required
                            class="w-full border-2 border-gray-200 bg-white text-gray-900 rounded-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all placeholder-gray-400 resize-none"
                            placeholder="{{ __('admin.emails.compose.content_placeholder') }}"></textarea>
                        <div class="flex justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ __('admin.emails.compose.content_desc') }}</span>
                            <span id="message-counter" class="text-xs font-medium">0/5000</span>
                        </div>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-gray-800">{{ __('admin.emails.compose.recipients') }}</h4>
                            <span class="text-sm text-gray-600 font-medium">
                                <i class="fas fa-users mr-1"></i>
                                <span id="recipient-count">{{ $totalActive }}</span>
                                {{ __('admin.emails.compose.recipients_desc') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">{{ __('admin.emails.compose.recipients_desc') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-8 pt-8 border-t border-gray-200">
                <div>
                    <button type="button" onclick="previewEmail()"
                        class="bg-gray-200 text-gray-800 hover:bg-gray-300 px-6 py-3 rounded-xl font-medium transition-colors flex items-center">
                        <i class="fas fa-eye mr-2"></i> {{ __('admin.emails.actions.preview') }}
                    </button>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="clearForm()"
                        class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-6 py-3 rounded-xl font-medium transition-colors">
                        <i class="fas fa-eraser mr-2"></i> {{ __('admin.emails.actions.clear') }}
                    </button>
                    <button type="submit" id="send-btn"
                        class="bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 px-8 py-3 rounded-xl font-medium transition-all transform hover:scale-105 flex items-center shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span>{{ __('admin.emails.actions.send') }}</span>
                        <span id="sending-text" class="hidden ml-2"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Subscribers List -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden" data-aos="fade-up"
        data-aos-delay="350">
        <div class="bg-gradient-to-r from-gray-50 to-white p-6 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center mr-4">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('admin.emails.list.title') }}</h2>
                    <p class="text-gray-600">{{ __('admin.emails.list.subtitle') }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <div class="relative">
                    <input type="text" id="search-subscribers"
                        class="border border-gray-300 rounded-lg px-4 py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64"
                        placeholder="{{ __('admin.emails.list.search_placeholder') }}">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button onclick="exportSubscribers()"
                    class="bg-green-100 text-green-700 hover:bg-green-200 px-4 py-2.5 rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-file-export mr-2"></i> {{ __('admin.emails.list.export') }}
                </button>
                {{-- <button onclick="showAddSubscriberModal()"
                    class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2.5 rounded-lg font-medium transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i> {{ __('admin.emails.list.add_subscriber') }}
                </button> --}}
            </div>
        </div>

        @if ($subscribers->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <label class="flex items-center">
                                    <input type="checkbox" id="select-all" class="rounded text-blue-600">
                                    <span class="ml-2">{{ __('admin.emails.list.select_all') }}</span>
                                </label>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('admin.emails.list.status') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('admin.emails.list.subscribed_date') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('admin.emails.list.user') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                {{ __('admin.emails.list.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="subscribers-table">
                        @foreach ($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50 transition-colors"
                                data-email="{{ strtolower($subscriber->email) }}" data-id="{{ $subscriber->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="subscriber-checkbox rounded text-blue-600"
                                            value="{{ $subscriber->id }}">
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold mr-3">
                                                    {{ strtoupper(substr($subscriber->email, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $subscriber->email }}</p>
                                                    @if ($subscriber->user_id)
                                                        <p class="text-gray-500 text-sm">User ID:
                                                            {{ $subscriber->user_id }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('admin.emails.list.active') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $subscriber->subscribed_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $subscriber->subscribed_at->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($subscriber->user)
                                        <div class="flex items-center">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $subscriber->user->name }}
                                                </p>
                                                <p class="text-sm text-gray-500">{{ $subscriber->user->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">{{ __('admin.emails.list.guest') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="sendTestToSubscriber('{{ $subscriber->email }}')"
                                            class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Send test email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                        <button onclick="showSubscriberInfo('{{ $subscriber->id }}')"
                                            class="text-gray-600 hover:text-gray-900 p-2 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="View details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button
                                            onclick="confirmRemoveSubscriber('{{ $subscriber->id }}', '{{ $subscriber->email }}')"
                                            class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Remove subscriber">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button onclick="deleteSelectedSubscribers()"
                            class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                            id="delete-selected-btn" disabled>
                            <i class="fas fa-trash mr-2"></i> {{ __('admin.emails.list.remove_selected') }}
                        </button>
                        <span class="text-sm text-gray-600" id="selected-count">0 selected</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-sm text-gray-600">
                            {{ __('admin.emails.list.showing') }} <span
                                class="font-semibold">{{ $subscribers->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope-open text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('admin.emails.empty.title') }}</h3>
                <p class="text-gray-600 mb-4">{{ __('admin.emails.empty.message') }}</p>
                {{-- <button onclick="showAddSubscriberModal()"
                    class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition-colors flex items-center mx-auto">
                    <i class="fas fa-plus mr-2"></i> {{ __('admin.emails.empty.add_first') }}
                </button> --}}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .counter-exceeded {
            color: #ef4444;
            font-weight: bold;
        }

        .character-limit {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }

        .token-btn {
            transition: all 0.2s ease;
        }

        .token-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Initialize character counters
        document.addEventListener('DOMContentLoaded', function() {
            // Subject counter
            const subjectField = document.getElementById('subject');
            const subjectCounter = document.getElementById('subject-counter');
            if (subjectField && subjectCounter) {
                subjectField.addEventListener('input', function() {
                    updateCounter(this, subjectCounter, 60);
                });
                updateCounter(subjectField, subjectCounter, 60);
            }

            // Preview counter
            const previewField = document.getElementById('preview_text');
            const previewCounter = document.getElementById('preview-counter');
            if (previewField && previewCounter) {
                previewField.addEventListener('input', function() {
                    updateCounter(this, previewCounter, 120);
                });
                updateCounter(previewField, previewCounter, 120);
            }

            // Message counter
            const messageField = document.getElementById('message');
            const messageCounter = document.getElementById('message-counter');
            if (messageField && messageCounter) {
                messageField.addEventListener('input', function() {
                    updateCounter(this, messageCounter, 5000);
                });
                updateCounter(messageField, messageCounter, 5000);
            }

            // Bulk selection
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                });
            }

            // Individual checkbox selection
            document.querySelectorAll('.subscriber-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Search functionality
            const searchField = document.getElementById('search-subscribers');
            if (searchField) {
                searchField.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#subscribers-table tr');

                    rows.forEach(row => {
                        const email = row.getAttribute('data-email');
                        const text = row.textContent.toLowerCase();

                        if (text.includes(searchTerm) || email.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

        function updateCounter(field, counterElement, maxLength) {
            const count = field.value.length;
            counterElement.textContent = count + '/' + maxLength;

            if (count > maxLength) {
                counterElement.classList.add('counter-exceeded');
                field.classList.add('character-limit');
            } else {
                counterElement.classList.remove('counter-exceeded');
                field.classList.remove('character-limit');
            }
        }

        function updateSelectedCount() {
            const selectedCheckboxes = document.querySelectorAll('.subscriber-checkbox:checked');
            const selectedCount = document.getElementById('selected-count');
            const deleteBtn = document.getElementById('delete-selected-btn');
            const selectAll = document.getElementById('select-all');

            const count = selectedCheckboxes.length;
            selectedCount.textContent = count + ' selected';
            deleteBtn.disabled = count === 0;

            if (selectAll) {
                const totalCheckboxes = document.querySelectorAll('.subscriber-checkbox').length;
                selectAll.checked = count === totalCheckboxes;
                selectAll.indeterminate = count > 0 && count < totalCheckboxes;
            }
        }

        // Insert token into message field
        function insertToken(token) {
            const messageField = document.getElementById('message');
            if (messageField) {
                const start = messageField.selectionStart;
                const end = messageField.selectionEnd;
                const text = messageField.value;
                messageField.value = text.substring(0, start) + token + text.substring(end);
                messageField.focus();
                messageField.setSelectionRange(start + token.length, start + token.length);

                Swal.fire({
                    icon: 'success',
                    title: 'Token Inserted',
                    text: `Added ${token} to your message`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        }

        // Preview email
        function previewEmail() {
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            const previewText = document.getElementById('preview_text').value;

            if (!subject || !message) {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __('admin.emails.messages.missing_content') }}',
                    text: '{{ __('admin.emails.messages.missing_content_desc') }}',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            Swal.fire({
                title: '{{ __('admin.emails.modals.preview.title') }}',
                html: `
                <div style="text-align: left; max-height: 60vh; overflow-y: auto; font-family: sans-serif;">
                    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                        <h3 style="margin: 0 0 10px 0; color: #1e293b;">Subject: ${subject}</h3>
                        ${previewText ? `<p style="color: #64748b; font-style: italic; margin: 0;">Preview: ${previewText}</p>` : ''}
                    </div>
                    <div style="background: white; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; white-space: pre-wrap;">
                        ${message.replace(/\n/g, '<br>')}
                    </div>
                </div>
            `,
                width: 700,
                showCancelButton: true,
                confirmButtonText: 'Looks Good',
                cancelButtonText: 'Edit',
                confirmButtonColor: '#10b981'
            });
        }

        // Clear form
        function clearForm() {
            Swal.fire({
                title: '{{ __('admin.emails.modals.clear_form.title') }}',
                text: '{{ __('admin.emails.modals.clear_form.text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, clear it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('subject').value = '';
                    document.getElementById('preview_text').value = '';
                    document.getElementById('message').value = '';

                    // Reset counters
                    updateCounter(document.getElementById('subject'), document.getElementById('subject-counter'),
                        60);
                    updateCounter(document.getElementById('preview_text'), document.getElementById(
                        'preview-counter'), 120);
                    updateCounter(document.getElementById('message'), document.getElementById('message-counter'),
                        5000);

                    Swal.fire({
                        icon: 'success',
                        title: 'Form Cleared!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        }

        // Send newsletter
        document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const sendBtn = document.getElementById('send-btn');
            const sendingText = document.getElementById('sending-text');
            const recipientCount = document.getElementById('recipient-count').textContent;

            // Get values directly from fields
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            const previewText = document.getElementById('preview_text').value;

            if (!subject || !message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Subject and message are required',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Confirm sending
            const result = await Swal.fire({
                title: 'Send Newsletter?',
                html: `This will send the newsletter to <strong>${recipientCount}</strong> active subscribers.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Send Now!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (!result.isConfirmed) return;

            // Show loading state
            sendBtn.disabled = true;
            sendBtn.classList.remove('hover:scale-105');
            sendingText.textContent = 'Sending...';
            sendingText.classList.remove('hidden');

            try {
                const response = await fetch('{{ route('admin.emails.send') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // Add this header
                    },
                    body: JSON.stringify({
                        subject: subject,
                        message: message,
                        preview_text: previewText
                    })
                });

                // First check if response is JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // If not JSON, get the text to see what's returned
                    const text = await response.text();
                    console.error('Non-JSON response:', text.substring(0, 200));
                    throw new Error('Server returned an invalid response. Please try again.');
                }

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to send newsletter');
                }

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Newsletter Sent!',
                        html: `Successfully sent to <strong>${data.count}</strong> subscribers.`,
                        confirmButtonColor: '#10b981'
                    });

                    // Clear form on success
                    this.reset();
                    updateCounter(document.getElementById('subject'), document.getElementById(
                        'subject-counter'), 60);
                    updateCounter(document.getElementById('preview_text'), document.getElementById(
                        'preview-counter'), 120);
                    updateCounter(document.getElementById('message'), document.getElementById(
                        'message-counter'), 5000);
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Sending Failed',
                    text: error.message || 'Failed to send newsletter. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                // Reset button state
                sendBtn.disabled = false;
                sendBtn.classList.add('hover:scale-105');
                sendingText.classList.add('hidden');
            }
        });

        // Send test email modal
        function showTestEmailModal() {
            Swal.fire({
                title: '{{ __('admin.emails.modals.test_email.title') }}',
                html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Test Email Address</label>
                        <input type="email" id="test-email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2"
                               placeholder="test@example.com"
                               value="{{ auth()->user()->email ?? '' }}">
                    </div>
                    <div class="text-sm text-gray-500">
                        <p>{{ __('admin.emails.modals.test_email.description') }}</p>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: '{{ __('admin.emails.modals.test_email.send') }}',
                cancelButtonText: '{{ __('admin.emails.modals.test_email.cancel') }}',
                confirmButtonColor: '#3b82f6',
                preConfirm: () => {
                    const email = document.getElementById('test-email').value;

                    if (!email) {
                        Swal.showValidationMessage('Please enter an email address');
                        return false;
                    }

                    if (!/^\S+@\S+\.\S+$/.test(email)) {
                        Swal.showValidationMessage('Please enter a valid email address');
                        return false;
                    }

                    return {
                        email: email
                    };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const {
                        email
                    } = result.value;
                    const subject = document.getElementById('subject').value || 'Test Newsletter';
                    const message = document.getElementById('message').value || 'This is a test email.';
                    const previewText = document.getElementById('preview_text').value || '';

                    if (!message) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Content',
                            text: 'Please enter some content in the message field first',
                            confirmButtonColor: '#3b82f6'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Sending Test Email...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch('{{ route('admin.emails.test') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email,
                                subject: subject,
                                message: message,
                                preview_text: previewText
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Test Email Sent!',
                                text: `Test email sent to ${email}`,
                                confirmButtonColor: '#10b981'
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Send',
                            text: error.message || 'Could not send test email',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }

        // Send test to specific subscriber
        function sendTestToSubscriber(email) {
            const subject = document.getElementById('subject').value || 'Test Email';
            const message = document.getElementById('message').value || 'This is a test email.';

            if (!message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Content',
                    text: 'Please enter some content in the message field first',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            Swal.fire({
                title: '{{ __('admin.emails.confirmations.send_test.title') }}',
                html: `Send test to <strong>${email}</strong>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ __('admin.emails.confirmations.send_test.confirm') }}',
                cancelButtonText: '{{ __('admin.emails.confirmations.send_test.cancel') }}'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('{{ route('admin.emails.test') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email,
                                subject: subject + ' [TEST]',
                                message: message,
                                preview_text: document.getElementById('preview_text').value ||
                                    ''
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Test Sent!',
                                text: `Test email sent to ${email}`,
                                confirmButtonColor: '#10b981'
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Send',
                            text: error.message || 'Could not send test email',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }

        // Export subscribers
        async function exportSubscribers() {
            const result = await Swal.fire({
                title: '{{ __('admin.emails.confirmations.export.title') }}',
                text: '{{ __('admin.emails.confirmations.export.message') }}',
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'CSV',
                denyButtonText: 'Excel',
                cancelButtonText: '{{ __('admin.emails.confirmations.export.cancel') }}',
                confirmButtonColor: '#10b981',
                denyButtonColor: '#3b82f6'
            });

            if (result.isConfirmed || result.isDenied) {
                const format = result.isConfirmed ? 'csv' : 'xlsx';

                Swal.fire({
                    title: 'Exporting...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Trigger download
                window.location.href = `{{ route('admin.emails.export') }}?format=${format}`;

                // Close loading after delay
                setTimeout(() => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Export Complete',
                        text: 'Subscribers exported successfully',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }, 1000);
            }
        }

        // Remove subscriber with confirmation
        async function confirmRemoveSubscriber(id, email) {
            const result = await Swal.fire({
                title: '{{ __('admin.emails.confirmations.remove_subscriber.title') }}',
                html: `Are you sure you want to remove <strong>${email}</strong> from the newsletter?<br><br><small class="text-gray-500">They will be marked as unsubscribed.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ __('admin.emails.confirmations.remove_subscriber.confirm') }}',
                cancelButtonText: '{{ __('admin.emails.confirmations.remove_subscriber.cancel') }}',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/emails/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove row from table
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        if (row) {
                            row.remove();
                        }

                        // Update counters
                        const activeCount = parseInt(document.getElementById('recipient-count').textContent);
                        document.getElementById('recipient-count').textContent = activeCount - 1;
                        document.querySelectorAll('[id*="subscribers"]').forEach(el => {
                            if (el.textContent.includes(activeCount)) {
                                el.textContent = el.textContent.replace(activeCount, activeCount - 1);
                            }
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Subscriber Removed!',
                            text: `${email} has been unsubscribed`,
                            confirmButtonColor: '#10b981',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Remove',
                        text: error.message || 'Could not remove subscriber',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
        }

        // Delete selected subscribers
        async function deleteSelectedSubscribers() {
            const selectedIds = Array.from(document.querySelectorAll('.subscriber-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) return;

            const result = await Swal.fire({
                title: 'Remove Selected Subscribers?',
                html: `Remove <strong>${selectedIds.length}</strong> subscriber${selectedIds.length > 1 ? 's' : ''}?<br><br><small class="text-gray-500">They will be marked as unsubscribed.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('{{ route('admin.emails.bulk-delete') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ids: selectedIds
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove rows from table
                        selectedIds.forEach(id => {
                            const row = document.querySelector(`tr[data-id="${id}"]`);
                            if (row) row.remove();
                        });

                        // Update counters
                        const activeCount = parseInt(document.getElementById('recipient-count').textContent);
                        const newCount = activeCount - selectedIds.length;
                        document.getElementById('recipient-count').textContent = newCount;

                        // Reset selection
                        document.getElementById('select-all').checked = false;
                        document.getElementById('delete-selected-btn').disabled = true;
                        document.getElementById('selected-count').textContent = '0 selected';

                        Swal.fire({
                            icon: 'success',
                            title: 'Subscribers Removed!',
                            html: `Successfully removed ${selectedIds.length} subscriber${selectedIds.length > 1 ? 's' : ''}`,
                            confirmButtonColor: '#10b981',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Remove',
                        text: 'Could not remove selected subscribers',
                        confirmButtonColor: '#ef4444'
                    });
                }
            }
        }

        // Add subscriber modal
        function showAddSubscriberModal() {
            Swal.fire({
                title: '{{ __('admin.emails.modals.add_subscriber.title') }}',
                html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" id="new-email" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2"
                               placeholder="subscriber@example.com"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name (Optional)</label>
                        <input type="text" id="new-name" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2"
                               placeholder="John Doe">
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" id="send-welcome" class="mr-2" checked>
                            <span class="text-sm text-blue-800">Send welcome email</span>
                        </label>
                    </div>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: '{{ __('admin.emails.modals.add_subscriber.add') }}',
                cancelButtonText: '{{ __('admin.emails.modals.add_subscriber.cancel') }}',
                confirmButtonColor: '#3b82f6',
                preConfirm: () => {
                    const email = document.getElementById('new-email').value;
                    const name = document.getElementById('new-name').value;
                    const sendWelcome = document.getElementById('send-welcome').checked;

                    if (!email) {
                        Swal.showValidationMessage('{{ __('admin.emails.messages.enter_email') }}');
                        return false;
                    }

                    if (!/^\S+@\S+\.\S+$/.test(email)) {
                        Swal.showValidationMessage('{{ __('admin.emails.messages.valid_email') }}');
                        return false;
                    }

                    return {
                        email,
                        name,
                        send_welcome: sendWelcome
                    };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const {
                        email,
                        name,
                        send_welcome
                    } = result.value;

                    try {
                        const response = await fetch('{{ route('emails.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email,
                                name: name,
                                send_welcome: send_welcome
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Subscriber Added!',
                                html: `${email} has been added to your newsletter`,
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Add',
                            text: error.message || 'Could not add subscriber',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }

        // Show subscriber info
        function showSubscriberInfo(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (!row) return;

            const email = row.querySelector('.font-semibold').textContent;
            const status = row.querySelector('.bg-green-100').textContent.trim();
            const date = row.querySelector('td:nth-child(3) .text-gray-900').textContent;
            const time = row.querySelector('td:nth-child(3) .text-gray-500').textContent;
            const user = row.querySelector('td:nth-child(4)').textContent.trim();

            Swal.fire({
                title: '{{ __('admin.emails.modals.subscriber_info.title') }}',
                html: `
                <div class="text-left space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                            ${email.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <h3 class="font-bold text-lg">${email}</h3>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${status}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('admin.emails.modals.subscriber_info.subscribed') }}</p>
                            <p class="font-medium">${date}</p>
                            <p class="text-sm text-gray-500">${time}</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-sm text-gray-500">{{ __('admin.emails.modals.subscriber_info.user') }}</p>
                            <p class="font-medium">${user}</p>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-sm font-medium text-blue-800 mb-2">{{ __('admin.emails.modals.subscriber_info.quick_actions') }}</p>
                        <div class="flex space-x-2">
                            <button onclick="sendTestToSubscriber('${email}')" class="text-sm bg-blue-600 text-white hover:bg-blue-700 px-3 py-1 rounded">{{ __('admin.emails.modals.subscriber_info.send_test') }}</button>
                            <button onclick="confirmRemoveSubscriber('${id}', '${email}')" class="text-sm bg-red-600 text-white hover:bg-red-700 px-3 py-1 rounded">{{ __('admin.emails.modals.subscriber_info.remove') }}</button>
                        </div>
                    </div>
                </div>
            `,
                confirmButtonText: '{{ __('admin.emails.modals.subscriber_info.close') }}',
                confirmButtonColor: '#3b82f6'
            });
        }
    </script>
@endpush
