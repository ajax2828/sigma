<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Models\Registration;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Jobs\SendSeminarTicketEmail;

class ProcessExcelImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $delaySeconds;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     * @param int $delaySeconds
     * @return void
     */
    public function __construct($filePath, $delaySeconds = 0)
    {
        $this->filePath = $filePath;
        $this->delaySeconds = $delaySeconds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fullPath = \Illuminate\Support\Facades\Storage::path($this->filePath);
        if (!file_exists($fullPath)) {
            \Log::error("Excel file not found: {$this->filePath} -> {$fullPath}");
            return;
        }
        $sheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $fullPath);
        $rows = $sheets[0] ?? [];
        $delayMinutes = $this->delaySeconds / 60;

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $name = trim($row[2] ?? '');
            $email = trim($row[1] ?? '');
            $phone = trim($row[5] ?? '');
            $institute = trim($row[7] ?? '');
            $userStatus = trim($row[9] ?? '');

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                \Log::warning("Invalid email skipped at row $index: $email");
                continue;
            }

            // Generate unique code and QR code
            $uniqueCode = Str::uuid()->toString();
            $fileName = 'qr_' . time() . '_' . $index . '.png';
            $filePath = public_path('storage/qr-codes/' . $fileName);

            if (!file_exists(public_path('storage/qr-codes'))) {
                mkdir(public_path('storage/qr-codes'), 0755, true);
            }

            $scanUrl = route('registrants.scan', $uniqueCode);
            QrCode::size(300)->format('png')->generate($scanUrl, $filePath);

            // Save registration data
            $registration = Registration::create([
                'name' => $name ?: 'Unknown',
                'email' => $email,
                'phone' => $phone ?: 'N/A',
                'study' => '-', // Default
                'Institute' => $institute ?: 'Unknown',
                'user_status' => $userStatus ?: 'Unknown',
                'unique_code' => $uniqueCode,
                'qr_code_path' => 'qr-codes/' . $fileName,
            ]);

            // Dispatch email job with 5-minute delay
            SendSeminarTicketEmail::dispatch($registration, null, 0)->delay(now()->addMinutes($delayMinutes));
            $delayMinutes += 5; // Increment delay for next email
        }

        // Clean up temporary file
        \Storage::disk('local')->delete($this->filePath);
    }
}
