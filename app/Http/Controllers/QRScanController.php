<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QRScanController extends Controller
{
    public function show()
    {
        return view('scan');
    }

    public function showBehind()
    {
        return view('scanBehind');
    }

    // validasi QR Code untuk registrasi
    public function scan(Request $request)
    {
        $request->validate([
            'unique_code' => 'required|string',
        ]);

        // Ekstrak UUID dari input (jika berupa URL)
        $unique_code = basename($request->unique_code);

        $registrant = Registration::where('unique_code', $unique_code)->first();

        if (!$registrant) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'QR tidak valid.',
                'nama' => $registrant->name,
                'institusi' => $registrant->institute,
                'jabatan' => $registrant->user_status,
                'studi' => $registrant->study,
            ], 404);
        }

        if ($registrant->is_scanned) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'QR ini sudah digunakan pada ' . $registrant->scanned_at->format('d-m-Y H:i:s'),
                'nama' => $registrant->name,
                'institusi' => $registrant->institute,
                'jabatan' => $registrant->user_status,
                'studi' => $registrant->study,
            ], 403);
        }

        // Tandai sebagai sudah discan
        $registrant->update([
            'is_scanned' => true,
            'scanned_at' => now(),
        ]);

        return response()->json([
            'status' => 'valid',
            'message' => 'QR valid dan pertama kali digunakan.',
            'nama' => $registrant->name,
            'institusi' => $registrant->institute,
            'jabatan' => $registrant->user_status,
            'studi' => $registrant->study,
        ]);
    }
}
