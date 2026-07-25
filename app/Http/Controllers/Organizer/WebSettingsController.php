<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\WebSetting;
use App\Models\PartnerLogo;
use App\Models\InstagramFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class WebSettingsController extends Controller
{
    // --- Logos (Partners & Sponsors) ---
    public function logos()
    {
        $partners = PartnerLogo::partners()->get();
        $sponsors = PartnerLogo::sponsors()->get();
        $siteLogo = WebSetting::get('site_logo');
        return view('organizer.web-settings.logos', compact('partners', 'sponsors', 'siteLogo'));
    }

    public function updateSiteLogo(Request $request)
    {
        $request->validate([
            'site_logo' => 'required|file|mimes:png,jpg,jpeg,ico|max:2048',
        ]);

        $file = $request->file('site_logo');
        if (!file_exists(public_path('storage/settings'))) {
            mkdir(public_path('storage/settings'), 0755, true);
        }
        
        // Save without converting to webp because favicon must be supported natively (png/ico)
        $filename = 'site-logo-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('settings', $filename, 'public');

        $oldLogo = WebSetting::get('site_logo');
        if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
            Storage::disk('public')->delete($oldLogo);
        }

        WebSetting::set('site_logo', $path);

        return back()->with('success', 'Logo utama website berhasil diperbarui.');
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:partner,sponsor',
            'url' => 'nullable|url',
            'image' => 'required|image|max:2048'
        ]);

        $path = $this->uploadAndCompress($request->file('image'), 'logos');

        PartnerLogo::create([
            'name' => $request->name,
            'type' => $request->type,
            'url' => $request->url,
            'image_path' => $path,
            'order' => PartnerLogo::where('type', $request->type)->count() + 1
        ]);

        return back()->with('success', 'Logo berhasil ditambahkan.');
    }

    public function destroyLogo(PartnerLogo $logo)
    {
        if (Storage::disk('public')->exists($logo->image_path)) {
            Storage::disk('public')->delete($logo->image_path);
        }
        $logo->delete();
        return back()->with('success', 'Logo dihapus.');
    }

    // --- Instagram Feeds ---
    public function instagram()
    {
        $feeds = InstagramFeed::active()->get();
        return view('organizer.web-settings.instagram', compact('feeds'));
    }

    public function storeInstagram(Request $request)
    {
        $request->validate([
            'link_url' => 'required|url',
            'image' => 'required|image|max:3072'
        ]);

        $path = $this->uploadAndCompress($request->file('image'), 'instagram', 800, 800);

        InstagramFeed::create([
            'link_url' => $request->link_url,
            'image_path' => $path,
            'order' => InstagramFeed::count() + 1
        ]);

        return back()->with('success', 'Feed Instagram ditambahkan.');
    }

    public function destroyInstagram(InstagramFeed $feed)
    {
        if (Storage::disk('public')->exists($feed->image_path)) {
            Storage::disk('public')->delete($feed->image_path);
        }
        $feed->delete();
        return back()->with('success', 'Feed dihapus.');
    }

    // --- Footer Settings ---
    public function footer()
    {
        $footer = WebSetting::get('footer', [
            'description' => 'Musabaqah Tarikh Islam adalah kompetisi sejarah peradaban Islam tingkat nasional.',
            'phone' => '+62 812-3456-7890',
            'email' => 'panitia@musabaqahtarikhislam.com',
            'address' => 'Jl. A. Yani No.117, Surabaya',
            'socials' => [
                'instagram' => '',
                'youtube' => '',
                'tiktok' => ''
            ]
        ]);
        return view('organizer.web-settings.footer', compact('footer'));
    }

    public function updateFooter(Request $request)
    {
        WebSetting::set('footer', $request->all());
        return back()->with('success', 'Footer berhasil diperbarui.');
    }

    // --- Hero Section Settings ---
    public function hero()
    {
        $hero = WebSetting::get('hero', [
            'badge'      => 'Kompetisi Sejarah Islam Tingkat Nasional',
            'title_line1'=> 'Uji Wawasan,',
            'title_line2'=> 'Raih Prestasi,',
            'title_line3'=> '& Jadilah Juara',
            'description'=> 'Bangun prestasimu di kompetisi sejarah peradaban Islam tingkat nasional — tanpa batas domisili, usia, maupun institusi.',
            'cta_text'   => 'Daftarkan Dirimu!',
            'stat1_value'=> '5.000',
            'stat1_suffix'=> '+',
            'stat1_label'=> 'Total Peserta',
            'stat2_value'=> '50',
            'stat2_suffix'=> '+',
            'stat2_label'=> 'Institusi Pendidikan',
            'stat3_value'=> '100',
            'stat3_suffix'=> '%',
            'stat3_label'=> 'Online & Realtime',
        ]);
        return view('organizer.web-settings.hero', compact('hero'));
    }

    public function updateHero(Request $request)
    {
        $request->validate([
            'badge'       => 'required|string|max:120',
            'badge_icon'  => 'nullable|string|max:50',
            'title_line1' => 'required|string|max:100',
            'title_line2' => 'required|string|max:100',
            'title_line3' => 'required|string|max:100',
            'description' => 'required|string|max:400',
            'cta_text'    => 'required|string|max:60',
            'cta_link'    => 'nullable|string|max:255',
        ]);
        WebSetting::set('hero', $request->all());
        return back()->with('success', 'Hero Section berhasil diperbarui.');
    }

    // --- Helper for WebP Compression ---
    private function uploadAndCompress($file, $folder, $maxWidth = 600, $maxHeight = null)
    {
        if (!file_exists(public_path('storage/' . $folder))) {
            mkdir(public_path('storage/' . $folder), 0755, true);
        }

        $filename = uniqid() . '-' . time() . '.webp';
        $fullPath = public_path('storage/' . $folder . '/' . $filename);

        $manager = new ImageManager(new Driver());
        $img = $manager->decode($file->getRealPath());
        
        // Resize proportionally with cover logic or scale
        if ($maxHeight) {
            $img->cover($maxWidth, $maxHeight);
        } else {
            $img->scale(width: $maxWidth);
        }

        // Save as WebP
        $encoded = $img->encodeUsingFileExtension('webp', 85);
        file_put_contents($fullPath, $encoded->toString());

        return $folder . '/' . $filename;
    }
}
