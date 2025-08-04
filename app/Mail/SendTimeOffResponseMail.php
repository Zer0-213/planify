<?php

namespace App\Mail;

use App\Models\TimeOffRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendTimeOffResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TimeOffRequest $timeOffRequest,
    )
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Time Off Request ' . ucfirst($this->timeOffRequest->status),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.time_off_response',
            with: [
                'timeOffRequest' => $this->timeOffRequest,
            ],
        );
    }
}
