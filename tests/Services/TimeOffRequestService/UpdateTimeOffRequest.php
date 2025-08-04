<?php

namespace Tests\Services\TimeOffRequestService;

use App\Mail\SendTimeOffResponseMail;
use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use App\Services\TimeOffRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UpdateTimeOffRequest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests for the updateTimeOffRequest method in the TimeOffRequestService class.
     *
     * This method updates a TimeOffRequest with the provided data
     * and sends an email notification to the user associated with the request.
     */

    private TimeOffRequestService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TimeOffRequestService();
        Mail::fake();
    }

    public function test_it_updates_time_off_request_successfully(): void
    {
        // Arrange
        $timeOffRequest = TimeOffRequest::factory()->create();
        $startDate = now()->addDays()->startOfDay();
        $endDate = now()->addDays(2)->startOfDay();
        $updateData = [
            'status' => 'approved',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $this->service->updateTimeOffRequest($updateData, $timeOffRequest);

        $timeOffRequest->refresh();
        $this->assertEquals('approved', $timeOffRequest->status);
        $this->assertEquals($startDate, $timeOffRequest->start_date);
        $this->assertEquals($endDate, $timeOffRequest->end_date);

        $this->assertDatabaseHas('time_off_requests', [
            'id' => $timeOffRequest->id,
            'status' => 'approved',
            'start_date' => $startDate->toDateTimeString(),
            'end_date' => $endDate->toDateTimeString(),
        ]);
    }


    public function test_it_sends_email_after_updating_time_off_request(): void
    {
        // Arrange
        $companyUser = CompanyUser::factory()->create(['user_id' => 1]);
        $timeOffRequest = TimeOffRequest::factory()->create(['company_user_id' => $companyUser->id]);
        $updateData = ['status' => 'approved'];

        // Act
        $this->service->updateTimeOffRequest($updateData, $timeOffRequest, true);

        Mail::assertQueued(SendTimeOffResponseMail::class, function ($mail) use ($timeOffRequest) {
            return $mail->timeOffRequest->id === $timeOffRequest->id;
        });
    }

}
