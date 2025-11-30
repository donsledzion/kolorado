@extends('layouts.app')

@section('title', 'Nowa Siatka')

@section('content')
<a href="{{ route('grids.index') }}" style="color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block;">&larr; Powrót</a>

@if($errors->any())
    <div class="alert alert-error">
        <ul style="margin-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('grids.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
    @csrf

    <!-- Drag & Drop Zone -->
    <div id="dropZone" style="
        margin-bottom: 20px;
        padding: 40px;
        border: 3px dashed #ccc;
        border-radius: 10px;
        text-align: center;
        background: #fafafa;
        cursor: pointer;
        transition: all 0.3s ease;
    ">
        <input type="file" name="image" id="image" accept="image/*" required style="display: none;">

        <div id="dropContent">
            <svg style="width: 80px; height: 80px; margin: 0 auto 20px; color: #999;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 style="margin: 0 0 10px 0; color: #333;">Przeciągnij i upuść obrazek tutaj</h3>
            <p style="margin: 0 0 15px 0; color: #666;">lub</p>
            <button type="button" id="browseBtn" class="btn" style="background: #28a745;">Wybierz plik</button>
            <p style="margin: 15px 0 0 0; color: #999; font-size: 14px;">JPG, PNG, GIF (max 10MB)</p>
        </div>

        <div id="previewContainer" style="display: none;">
            <img id="imagePreview" style="max-width: 100%; max-height: 300px; border-radius: 5px; margin-bottom: 15px;">
            <p id="fileName" style="font-weight: bold; color: #333; margin: 0 0 10px 0;"></p>
            <button type="button" id="changeBtn" class="btn" style="background: #6c757d;">Zmień obrazek</button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
        <div>
            <label for="grid_width" style="display: block; margin-bottom: 5px; font-weight: bold;">Szerokość siatki:</label>
            <input type="number" name="grid_width" id="grid_width" value="30" min="10" max="52" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>

        <div>
            <label for="grid_height" style="display: block; margin-bottom: 5px; font-weight: bold;">Wysokość siatki:</label>
            <input type="number" name="grid_height" id="grid_height" value="30" min="10" max="52" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>

        <div>
            <label for="color_count" style="display: block; margin-bottom: 5px; font-weight: bold;">Liczba kolorów:</label>
            <input type="number" name="color_count" id="color_count" value="8" min="2" max="20" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Typ numeracji:</label>
        <div style="display: flex; gap: 20px;">
            <label style="display: flex; align-items: center; gap: 5px;">
                <input type="radio" name="numbering_type" value="numbers" checked>
                <span>Numerki w polach (1, 2, 3...)</span>
            </label>
            <label style="display: flex; align-items: center; gap: 5px;">
                <input type="radio" name="numbering_type" value="chess">
                <span>Współrzędne jak szachy (A1, B2...)</span>
            </label>
        </div>
        <small style="color: #666; display: block; margin-top: 5px;">Dla szachownicy max 52x52 (a-z, A-Z)</small>
    </div>

    <button type="submit" class="btn" id="submitBtn">Generuj Siatkę</button>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" style="display: none; text-align: center; margin-top: 20px;">
        <div style="
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        "></div>
        <p style="margin-top: 15px; color: #666; font-weight: bold;">Generowanie siatki...</p>
    </div>
</form>

@push('styles')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #dropZone.dragover {
        border-color: #007bff !important;
        background: #e7f3ff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image');
    const browseBtn = document.getElementById('browseBtn');
    const changeBtn = document.getElementById('changeBtn');
    const dropContent = document.getElementById('dropContent');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    const uploadForm = document.getElementById('uploadForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Kliknięcie w dropzone lub przycisk "Wybierz plik"
    dropZone.addEventListener('click', (e) => {
        if (e.target !== changeBtn && !previewContainer.contains(e.target)) {
            fileInput.click();
        }
    });

    browseBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.click();
    });

    changeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.click();
    });

    // Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // Wybór pliku
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });

    function handleFileSelect(file) {
        // Walidacja rozmiaru
        if (file.size > 10 * 1024 * 1024) {
            alert('Plik jest za duży! Maksymalny rozmiar to 10MB.');
            return;
        }

        // Walidacja typu
        if (!file.type.match('image.*')) {
            alert('Proszę wybrać plik graficzny (JPG, PNG, GIF).');
            return;
        }

        // Pokaż preview
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            fileName.textContent = file.name;
            dropContent.style.display = 'none';
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Spinner przy wysyłaniu formularza
    uploadForm.addEventListener('submit', function() {
        submitBtn.style.display = 'none';
        loadingSpinner.style.display = 'block';
    });
</script>
@endpush
@endsection
