<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-6">Test Add Product</h1>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ url('/test-add-product') }}" method="POST" class="space-y-4 max-w-md">
        @csrf
        <div>
            <label class="block mb-1">Product Name *</label>
            <input type="text" name="name" required class="w-full border p-2 rounded" value="Test Product">
        </div>
        
        <div>
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">Test description</textarea>
        </div>
        
        <div>
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Test Submit</button>
    </form>
    
    <div class="mt-8">
        <a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:underline">
            ← Back to Products
        </a>
    </div>
</body>
</html>