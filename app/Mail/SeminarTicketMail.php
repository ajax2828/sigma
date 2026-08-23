<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;

class SeminarTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registrant;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registrant)
    {
        $this->registrant = $registrant;
    }

    public function build(){
        return $this->subject('Tiket Seminar')
            ->markdown('emails.seminar')
            ->with(['registrant' => $this->registrant]);
    }
}
