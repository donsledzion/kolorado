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

<form action="{{ route('grids.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="margin-bottom: 20px;">
        <label for="image" style="display: block; margin-bottom: 5px; font-weight: bold;">Wybierz obrazek:</label>
        <input type="file" name="image" id="image" accept="image/*" required style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        <small style="color: #666;">Format: JPG, PNG, GIF (max 10MB)</small>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px;">
        <div>
            <label for="grid_width" style="display: block; margin-bottom: 5px; font-weight: bold;">Szerokość siatki:</label>
            <input type="number" name="grid_width" id="grid_width" value="30" min="10" max="100" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>

        <div>
            <label for="grid_height" style="display: block; margin-bottom: 5px; font-weight: bold;">Wysokość siatki:</label>
            <input type="number" name="grid_height" id="grid_height" value="30" min="10" max="100" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>

        <div>
            <label for="color_count" style="display: block; margin-bottom: 5px; font-weight: bold;">Liczba kolorów:</label>
            <input type="number" name="color_count" id="color_count" value="8" min="2" max="20" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
        </div>
    </div>

    <button type="submit" class="btn">Generuj Siatkę</button>
</form>

@push('scripts')
<script>
    // Preview obrazka po wybraniu
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('image-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'image-preview';
                    preview.style.maxWidth = '300px';
                    preview.style.marginTop = '10px';
                    preview.style.borderRadius = '5px';
                    document.querySelector('form').insertBefore(preview, document.querySelector('button'));
                }
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
