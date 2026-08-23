<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
     // Tampilkan halaman dengan QR dari teks statis
    public function show()
    {
        $text = 'https://khaerulummamaf.bitbytezone.my.id'; // ganti dengan data dinamis kalau perlu
        $qrCode = QrCode::size(250)->generate($text);

        return view('qrcode', ['qrCode' => $qrCode]);
    }

    // Endpoint untuk generate QR dari input (POST atau GET)
    public function generate(Request $request)
    {
        $text = $request->input('text', 'default-text');

        return response(QrCode::size(300)->generate($text))
               ->header('Content-Type', 'image/svg+xml');
    }
}
