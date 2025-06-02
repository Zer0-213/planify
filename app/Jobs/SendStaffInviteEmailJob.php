<?php

namespace App\Jobs;

use App\Mail\StaffInviteMail;
use App\Models\CompanyInvite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailer;

class SendStaffInviteEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public CompanyInvite $companyInvite, public string $token)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(Mailer $mailer): void
    {
        $mailer->to($this->companyInvite->email)
            ->send(new StaffInviteMail($this->companyInvite, $this->token));
    }
}
