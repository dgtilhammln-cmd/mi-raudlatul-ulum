<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $user = $request->user();
        $file = $request->file('avatar');

        // Generate clean filename: foto-profil-nama-peserta.webp
        $slug = Str::slug($user->name, '-');
        $filename = "foto-profil-{$slug}-" . time() . ".webp";
        $path = "avatars/{$filename}";

        // Ensure directory exists
        $storagePath = storage_path('app/public/avatars');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Delete old avatar if exists
        if ($user->avatar_path && file_exists(storage_path('app/public/' . $user->avatar_path))) {
            unlink(storage_path('app/public/' . $user->avatar_path));
        }

        // Process image: resize to 300x300, convert to WebP
        $manager = new ImageManager(new Driver());
        $image = $manager->decode($file->getPathname());
        $image->cover(300, 300);
        $encoded = $image->encode(new WebpEncoder(85));
        file_put_contents(storage_path('app/public/' . $path), (string) $encoded);


        // Update user
        $user->update(['avatar_path' => $path]);

        // Create notification for admin (organizer) about the avatar update
        $organizers = \App\Models\User::where('role', 'organizer')->get();
        foreach ($organizers as $organizer) {
            UserNotification::create([
                'user_id'  => $organizer->id,
                'title'    => 'Foto Profil Diperbarui',
                'body'     => "Peserta {$user->name} telah memperbarui foto profil mereka.",
                'type'     => 'info',
                'icon'     => 'fa-camera',
            ]);
        }

        // Create notification for the participant too
        UserNotification::create([
            'user_id'  => $user->id,
            'title'    => 'Foto Profil Berhasil Diperbarui',
            'body'     => 'Foto profil Anda telah berhasil diperbarui dan sudah aktif.',
            'type'     => 'success',
            'icon'     => 'fa-check-circle',
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
