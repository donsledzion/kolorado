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

    <!-- Live Preview Canvas -->
    <div id="canvasContainer" style="display: none; margin-bottom: 30px; text-align: center;">
        <h3 style="margin-bottom: 15px;">Podgląd siatki:</h3>
        <div style="display: inline-block; position: relative;">
            <canvas id="gridPreviewCanvas" style="max-width: 100%; border: 2px solid #333; border-radius: 5px;"></canvas>
        </div>
        <p id="gridDimensions" style="margin-top: 10px; color: #666; font-weight: bold;"></p>
    </div>

    <!-- Aspect Ratio Mode Toggle -->
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 10px; font-weight: bold;">Tryb proporcji:</label>
        <div style="display: flex; gap: 20px; margin-bottom: 15px;">
            <label style="display: flex; align-items: center; gap: 5px;">
                <input type="radio" name="aspect_ratio_mode" value="auto" id="aspectAuto" checked>
                <span>Auto (zachowaj proporcje obrazka)</span>
            </label>
            <label style="display: flex; align-items: center; gap: 5px;">
                <input type="radio" name="aspect_ratio_mode" value="manual" id="aspectManual">
                <span>Manualny (własne wymiary)</span>
            </label>
        </div>
    </div>

    <!-- Auto Mode Controls -->
    <div id="autoControls">
        <div style="margin-bottom: 20px;">
            <label for="gridSizeSlider" style="display: block; margin-bottom: 10px; font-weight: bold;">
                Rozmiar siatki: <span id="sliderValue">30</span> pól (max wymiar)
            </label>
            <input type="range" id="gridSizeSlider" min="10" max="52" value="30"
                style="width: 100%; height: 8px; border-radius: 5px; background: #ddd; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="color_count_auto" style="display: block; margin-bottom: 5px; font-weight: bold;">Liczba kolorów:</label>
            <input type="number" id="color_count_auto" value="8" min="2" max="20" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 200px;">
        </div>
    </div>

    <!-- Manual Mode Controls -->
    <div id="manualControls" style="display: none;">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
            <div>
                <label for="grid_width_manual" style="display: block; margin-bottom: 5px; font-weight: bold;">Szerokość siatki:</label>
                <input type="number" id="grid_width_manual" value="30" min="10" max="52" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
            </div>

            <div>
                <label for="grid_height_manual" style="display: block; margin-bottom: 5px; font-weight: bold;">Wysokość siatki:</label>
                <input type="number" id="grid_height_manual" value="30" min="10" max="52" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
            </div>

            <div>
                <label for="color_count_manual" style="display: block; margin-bottom: 5px; font-weight: bold;">Liczba kolorów:</label>
                <input type="number" id="color_count_manual" value="8" min="2" max="20" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
            </div>
        </div>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="grid_width" id="grid_width">
    <input type="hidden" name="grid_height" id="grid_height">
    <input type="hidden" name="color_count" id="color_count">

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

    // Canvas elements
    const canvasContainer = document.getElementById('canvasContainer');
    const canvas = document.getElementById('gridPreviewCanvas');
    const ctx = canvas.getContext('2d');
    const gridDimensions = document.getElementById('gridDimensions');

    // Controls
    const aspectAuto = document.getElementById('aspectAuto');
    const aspectManual = document.getElementById('aspectManual');
    const autoControls = document.getElementById('autoControls');
    const manualControls = document.getElementById('manualControls');
    const gridSizeSlider = document.getElementById('gridSizeSlider');
    const sliderValue = document.getElementById('sliderValue');

    let currentImage = null;
    let imageWidth = 0;
    let imageHeight = 0;

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

            // Load image for canvas
            const img = new Image();
            img.onload = function() {
                currentImage = img;
                imageWidth = img.width;
                imageHeight = img.height;
                canvasContainer.style.display = 'block';
                drawGridPreview();
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    // Toggle between auto and manual mode
    aspectAuto.addEventListener('change', function() {
        if (this.checked) {
            autoControls.style.display = 'block';
            manualControls.style.display = 'none';
            drawGridPreview();
        }
    });

    aspectManual.addEventListener('change', function() {
        if (this.checked) {
            autoControls.style.display = 'none';
            manualControls.style.display = 'block';
            drawGridPreview();
        }
    });

    // Slider change
    gridSizeSlider.addEventListener('input', function() {
        sliderValue.textContent = this.value;
        drawGridPreview();
    });

    // Manual inputs change
    document.getElementById('grid_width_manual').addEventListener('input', drawGridPreview);
    document.getElementById('grid_height_manual').addEventListener('input', drawGridPreview);

    function drawGridPreview() {
        if (!currentImage) return;

        let gridWidth, gridHeight;

        if (aspectAuto.checked) {
            // Auto mode - calculate proportions
            const maxSize = parseInt(gridSizeSlider.value);
            const aspectRatio = imageWidth / imageHeight;

            if (aspectRatio > 1) {
                // Landscape
                gridWidth = maxSize;
                gridHeight = Math.round(maxSize / aspectRatio);
            } else {
                // Portrait
                gridHeight = maxSize;
                gridWidth = Math.round(maxSize * aspectRatio);
            }
        } else {
            // Manual mode
            gridWidth = parseInt(document.getElementById('grid_width_manual').value) || 30;
            gridHeight = parseInt(document.getElementById('grid_height_manual').value) || 30;
        }

        // Set canvas size
        const maxCanvasSize = 600;
        const canvasAspect = gridWidth / gridHeight;
        if (canvasAspect > 1) {
            canvas.width = maxCanvasSize;
            canvas.height = maxCanvasSize / canvasAspect;
        } else {
            canvas.height = maxCanvasSize;
            canvas.width = maxCanvasSize * canvasAspect;
        }

        // Draw image
        ctx.drawImage(currentImage, 0, 0, canvas.width, canvas.height);

        // Draw grid overlay
        ctx.strokeStyle = '#00ff00';
        ctx.lineWidth = 1;

        const cellWidth = canvas.width / gridWidth;
        const cellHeight = canvas.height / gridHeight;

        // Vertical lines
        for (let i = 0; i <= gridWidth; i++) {
            ctx.beginPath();
            ctx.moveTo(i * cellWidth, 0);
            ctx.lineTo(i * cellWidth, canvas.height);
            ctx.stroke();
        }

        // Horizontal lines
        for (let i = 0; i <= gridHeight; i++) {
            ctx.beginPath();
            ctx.moveTo(0, i * cellHeight);
            ctx.lineTo(canvas.width, i * cellHeight);
            ctx.stroke();
        }

        // Update dimensions text
        gridDimensions.textContent = `Siatka: ${gridWidth} × ${gridHeight} pól`;
    }

    // Spinner przy wysyłaniu formularza
    uploadForm.addEventListener('submit', function(e) {
        // Set hidden form values based on mode
        let gridWidth, gridHeight, colorCount;

        if (aspectAuto.checked) {
            const maxSize = parseInt(gridSizeSlider.value);
            const aspectRatio = imageWidth / imageHeight;

            if (aspectRatio > 1) {
                gridWidth = maxSize;
                gridHeight = Math.round(maxSize / aspectRatio);
            } else {
                gridHeight = maxSize;
                gridWidth = Math.round(maxSize * aspectRatio);
            }
            colorCount = parseInt(document.getElementById('color_count_auto').value);
        } else {
            gridWidth = parseInt(document.getElementById('grid_width_manual').value);
            gridHeight = parseInt(document.getElementById('grid_height_manual').value);
            colorCount = parseInt(document.getElementById('color_count_manual').value);
        }

        document.getElementById('grid_width').value = gridWidth;
        document.getElementById('grid_height').value = gridHeight;
        document.getElementById('color_count').value = colorCount;

        submitBtn.style.display = 'none';
        loadingSpinner.style.display = 'block';
    });
</script>
@endpush
@endsection
