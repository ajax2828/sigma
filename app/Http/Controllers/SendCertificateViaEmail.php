<?php

namespace App\Http\Controllers;

use App\Models\Registrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Registration;
use App\Mail\SeminarTicketMail;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendSeminarCertificate;

class SendCertificateViaEmail extends Controller
{
    public function show()
    {
        return view('sendCertificate');
    }

    public function send()
    {
        $registrants = Registration::where('is_scanned', true)->get();

        if ($registrants->isEmpty()) {
            return back()->with('info', 'Tidak ada peserta yang sudah discan untuk dikirim sertifikat.');
        }

        $delayMinutes = 3; // jeda antar email
        $index = 1;

        foreach ($registrants as $registrant) {
            $filePath = public_path("storage/sertifikat/{$registrant->name}.pdf");

            if (file_exists($filePath)) {
                $delay = now()->addMinutes($delayMinutes * $index);

                SendSeminarCertificate::dispatch($registrant, $filePath)->delay($delay);

                \Log::info("Job email untuk {$registrant->email} dijadwalkan pada {$delay}");

                $index++;
            } else {
                \Log::warning("File sertifikat tidak ditemukan untuk {$registrant->email} di path: {$filePath}");
            }
        }

        return back()->with('success', 'Berhasil! Sertifikat akan dikirim bertahap setiap 3 menit per peserta.');
    }




    public function email()
    {
        return view('emails.sertifikatSeminar');
    }

    public function tes()
    {

        $registrants = Registration::where('is_scanned', true)->get();

        $files = [];

        foreach ($registrants as $registrant) {
            $filePath = public_path('storage/sertifikat/' . $registrant->name . '.pdf');

            if (file_exists($filePath)) {
                $files[$registrant->id] = $registrant->name . '.pdf';
            } else {
                $files[$registrant->id] = null;
            }
        }

        return view('hadir', [
            'data' => $registrants,
            'files' => $files,
        ]);
    }
}
