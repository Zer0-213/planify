<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $user = User::factory()->create();
        $user->subscriptions()->create(['type' => 'default', 'paddle_id' => '1234567890', 'status' => 'active']);

        $company = Company::factory()->create(['owner_id' => $user->id]);

        CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'is_default' => true,
        ]);


        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

}
