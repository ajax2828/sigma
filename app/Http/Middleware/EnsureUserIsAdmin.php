<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gerbang panel admin.
 *
 * Login saja tidak cukup: begitu pendaftaran dibuka, siapa pun bisa punya akun.
 * Tanpa pemeriksaan ini, akun baru tersebut bisa langsung menghapus seluruh
 * post, member, dan achievement. Jadi "sudah login" dan "boleh kelola situs"
 * adalah dua hal yang berbeda.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Middleware ini dipasang bersama 'auth', tapi tetap dijaga sendiri
        // supaya tidak bocor kalau suatu saat dipakai tanpa auth.
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (! $user->isAdmin()) {
            // Menolak dengan jelas, bukan 403 kosong. Akunnya tetap sah, hanya
            // tidak punya wewenang, jadi arahkan balik ke landing page.
            return redirect()->route('landing')->withErrors([
                'auth' => 'Akun ini belum punya akses ke panel admin.',
            ]);
        }

        return $next($request);
    }
}
