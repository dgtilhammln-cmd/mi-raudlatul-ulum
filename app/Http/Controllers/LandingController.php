<?php

namespace App\Http\Controllers;

use App\Models\LandingImage;
use App\Models\Event;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $leftImages  = LandingImage::where('column_position', 'left')->orderBy('order')->get();
        $rightImages = LandingImage::where('column_position', 'right')->orderBy('order')->get();

        // New features data
        $partners = \App\Models\PartnerLogo::partners()->get();
        $sponsors = \App\Models\PartnerLogo::sponsors()->get();
        $instagramFeeds = \App\Models\InstagramFeed::active()->get();
        $footer = \App\Models\WebSetting::get('footer', [
            'description' => 'Musabaqah Tarikh Islam adalah kompetisi sejarah peradaban Islam tingkat nasional.',
            'phone' => '+62 812-3456-7890',
            'email' => 'panitia@musabaqahtarikhislam.com',
            'address' => 'Jl. A. Yani No.117, Surabaya',
            'socials' => ['instagram' => '', 'youtube' => '', 'tiktok' => '']
        ]);

        // Hero section settings (editable by organizer)
        $hero = \App\Models\WebSetting::get('hero', [
            'badge'       => 'Kompetisi Sejarah Islam Tingkat Nasional',
            'title_line1' => 'Uji Wawasan,',
            'title_line2' => 'Raih Prestasi,',
            'title_line3' => '& Jadilah Juara',
            'description' => 'Bangun prestasimu di kompetisi sejarah peradaban Islam tingkat nasional — tanpa batas domisili, usia, maupun institusi.',
            'cta_text'    => 'Daftarkan Dirimu!',
            'stat1_value' => '5.000',
            'stat1_suffix'=> '+',
            'stat1_label' => 'Total Peserta',
            'stat2_value' => '50',
            'stat2_suffix'=> '+',
            'stat2_label' => 'Institusi Pendidikan',
            'stat3_value' => '100',
            'stat3_suffix'=> '%',
            'stat3_label' => 'Online & Realtime',
        ]);

        // Active events with visible leaderboard for homepage display
        $featuredEvents = Event::where('leaderboard_visible', true)
            ->withCount('participants')
            ->latest()
            ->take(3)
            ->get();

        return view('landing', compact('leftImages', 'rightImages', 'featuredEvents', 'footer', 'partners', 'sponsors', 'instagramFeeds', 'hero'));
    }
}
