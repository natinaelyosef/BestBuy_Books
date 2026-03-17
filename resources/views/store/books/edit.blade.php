@extends('store.registration-layout')

@section('title', 'Edit Book')

@section('content')
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="min-h-screen bg-gray-100 py-10">
                    <div class="max-w-6xl mx-auto px-6">
                        <!-- HEADER -->
                        <div class="mb-10">
                            <h1 class="text-4xl font-bold text-gray-800 tracking-tight">
                                Edit Book
                            </h1>
                            <p class="text-gray-500 mt-2">Update your bookstore catalog</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" enctype="multipart/form-data" class="space-y-10" action="{{ route('books.update', $book->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- SECTION CARD STYLE -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200 p-8 space-y-6">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                    📘 Book Information
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-sm font-medium text-gray-600">Title *</label>
                                        <input type="text" name="title" required
                                            value="{{ old('title', $book->title) }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-control" placeholder="enter name of title">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-sm font-medium text-gray-600">Author *</label>
                                        <input type="text" name="author" required
                                            value="{{ old('author', $book->author) }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-control" placeholder="enter author name">
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-sm font-medium text-gray-600">Genre *</label>
                                        <select name="genre" required
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                            @php
                                                $genre = old('genre', $book->genre);
                                            @endphp
                                            <option value="">Select Genre</option>
                                            <option value="fiction" @selected($genre === 'fiction')>Fiction</option>
                                            <option value="non-fiction" @selected($genre === 'non-fiction')>Non-Fiction</option>
                                            <option value="sci-fi" @selected($genre === 'sci-fi')>Science Fiction</option>
                                            <option value="fantasy" @selected($genre === 'fantasy')>Fantasy</option>
                                        </select>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-sm font-medium text-gray-600">Publication Year</label>
                                        <input type="number" name="publication_year"
                                            value="{{ old('publication_year', $book->publication_year) }}"
                                            class="w-full px-4 py-3 rounded-xl border border-gray-300 
                                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    </div>
                                </div>
                            </div>

                            <!-- INVENTORY -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200 p-8 space-y-6">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                    📦 Inventory Details
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <input type="number" name="total_copies" required placeholder="Total Copies"
                                        value="{{ old('total_copies', $book->total_copies) }}"
                                        class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 transition">

                                    <input type="number" name="available_rent" required placeholder="Available for Rent"
                                        value="{{ old('available_rent', $book->available_rent) }}"
                                        class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 transition">

                                    <input type="number" name="available_sale" required placeholder="Available for Sale"
                                        value="{{ old('available_sale', $book->available_sale) }}"
                                        class="px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                            </div>

                            <!-- PRICING -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200 p-8 space-y-6">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                    💰 Pricing
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative">
                                        <span class="absolute left-4 top-3 text-gray-400">$</span>
                                        <input type="number" name="rental_price" required step="0.01"
                                            value="{{ old('rental_price', $book->rental_price) }}"
                                            class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-300 form-control
                                            focus:ring-2 focus:ring-blue-500 transition"
                                            placeholder="Rental Price">
                                    </div>

                                    <div class="relative">
                                        <span class="absolute left-4 top-3 text-gray-400">$</span>
                                        <input type="number" name="sale_price" required step="0.01"
                                            value="{{ old('sale_price', $book->sale_price) }}"
                                            class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-300 
                                            focus:ring-2 focus:ring-blue-500 transition"
                                            placeholder="Sale Price">
                                    </div>
                                </div>
                            </div>

                            <!-- IMAGE UPLOAD SECTION -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200 p-8 space-y-6">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                    🖼️ Book Cover Image
                                </h2>

                                <div class="space-y-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition cursor-pointer" 
                                         onclick="document.getElementById('cover_image').click()">
                                        <input type="file" name="cover_image" id="cover_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                        
                                        <!-- Image preview container -->
                                        <div id="image_preview_container" class="@if(!$book->cover_image_path) hidden @endif mb-4">
                                            <img id="image_preview" src="{{ $book->cover_image_path ? asset($book->cover_image_path) : '#' }}" alt="Cover preview" class="max-h-48 mx-auto rounded-lg shadow-md">
                                        </div>
                                        
                                        <!-- Upload icon and text -->
                                        <div id="upload_prompt" class="@if($book->cover_image_path) hidden @endif">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H8a4 4 0 01-4-4v-20m32 12h-8m-12 0h-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Click to upload book cover image</p>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Remove image button -->
                                    <button type="button" onclick="removeImage()" class="text-sm text-red-600 hover:text-red-800 @if(!$book->cover_image_path) hidden @endif" id="remove_image_btn">
                                        <i class="fas fa-trash-alt mr-1"></i> Remove Image
                                    </button>
                                </div>
                            </div>

                            <!-- PDF UPLOAD SECTION -->
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200 p-8 space-y-4">
                                <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                                    Book PDF
                                </h2>
                                @if($book->pdf_path)
                                    <div class="text-sm text-gray-600">
                                        Current PDF: {{ $book->pdf_name ?? 'PDF file' }}
                                    </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Upload new PDF (optional)</label>
                                    <input type="file" name="pdf_file" accept="application/pdf" class="form-control mt-2">
                                    <p class="text-xs text-gray-500 mt-2">Uploading a new file replaces the existing PDF.</p>
                                </div>
                                @if($book->pdf_path)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_pdf" value="1" id="remove_pdf">
                                        <label class="form-check-label text-sm text-gray-600" for="remove_pdf">
                                            Remove current PDF
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <!-- STICKY ACTION BAR -->
                            <div class="sticky bottom-0 border-t border-gray-200 p-6 flex justify-end gap-4 rounded-b-2xl shadow-lg bg-white">
                                <button type="button"
                                    onclick="window.history.back()"
                                    class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition btn btn-danger">
                                    Cancel
                                </button>

                                <button type="submit"
                                    class="px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow-md transition btn btn-primary">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
    function previewImage(input) {
        const previewContainer = document.getElementById('image_preview_container');
        const preview = document.getElementById('image_preview');
        const uploadPrompt = document.getElementById('upload_prompt');
        const removeBtn = document.getElementById('remove_image_btn');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
                removeBtn.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const fileInput = document.getElementById('cover_image');
        const previewContainer = document.getElementById('image_preview_container');
        const uploadPrompt = document.getElementById('upload_prompt');
        const removeBtn = document.getElementById('remove_image_btn');
        
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        uploadPrompt.classList.remove('hidden');
        removeBtn.classList.add('hidden');
    }
    </script>

    <style>
        .border-2 { border-width: 2px; }
        .border-dashed { border-style: dashed; }
        .hidden { display: none; }
    </style>
@endsection
