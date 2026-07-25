<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // --- PESERTA ---
    public function showParticipantLogin()
    {
        return view('auth.login-peserta');
    }

    public function loginParticipant(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|string',
            'access_code' => 'required|string',
        ]);

        $credentials = [
            'participant_id' => $request->participant_id,
            'password' => $request->access_code,
            'role' => 'participant',
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $isFirstLogin = is_null($user->last_login_at);
            $user->update(['last_login_at' => now()]);

            // Send welcome notification only on first login
            if ($isFirstLogin) {
                UserNotification::send(
                    userId:      $user->id,
                    type:        'info',
                    icon:        'fas fa-hand-wave',
                    title:       '👋 Selamat Bergabung di MTI!',
                    body:        'Akun Anda berhasil diaktifkan. Cek menu Dashboard untuk melihat jadwal babak ujian Anda. Semoga sukses!',
                    actionUrl:   route('peserta.dashboard'),
                    actionLabel: 'Lihat Jadwal Ujian'
                );
            }

            return redirect()->intended(route('peserta.dashboard'));
        }

        return back()->withErrors(['login' => 'ID Peserta atau Kode Akses salah.'])->withInput();
    }

    // --- ADMIN / ORGANIZER ---
    public function showAdminLogin()
    {
        return view('auth.login-admin');
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'organizer',
            'is_active' => true,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->update(['last_login_at' => now()]);
            return redirect()->intended(route('organizer.dashboard'));
        }

        return back()->withErrors(['login' => 'Email atau password salah.'])->withInput();
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        $user = Auth::user();
        $role = $user ? $user->role : null;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke login masing-masing
        if ($role === 'organizer') {
            return redirect()->route('admin.login');
        }
        
        return redirect()->route('login');
    }
}
