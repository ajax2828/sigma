<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Jobs\ProcessExcelImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendSeminarTicketEmail;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationController extends Controller
{
    public function show()
    {
        return view('register');
    }

    public function regist(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'study' => 'nullable|string|max:255',
                'Institute' => 'required|string|max:255',
                'user_status' => 'nullable|string|max:50',
            ]);

            // Generate unique code
            $uniqueCode = Str::uuid()->toString();
            $fileName = 'qr_' . time() . '.png';
            $filePath = public_path('storage/qr-codes/' . $fileName);

            // Ensure the directory exists
            if (!file_exists(public_path('storage/qr-codes'))) {
                mkdir(public_path('storage/qr-codes'), 0755, true);
            }

            // Generate QR code
            $scanUrl = route('registrants.scan', $uniqueCode);
            QrCode::size(300)->format('png')->generate($scanUrl, $filePath);

            // Save registration data
            $registration = Registration::create([
                ...$validated,
                'unique_code' => $uniqueCode,
                'qr_code_path' => 'qr-codes/' . $fileName,
            ]);

            // Dispatch email job (optional for single registration)
            SendSeminarTicketEmail::dispatch($registration, null, 0);

            // Return JSON for AJAX or redirect for non-AJAX
            if ($request->ajax()) {
                return response()->json(['success' => 'Pendaftaran berhasil! Email akan dikirim dalam 5 menit.']);
            }
            return redirect()->back()->with('success', 'Pendaftaran berhasil! Email akan dikirim dalam 5 menit.');
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Pendaftaran gagal! ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Pendaftaran gagal! ' . $e->getMessage());
        }
    }

    // public function store(Request $request){
    //     dd($request->all());

    //     $data = $request->validate([
    //         'nama' => 'required',
    //         'tempat_lahir' => 'nullable',
    //         'tanggal_lahir' => 'nullable|date',
    //         'email' => 'required|email',
    //         'whatsapp' => 'required',
    //         'institusi' => 'required',
    //         'jurusan' => 'nullable',
    //     ]);

    //     // Generate QR Code token unik
    //     $qrToken = Str::uuid()->toString();

    //     $data['qr_code'] = $qrToken;

    //     // Simpan ke database
    //     $registration = Registration::create($data);

    //     // Generate QR image (optional: simpan sebagai file)
    //     $qrImage = QrCode::format('png')->size(300)->generate(route('qr.validate', $qrToken));
    //     Storage::put("qr-codes/{$qrToken}.png", $qrImage);

    //     // Kirim email dengan QR (opsional)
    //     // Mail::to($registration->email)->send(new QrCodeMail($qrImage));

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Registrasi berhasil',
    //         'qr_code_url' => route('qr.validate', $qrToken),
    //     ]);
    // }

    public function scan($code)
    {
        $registrant = Registration::where('unique_code', $code)->first();
        if (!$registrant) {
            abort(404, 'QR Code tidak valid');
        }
        return view('scanResult', compact('registrant'));
    }

    public function importShow()
    {
        return view('importData');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        // Store the file temporarily
        $filePath = $request->file('excel')->store('temp');
        // Dispatch import job to process in background
        ProcessExcelImport::dispatch($filePath);

        // Return immediate success response
        return response()->json(['success' => 'Import data telah dimulai di background. Email akan dikirim secara bertahap dalam 5 menit per peserta.']);
    }

    public function checkEmailStatus()
    {
        // Check if there are pending jobs in the queue (simplified example)
        $pendingJobs = DB::table('jobs')->where('reserved_at', null)->exists();

        // Alternatively, check registration status (e.g., if email was sent)
        $latestRegistration = Registration::latest()->first();
        $emailSent = $latestRegistration ? Storage::exists($latestRegistration->qr_code_path) && !DB::table('failed_jobs')->where('payload', 'like', '%' . $latestRegistration->email . '%')->exists() : false;

        return response()->json(['sent' => $emailSent && !$pendingJobs]);
    }
}
