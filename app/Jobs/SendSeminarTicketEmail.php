<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SeminarTicketMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Registration;

class SendSeminarTicketEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $registration;
    protected $filePath;
    protected $delaySeconds;

    /**
     * Create a new job instance.
     *
     * @param Registration $registration
     * @return void
     */
    public function __construct(Registration $registration, $filePath = null, $delaySeconds = 0)
    {
        $this->registration = $registration;
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
        try {
            Mail::to($this->registration->email)->send(new SeminarTicketMail($this->registration));
            \Log::info("Email sent successfully to {$this->registration->email} at " . now());
        } catch (\Exception $e) {
            \Log::error("Failed to send email to {$this->registration->email}: " . $e->getMessage());
        }
    }
}
