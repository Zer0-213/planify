<?php

namespace Database\Seeders;

use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TimeOffRequestSeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $companyUsers = CompanyUser::all();

        if ($companyUsers->isEmpty()) {
            $this->command->warn('No company users found. Please run CompanyUserSeeder first.');
            return;
        }

        TimeOffRequest::query()->delete();

        foreach ($companyUsers as $companyUser) {
            $this->createTimeOffRequests($companyUser);
        }
    }

    /**
     * @throws RandomException
     */
    private function createTimeOffRequests(CompanyUser $companyUser): void
    {
        $randomDay = random_int(1, 30);
        $randomDay2 = random_int(1, 30);

        $requests = [
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->subDays(28),
                'is_full_day' => true,
                'status' => 'approved',
                'reason' => 'Family vacation',
                'approved_by' => $companyUser->id,
                'approved_at' => Carbon::now()->subDays(25),
                'created_at' => Carbon::now()->subDays(35),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00:00',
                'end_date' => Carbon::now()->addDays(5),
                'end_time' => '13:00:00',
                'is_full_day' => false,
                'status' => 'pending',
                'reason' => 'Medical appointment',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(17),
                'is_full_day' => true,
                'status' => 'pending',
                'reason' => 'Personal leave',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(min($randomDay, $randomDay2)),
                'end_date' => Carbon::now()->addDays(max($randomDay, $randomDay2)),
                'is_full_day' => true,
                'status' => 'approved',
                'reason' => 'Random',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(min($randomDay, $randomDay2)),
                'end_date' => Carbon::now()->addDays(max($randomDay, $randomDay2)),
                'is_full_day' => true,
                'status' => 'approved',
                'reason' => 'Random2',
                'created_at' => Carbon::now()->subDay(),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(27),
                'is_full_day' => true,
                'status' => 'rejected',
                'reason' => 'Holiday request',
                'admin_notes' => 'Too many people already off during this period',
                'approved_by' => $companyUser->id,
                'approved_at' => Carbon::now()->subHours(12),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'company_user_id' => $companyUser->id,
                'start_date' => Carbon::now()->addDays(10),
                'start_time' => '14:00:00',
                'end_date' => Carbon::now()->addDays(10),
                'end_time' => '18:00:00',
                'is_full_day' => false,
                'status' => 'pending',
                'reason' => 'Child school event',
                'created_at' => Carbon::now()->subHours(6),
            ],
        ];

        foreach ($requests as $requestData) {
            TimeOffRequest::create($requestData);
        }

        $this->command->info("Created 5 time off requests for company user: {$companyUser->id}");
    }
}
