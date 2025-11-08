 <!-- Subscribe Confirmation Modal -->
 <div id="subscribeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
     <div class="relative mx-4 rounded-2xl p-6 border max-w-md h-auto shadow-lg bg-white">
         <div class="mt-3 text-center">
             <!-- Icon -->
             <div class="mx-auto flex items-center justify-center h-16 w-16 mb-4 rounded-full bg-blue-100">
                 <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                 </svg>
             </div>

             <!-- Content -->
             <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Subscribe to Updates</h3>
             <div class="mt-2 px-7 py-3">
                 <p class="text-sm text-gray-500">
                     Are you sure you want to subscribe with
                     <strong>{{ auth()->user()->email ?? 'your email' }}</strong>?
                     You'll receive exclusive fashion updates and special offers.
                 </p>
             </div>

             <!-- Buttons -->
             <div class="flex items-center justify-center gap-3 px-4 py-3 mt-4">
                 <button type="button" onclick="hideSubscribeModal()"
                     class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                     Cancel
                 </button>
                 <form action="{{ route('emails.subscribe') }}" method="POST" class="flex-1">
                     @csrf
                     <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                     <button type="submit"
                         class="w-full px-4 py-2 bg-gray-900 text-white text-base font-medium rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 transition-colors duration-200">
                         Subscribe
                     </button>
                 </form>

             </div>
         </div>
     </div>
 </div>

 <!-- Unsubscribe Confirmation Modal -->
 <div id="unsubscribeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
     <div class="relative mx-4 rounded-2xl p-6 border max-w-md h-auto shadow-lg bg-white">
         <div class="mt-3 text-center">
             <!-- Icon -->
             <div class="mx-auto flex items-center justify-center h-16 w-16 mb-4 rounded-full bg-red-100">
                 <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                 </svg>
             </div>

             <!-- Content -->
             <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Unsubscribe from Updates</h3>
             <div class="mt-2 px-7 py-3">
                 <p class="text-sm text-gray-500">
                     Are you sure you want to unsubscribe from Outfit 818 updates?
                     You'll no longer receive exclusive fashion news and special offers.
                 </p>
             </div>

             <!-- Buttons -->
             <div class="flex items-center justify-center gap-3 px-4 py-3 mt-4">
                 <button type="button" onclick="hideUnsubscribeModal()"
                     class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                     Cancel
                 </button>
                 <form action="{{ route('emails.unsubscribe') }}" method="POST" class="flex-1">
                     @csrf
                     @method('DELETE')
                     <button type="submit"
                         class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                         Unsubscribe
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- JS: toggle logic -->
 @push('scripts')
     <script>
         function showSubscribeModal() {
             document.getElementById('subscribeModal').classList.remove('hidden');
             document.body.style.overflow = 'hidden';
         }

         function hideSubscribeModal() {
             document.getElementById('subscribeModal').classList.add('hidden');
             document.body.style.overflow = 'auto';
         }

         function showUnsubscribeModal() {
             document.getElementById('unsubscribeModal').classList.remove('hidden');
             document.body.style.overflow = 'hidden';
         }

         function hideUnsubscribeModal() {
             document.getElementById('unsubscribeModal').classList.add('hidden');
             document.body.style.overflow = 'auto';
         }

         // Close when clicking outside or pressing ESC
         document.addEventListener('click', e => {
             if (e.target.id === 'subscribeModal') hideSubscribeModal();
             if (e.target.id === 'unsubscribeModal') hideUnsubscribeModal();
         });
         document.addEventListener('keydown', e => {
             if (e.key === 'Escape') {
                 hideSubscribeModal();
                 hideUnsubscribeModal();
             }
         });
     </script>
 @endpush
