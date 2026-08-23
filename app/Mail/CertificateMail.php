<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registrant;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registrant, $filePath)
    {
        $this->registrant = $registrant;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this->subject('Sertifikat Seminar')
            ->view('emails.sertifikatSeminar')
            ->with(['registrant' => $this->registrant])
            ->attach($this->filePath);
    }
}
