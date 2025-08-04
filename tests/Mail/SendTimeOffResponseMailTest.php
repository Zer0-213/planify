<?php

namespace Tests\Mail;

use App\Mail\SendTimeOffResponseMail;
use App\Models\TimeOffRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SendTimeOffResponseMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_correct_envelope(): void
    {
        $timeOffRequest = TimeOffRequest::factory()->create([
            'status' => 'approved'
        ]);

        $mail = new SendTimeOffResponseMail($timeOffRequest);
        $envelope = $mail->envelope();

        $this->assertEquals('Time Off Request Approved', $envelope->subject);
    }

    public function test_it_creates_mail_with_rejected_status(): void
    {
        $timeOffRequest = TimeOffRequest::factory()->create([
            'status' => 'rejected'
        ]);

        $mail = new SendTimeOffResponseMail($timeOffRequest);
        $envelope = $mail->envelope();

        $this->assertEquals('Time Off Request Rejected', $envelope->subject);
    }

    public function test_it_has_correct_content_configuration(): void
    {
        $timeOffRequest = TimeOffRequest::factory()->create();

        $mail = new SendTimeOffResponseMail($timeOffRequest);
        $content = $mail->content();

        $this->assertEquals('emails.time_off_response', $content->view);
        $this->assertArrayHasKey('timeOffRequest', $content->with);
        $this->assertEquals($timeOffRequest->id, $content->with['timeOffRequest']->id);
    }

    public function test_it_passes_time_off_request_to_view(): void
    {
        $timeOffRequest = TimeOffRequest::factory()->create([
            'reason' => 'Family vacation',
            'status' => 'approved'
        ]);

        $mail = new SendTimeOffResponseMail($timeOffRequest);
        $content = $mail->content();

        $this->assertSame($timeOffRequest, $content->with['timeOffRequest']);
        $this->assertEquals('Family vacation', $content->with['timeOffRequest']->reason);
        $this->assertEquals('approved', $content->with['timeOffRequest']->status);
    }

}
