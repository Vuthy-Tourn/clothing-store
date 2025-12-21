<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column - Profile Card -->
    <div class="lg:col-span-1">
        @include('admin.profile.partials.profile-card')
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-8">
        @include('admin.profile.partials.personal-info')
        @include('admin.profile.partials.account-settings')
    </div>
</div>