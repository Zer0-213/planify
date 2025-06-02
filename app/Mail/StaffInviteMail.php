<?php

namespace App\Mail;

use App\Models\CompanyInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class StaffInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public CompanyInvite $companyInvite, public string $token)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function build(): StaffInviteMail
    {
        return $this->subject('You are invited to join the company')
            ->markdown('emails.staff_invite') // we'll create this
            ->with([
                'companyInvite' => $this->companyInvite,
                'token' => $this->token,
            ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
