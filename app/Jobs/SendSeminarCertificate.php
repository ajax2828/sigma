<?php

namespace App\Jobs;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CertificateMail;
use Illuminate\Support\Facades\Mail;

class SendSeminarCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $registration;
    protected $filePath;
    /**
     * Create a new job instance.
     * @param Registration $registration
     * @return void
     */
    public function __construct(Registration $registration, $filePath)
    {
        $this->registration = $registration;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle()
    {
        try {
            if (!file_exists($this->filePath)) {
                \Log::warning("Sertifikat tidak ditemukan untuk {$this->registration->email} di {$this->filePath}");
                return;
            }

            Mail::to($this->registration->email)->send(new CertificateMail($this->registration, $this->filePath));

            \Log::info("Certificate email sent successfully to {$this->registration->email} at " . now());
        } catch (\Exception $e) {
            \Log::error("Failed to send certificate email to {$this->registration->email}: " . $e->getMessage());
        }
    }
}
