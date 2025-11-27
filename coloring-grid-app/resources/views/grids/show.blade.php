@extends('layouts.app')

@section('title', 'Siatka: ' . $grid->original_filename)

@section('content')
<a href="{{ route('grids.index') }}" style="color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block;">&larr; Powrót do listy</a>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; margin-bottom: 10px;">{{ $grid->original_filename }}</h2>
    <p><strong>Rozmiar:</strong> {{ $grid->grid_width }}x{{ $grid->grid_height }}</p>
    <p><strong>Liczba kolorów:</strong> {{ $grid->color_count }}</p>
    <p><strong>Utworzono:</strong> {{ $grid->created_at->format('d.m.Y H:i') }}</p>
</div>

<div style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 15px;">Paleta kolorów:</h3>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        @foreach($grid->color_palette as $index => $color)
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: rgb({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }}); border: 2px solid #333; border-radius: 5px;"></div>
                <small style="display: block; margin-top: 5px; font-weight: bold;">{{ $index + 1 }}</small>
                <small style="color: #666;">RGB({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }})</small>
            </div>
        @endforeach
    </div>
</div>

<div style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 15px;">Siatka do kolorowania:</h3>
    <div style="display: inline-block; border: 2px solid #000;">
        @foreach($grid->grid_data as $rowIndex => $row)
            <div style="display: flex;">
                @foreach($row as $colIndex => $colorIndex)
                    @php
                        $cellSize = max(15, min(30, 600 / max($grid->grid_width, $grid->grid_height)));
                        $fontSize = max(6, min(10, $cellSize * 0.5));
                    @endphp
                    <div style="
                        width: {{ $cellSize }}px;
                        height: {{ $cellSize }}px;
                        border: 1px solid #999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: {{ $fontSize }}px;
                        font-weight: bold;
                        background: white;
                    ">{{ $colorIndex + 1 }}</div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 30px;">
    <h3 style="margin-bottom: 15px;">Podgląd z kolorami:</h3>
    <div style="display: inline-block; border: 2px solid #000;">
        @foreach($grid->grid_data as $rowIndex => $row)
            <div style="display: flex;">
                @foreach($row as $colIndex => $colorIndex)
                    @php
                        $cellSize = max(15, min(30, 600 / max($grid->grid_width, $grid->grid_height)));
                        $color = $grid->color_palette[$colorIndex];
                    @endphp
                    <div style="
                        width: {{ $cellSize }}px;
                        height: {{ $cellSize }}px;
                        background: rgb({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }});
                        border: 1px solid rgba(0,0,0,0.1);
                    "></div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<div style="margin-top: 30px;">
    <button onclick="window.print()" class="btn">🖨️ Drukuj</button>
</div>

@push('styles')
<style>
    @media print {
        body {
            background: white;
            padding: 0;
        }
        .container {
            box-shadow: none;
        }
        a, button {
            display: none !important;
        }
    }
</style>
@endpush
@endsection
