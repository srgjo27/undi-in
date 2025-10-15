<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Display the email verification notice.
     */
    public function notice()
    {
        if (!Auth::check() && !session('unverified_user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('pages.auth.verify-email');
    }

    /**
     * Fulfill the email verification request.
     */
    public function verify(Request $request, $id, $hash)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        if ($user->hasVerifiedEmail()) {
            if (Auth::check() && Auth::id() === $user->id) {
                return redirect()->intended($this->getRedirectPath($user))->with('success', 'Email sudah terverifikasi sebelumnya.');
            }

            return redirect()->route('login')->with('success', 'Email sudah terverifikasi. Silakan login untuk melanjutkan.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        session()->forget('unverified_user_id');

        Auth::login($user);

        return redirect()->intended($this->getRedirectPath($user))->with('success', 'Email berhasil diverifikasi! Selamat datang!');
    }

    /**
     * Send a new email verification notification.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();
        if (!$user && session('unverified_user_id')) {
            $user = \App\Models\User::find(session('unverified_user_id'));
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->hasVerifiedEmail()) {
            if (Auth::check()) {
                return redirect()->intended($this->getRedirectPath($user))->with('info', 'Email sudah terverifikasi.');
            } else {
                return redirect()->route('login')->with('info', 'Email sudah terverifikasi. Silakan login.');
            }
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }

    /**
     * Get redirect path based on user role.
     */
    private function getRedirectPath($user)
    {
        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'seller':
                return route('seller.dashboard');
            case 'buyer':
                return route('buyer.dashboard');
            default:
                return route('buyer.dashboard');
        }
    }
}
