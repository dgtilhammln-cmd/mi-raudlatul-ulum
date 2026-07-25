<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\LandingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingImageController extends Controller
{
    public function index()
    {
        $images = LandingImage::orderBy('column_position')->orderBy('order')->get();
        return view('organizer.landing-images.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'column_position' => 'required|in:left,right',
        ]);

        $file = $request->file('image');
        $filename = uniqid('landing_') . '.webp';
        
        // Buat folder jika belum ada
        if (!Storage::disk('public')->exists('landing')) {
            Storage::disk('public')->makeDirectory('landing');
        }

        // Convert ke webp menggunakan Intervention Image
        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->decode($file->getPathname());
        $encoded = $image->encodeUsingFileExtension('webp', 80);
        
        Storage::disk('public')->put('landing/' . $filename, $encoded->toString());

        $path = 'landing/' . $filename;

        LandingImage::create([
            'image_path' => $path,
            'column_position' => $request->column_position,
            'order' => LandingImage::where('column_position', $request->column_position)->max('order') + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Gambar berhasil diunggah!');
    }

    public function destroy(LandingImage $landingImage)
    {
        Storage::disk('public')->delete($landingImage->image_path);
        $landingImage->delete();

        return back()->with('success', 'Gambar berhasil dihapus!');
    }
}
