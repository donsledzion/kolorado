@extends('layouts.app')

@section('title', 'Generator Siatek do Kolorowania')

@section('content')
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

    <div style="display: grid; grid-template-columns: 400px 1fr; gap: 30px; align-items: start;">

        <!-- Left Panel - Settings -->
        <div style="position: sticky; top: 20px;">

            <!-- Drag & Drop Zone (Compact) -->
            <div id="dropZone" style="
                margin-bottom: 20px;
                padding: 20px;
                border: 2px dashed #e5e7eb;
                border-radius: 12px;
                text-align: center;
                background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
                cursor: pointer;
                transition: all 0.3s ease;
            ">
                <input type="file" name="image" id="image" accept="image/*" required style="display: none;">

                <div id="dropContent">
                    <svg style="width: 40px; height: 40px; margin: 0 auto 10px; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">Przeciągnij obrazek lub</p>
                    <button type="button" id="browseBtn" class="modern-btn">Wybierz plik</button>
                    <p style="margin: 10px 0 0 0; color: #9ca3af; font-size: 12px;">JPG, PNG, GIF</p>
                </div>

                <div id="previewContainer" style="display: none;">
                    <img id="imagePreview" style="max-width: 100%; max-height: 120px; border-radius: 8px; margin-bottom: 10px;">
                    <p id="fileName" style="font-size: 12px; color: #6b7280; margin: 0 0 10px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></p>
                    <button type="button" id="changeBtn" class="modern-btn-secondary">Zmień</button>
                </div>
            </div>

            <!-- Settings Card -->
            <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">

                <!-- Mode Toggle -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #111827; font-size: 14px;">Tryb proporcji</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <label class="radio-card">
                            <input type="radio" name="aspect_ratio_mode" value="auto" id="aspectAuto" checked>
                            <span>Auto</span>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="aspect_ratio_mode" value="manual" id="aspectManual">
                            <span>Manual</span>
                        </label>
                    </div>
                </div>

                <!-- Auto Controls -->
                <div id="autoControls">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px;">
                            Rozmiar: <span id="sliderValue" style="color: #4f46e5; font-weight: 600;">30</span> pól
                        </label>
                        <input type="range" id="gridSizeSlider" min="10" max="52" value="30" class="modern-slider">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px;">Kolory</label>
                        <input type="number" id="color_count_auto" value="8" min="2" max="20" class="modern-input">
                    </div>
                </div>

                <!-- Manual Controls -->
                <div id="manualControls" style="display: none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px;">Szerokość</label>
                            <input type="number" id="grid_width_manual" value="30" min="10" max="52" class="modern-input">
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px;">Wysokość</label>
                            <input type="number" id="grid_height_manual" value="30" min="10" max="52" class="modern-input">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #374151; font-size: 14px;">Kolory</label>
                        <input type="number" id="color_count_manual" value="8" min="2" max="20" class="modern-input">
                    </div>
                </div>

                <!-- Numbering Type -->
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #111827; font-size: 14px;">Numeracja</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <label class="radio-card">
                            <input type="radio" name="numbering_type" value="numbers" checked>
                            <span>1,2,3...</span>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="numbering_type" value="chess">
                            <span>A1,B2...</span>
                        </label>
                    </div>
                    <small style="color: #9ca3af; font-size: 11px; display: block; margin-top: 6px;">Szachy max 52×52</small>
                </div>

                <!-- Hidden inputs -->
                <input type="hidden" name="grid_width" id="grid_width">
                <input type="hidden" name="grid_height" id="grid_height">
                <input type="hidden" name="color_count" id="color_count">

                <!-- Submit Button -->
                <button type="submit" class="modern-btn-primary" id="submitBtn">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Generuj Siatkę
                </button>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" style="display: none; text-align: center; padding: 20px;">
                    <div class="spinner"></div>
                    <p style="margin-top: 12px; color: #6b7280; font-size: 14px;">Generowanie...</p>
                </div>
            </div>

        </div>

        <!-- Right Panel - Preview -->
        <div id="previewPanel" style="position: sticky; top: 20px; background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 600; color: #111827;">Podgląd siatki</h3>

            <!-- Placeholder (shown initially) -->
            <div id="previewPlaceholder" style="text-align: center;">
                <div style="display: inline-block; padding: 60px; background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px; border: 2px dashed #e5e7eb;">
                    <svg style="width: 100px; height: 100px; color: #d1d5db; margin-bottom: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                    </svg>
                    <p style="margin: 0; color: #9ca3af; font-size: 15px; font-weight: 500;">Wczytaj obrazek, aby zobaczyć podgląd siatki</p>
                    <p style="margin: 8px 0 0 0; color: #d1d5db; font-size: 13px;">Przeciągnij plik lub kliknij "Wybierz plik"</p>
                </div>
            </div>

            <!-- Canvas (shown after image load) -->
            <div id="canvasContainer" style="display: none; text-align: center;">
                <canvas id="gridPreviewCanvas" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></canvas>
                <p id="gridDimensions" style="margin-top: 12px; color: #6b7280; font-weight: 500; font-size: 14px;"></p>
            </div>
        </div>

</form>

@push('styles')
<style>
    /* Modern Buttons */
    .modern-btn {
        padding: 8px 16px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modern-btn:hover {
        background: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .modern-btn-secondary {
        padding: 6px 14px;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modern-btn-secondary:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .modern-btn-primary {
        width: 100%;
        padding: 14px 20px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
    }
    .modern-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
    }
    .modern-btn-primary:active {
        transform: translateY(0);
    }

    /* Modern Input */
    .modern-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    .modern-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Modern Slider */
    .modern-slider {
        width: 100%;
        height: 6px;
        border-radius: 10px;
        background: #e5e7eb;
        outline: none;
        -webkit-appearance: none;
    }
    .modern-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #4f46e5;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        transition: all 0.2s;
    }
    .modern-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 3px 12px rgba(79, 70, 229, 0.5);
    }
    .modern-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #4f46e5;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    }

    /* Radio Card */
    .radio-card {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }
    .radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    .radio-card span {
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
    }
    .radio-card:hover {
        border-color: #4f46e5;
        background: #f9fafb;
    }
    .radio-card input[type="radio"]:checked + span {
        color: #4f46e5;
    }
    .radio-card:has(input[type="radio"]:checked) {
        border-color: #4f46e5;
        background: #eef2ff;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Spinner */
    .spinner {
        display: inline-block;
        width: 40px;
        height: 40px;
        border: 4px solid #f3f4f6;
        border-top: 4px solid #4f46e5;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #dropZone.dragover {
        border-color: #4f46e5 !important;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%) !important;
        transform: scale(1.02);
    }

    /* Responsive */
    @media (max-width: 900px) {
        form > div {
            grid-template-columns: 1fr !important;
        }
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
    const previewPlaceholder = document.getElementById('previewPlaceholder');
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

                // Hide placeholder, show canvas
                previewPlaceholder.style.display = 'none';
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
