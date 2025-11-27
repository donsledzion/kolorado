@extends('layouts.app')

@section('title', 'Moje Siatki')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('grids.create') }}" class="btn">+ Utwórz Nową Siatkę</a>
</div>

@if($grids->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        @foreach($grids as $grid)
        <div style="border: 1px solid #ddd; border-radius: 5px; padding: 15px; background: #fafafa;">
            <h3 style="font-size: 16px; margin-bottom: 10px;">{{ $grid->original_filename }}</h3>
            <p style="color: #666; font-size: 14px;">Rozmiar: {{ $grid->grid_width }}x{{ $grid->grid_height }}</p>
            <p style="color: #666; font-size: 14px;">Kolory: {{ $grid->color_count }}</p>
            <p style="color: #999; font-size: 12px; margin-top: 10px;">{{ $grid->created_at->diffForHumans() }}</p>
            <a href="{{ route('grids.show', $grid) }}" class="btn" style="margin-top: 10px; display: block; text-align: center;">Zobacz</a>
        </div>
        @endforeach
    </div>

    <div style="margin-top: 30px;">
        {{ $grids->links() }}
    </div>
@else
    <p style="color: #666; text-align: center; padding: 40px;">Nie masz jeszcze żadnych siatek. <a href="{{ route('grids.create') }}">Utwórz pierwszą!</a></p>
@endif
@endsection
