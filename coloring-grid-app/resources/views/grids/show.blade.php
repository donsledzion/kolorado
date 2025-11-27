@extends('layouts.app')

@section('title', 'Siatka: ' . $grid->original_filename)

@section('content')
<a href="{{ route('grids.index') }}" style="color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block;">&larr; Powrót do listy</a>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 18px; margin-bottom: 10px;">{{ $grid->original_filename }}</h2>
    <p><strong>Rozmiar:</strong> {{ $grid->grid_width }}x{{ $grid->grid_height }}</p>
    <p><strong>Liczba kolorów:</strong> {{ $grid->color_count }}</p>
    <p><strong>Typ numeracji:</strong> {{ $grid->numbering_type === 'chess' ? 'Współrzędne szachowe' : 'Numerki w polach' }}</p>
    <p><strong>Utworzono:</strong> {{ $grid->created_at->format('d.m.Y H:i') }}</p>
</div>

<div style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 15px;">Paleta kolorów:</h3>
    @if($grid->numbering_type === 'chess')
        @php
            // Funkcja do grupowania zakresów
            function groupCoordinates($coords) {
                if (empty($coords)) return '';

                // Grupuj po literach
                $byLetter = [];
                foreach($coords as $coord) {
                    preg_match('/([A-Za-z]+)(\d+)/', $coord, $matches);
                    $letter = $matches[1];
                    $number = (int)$matches[2];
                    if (!isset($byLetter[$letter])) {
                        $byLetter[$letter] = [];
                    }
                    $byLetter[$letter][] = $number;
                }

                // Sortuj i grupuj zakresy
                $result = [];
                foreach($byLetter as $letter => $numbers) {
                    sort($numbers);
                    $ranges = [];
                    $start = $numbers[0];
                    $prev = $numbers[0];

                    for($i = 1; $i < count($numbers); $i++) {
                        if ($numbers[$i] == $prev + 1) {
                            $prev = $numbers[$i];
                        } else {
                            if ($start == $prev) {
                                $ranges[] = $letter . $start;
                            } else {
                                $ranges[] = $letter . $start . '-' . $prev;
                            }
                            $start = $numbers[$i];
                            $prev = $numbers[$i];
                        }
                    }

                    // Dodaj ostatni zakres
                    if ($start == $prev) {
                        $ranges[] = $letter . $start;
                    } else {
                        $ranges[] = $letter . $start . '-' . $prev;
                    }

                    $result[] = implode(', ', $ranges);
                }

                return implode(', ', $result);
            }

            // Przygotuj listę współrzędnych dla każdego koloru
            $colorCoordinates = [];
            foreach($grid->grid_data as $rowIndex => $row) {
                foreach($row as $colIndex => $colorIndex) {
                    $letter = $colIndex < 26 ? chr(65 + $colIndex) : chr(97 + ($colIndex - 26));
                    $coord = $letter . ($rowIndex + 1);
                    if (!isset($colorCoordinates[$colorIndex])) {
                        $colorCoordinates[$colorIndex] = [];
                    }
                    $colorCoordinates[$colorIndex][] = $coord;
                }
            }
        @endphp

        @foreach($grid->color_palette as $index => $color)
            @php
                // Sprawdź czy to biały kolor (tolerance dla prawie białego)
                $isWhite = $color[0] > 240 && $color[1] > 240 && $color[2] > 240;
            @endphp
            @if(!$isWhite)
                <div style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #fafafa;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <div style="width: 40px; height: 40px; background: rgb({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }}); border: 2px solid #333; border-radius: 5px; flex-shrink: 0;"></div>
                        <div>
                            <strong style="display: block;">Kolor {{ $index + 1 }}</strong>
                            <small style="color: #666;">RGB({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }})</small>
                        </div>
                    </div>
                    <div style="font-size: 14px; line-height: 1.6;">
                        <strong>Pola:</strong> {{ groupCoordinates($colorCoordinates[$index] ?? []) }}
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach($grid->color_palette as $index => $color)
                @php
                    // Sprawdź czy to biały kolor
                    $isWhite = $color[0] > 240 && $color[1] > 240 && $color[2] > 240;
                @endphp
                @if(!$isWhite)
                    <div style="text-align: center;">
                        <div style="width: 60px; height: 60px; background: rgb({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }}); border: 2px solid #333; border-radius: 5px;"></div>
                        <small style="display: block; margin-top: 5px; font-weight: bold;">{{ $index + 1 }}</small>
                        <small style="color: #666;">RGB({{ $color[0] }}, {{ $color[1] }}, {{ $color[2] }})</small>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<div style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 15px;">Siatka do kolorowania:</h3>
    @if($grid->numbering_type === 'chess')
        <div style="display: inline-block;">
            <div style="display: flex;">
                <div style="width: 30px; height: 30px;"></div>
                @for($col = 0; $col < $grid->grid_width; $col++)
                    @php
                        $letter = $col < 26 ? chr(65 + $col) : chr(97 + ($col - 26));
                    @endphp
                    <div style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">{{ $letter }}</div>
                @endfor
            </div>
            @foreach($grid->grid_data as $rowIndex => $row)
                <div style="display: flex;">
                    <div style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">{{ $rowIndex + 1 }}</div>
                    @foreach($row as $colIndex => $colorIndex)
                        @php
                            $cellSize = 30;
                        @endphp
                        <div style="
                            width: {{ $cellSize }}px;
                            height: {{ $cellSize }}px;
                            border: 1px solid #999;
                            background: white;
                        "></div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @else
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
    @endif
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
    /* Wymuś drukowanie kolorów tła */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

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

        /* Upewnij się że kolory są drukowane */
        div[style*="background"] {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    @page {
        margin: 1cm;
    }
</style>
@endpush
@endsection
