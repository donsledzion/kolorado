<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ColoringGrid;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GridController extends Controller
{
    public function index()
    {
        $grids = ColoringGrid::latest()->paginate(12);
        return view('grids.index', compact('grids'));
    }

    public function create()
    {
        return view('grids.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'grid_width' => 'nullable|integer|min:10|max:100',
            'grid_height' => 'nullable|integer|min:10|max:100',
            'color_count' => 'nullable|integer|min:3|max:20',
        ]);

        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();
        $path = $image->storeAs('originals', $filename, 'public');

        $grid = ColoringGrid::create([
            'original_filename' => $image->getClientOriginalName(),
            'original_image_path' => $path,
            'grid_width' => $request->input('grid_width', 30),
            'grid_height' => $request->input('grid_height', 30),
            'color_count' => $request->input('color_count', 8),
        ]);

        $this->processImage($grid);

        return redirect()->route('grids.show', $grid);
    }

    public function show(ColoringGrid $grid)
    {
        return view('grids.show', compact('grid'));
    }

    private function processImage(ColoringGrid $grid)
    {
        $manager = new ImageManager(new Driver());
        $imagePath = storage_path('app/public/' . $grid->original_image_path);
        $image = $manager->read($imagePath);

        // Resize do rozmiaru siatki
        $image->resize($grid->grid_width, $grid->grid_height);

        // Pobierz wszystkie piksele
        $pixels = [];
        for ($y = 0; $y < $grid->grid_height; $y++) {
            for ($x = 0; $x < $grid->grid_width; $x++) {
                $color = $image->pickColor($x, $y);
                $pixels[] = [
                    $color->red()->toInt(),
                    $color->green()->toInt(),
                    $color->blue()->toInt()
                ];
            }
        }

        // K-means clustering dla redukcji kolorów
        $palette = $this->kMeansClustering($pixels, $grid->color_count);

        // Przypisz każdemu pikselowi najbliższy kolor z palety
        $gridData = [];
        for ($y = 0; $y < $grid->grid_height; $y++) {
            $row = [];
            for ($x = 0; $x < $grid->grid_width; $x++) {
                $color = $image->pickColor($x, $y);
                $rgb = [
                    $color->red()->toInt(),
                    $color->green()->toInt(),
                    $color->blue()->toInt()
                ];
                $colorIndex = $this->findClosestColor($rgb, $palette);
                $row[] = $colorIndex;
            }
            $gridData[] = $row;
        }

        $grid->update([
            'grid_data' => $gridData,
            'color_palette' => $palette,
        ]);
    }

    private function kMeansClustering(array $pixels, int $k, int $maxIterations = 20)
    {
        // Inicjalizacja centroidów losowo
        $centroids = array_slice($pixels, 0, $k);

        for ($i = 0; $i < $maxIterations; $i++) {
            $clusters = array_fill(0, $k, []);

            // Przypisz każdy piksel do najbliższego centroidu
            foreach ($pixels as $pixel) {
                $closest = $this->findClosestColor($pixel, $centroids);
                $clusters[$closest][] = $pixel;
            }

            // Oblicz nowe centroidy
            $newCentroids = [];
            foreach ($clusters as $cluster) {
                if (empty($cluster)) {
                    $newCentroids[] = $centroids[count($newCentroids)];
                    continue;
                }
                $r = array_sum(array_column($cluster, 0)) / count($cluster);
                $g = array_sum(array_column($cluster, 1)) / count($cluster);
                $b = array_sum(array_column($cluster, 2)) / count($cluster);
                $newCentroids[] = [(int)$r, (int)$g, (int)$b];
            }

            $centroids = $newCentroids;
        }

        return $centroids;
    }

    private function findClosestColor(array $color, array $palette)
    {
        $minDistance = PHP_INT_MAX;
        $closestIndex = 0;

        foreach ($palette as $index => $paletteColor) {
            $distance = sqrt(
                pow($color[0] - $paletteColor[0], 2) +
                pow($color[1] - $paletteColor[1], 2) +
                pow($color[2] - $paletteColor[2], 2)
            );

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestIndex = $index;
            }
        }

        return $closestIndex;
    }
}
