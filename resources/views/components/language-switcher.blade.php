<div class="relative inline-block">
    <button type="button" 
            onclick="document.getElementById('langDropdownAjax').classList.toggle('hidden')"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none">
        @if(app()->getLocale() == 'en')
            <span class="mr-2">🇺🇸</span>
            <span class="hidden lg:flex">English</span>
        @else
            <span class="mr-2">🇰🇭</span>
            <span class="hidden lg:flex">ខ្មែរ</span>
        @endif
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div id="langDropdownAjax" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
        <!-- English Option -->
        <button type="button" onclick="setLanguage('en')"
                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 rounded-t-lg {{ app()->getLocale() == 'en' ? 'bg-blue-50' : '' }}">
            <div class="flex items-center">
                <span class="mr-3">🇺🇸</span>
                <span>English</span>
            </div>
            @if(app()->getLocale() == 'en')
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </button>
        
        <!-- Khmer Option -->
        <button type="button" onclick="setLanguage('km')"
                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 rounded-b-lg border-t border-gray-100 {{ app()->getLocale() == 'km' ? 'bg-blue-50' : '' }}">
            <div class="flex items-center">
                <span class="mr-3">🇰🇭</span>
                <span>ខ្មែរ</span>
            </div>
            @if(app()->getLocale() == 'km')
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            @endif
        </button>
    </div>
</div>

<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('langDropdownAjax');
    const button = event.target.closest('button[onclick*="langDropdownAjax"]');
    
    if (!button) {
        if (dropdown && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    }
});

// Set language via AJAX
async function setLanguage(lang) {
    // Close dropdown
    document.getElementById('langDropdownAjax').classList.add('hidden');
    
    // Show loading on main button
    const mainButton = document.querySelector('button[onclick*="langDropdownAjax"]');
    const originalHTML = mainButton.innerHTML;
    mainButton.innerHTML = '<span class="animate-spin">⟳</span>';
    mainButton.disabled = true;
    
    try {
        // Send AJAX request
        const response = await fetch('{{ route("language.ajax") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ locale: lang })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update main button appearance
            const flag = lang === 'en' ? '🇺🇸' : '🇰🇭';
            const text = lang === 'en' ? 'English' : 'ខ្មែរ';
            
            mainButton.innerHTML = `<span class="mr-2">${flag}</span><span>${text}</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>`;
            
            // Reload page to apply translations
            setTimeout(() => {
                window.location.reload();
            }, 300);
        } else {
            throw new Error(data.message || 'Failed to change language');
        }
    } catch (error) {
        console.error('Error:', error);
        // Revert button
        mainButton.innerHTML = originalHTML;
        mainButton.disabled = false;
        alert('Failed to change language. Please try again.');
    }
}
</script>