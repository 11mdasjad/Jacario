<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_customer_can_register_new_account(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Julian Vance',
            'email' => 'julian.vance@example.com',
            'phone' => '+91 98200 44444',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'julian.vance@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_customer_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'customer@jacario.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_customer_cannot_login_with_invalid_password(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'customer@jacario.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_can_update_profile_and_addresses(): void
    {
        $user = User::where('role', 'customer')->first();

        // Update profile
        $profileResponse = $this->actingAs($user)->post(route('account.profile.update'), [
            'name' => 'Lord Archibald Updated',
            'email' => $user->email,
            'phone' => '+91 98200 77777',
        ]);
        $profileResponse->assertRedirect();
        $user->refresh();
        $this->assertEquals('Lord Archibald Updated', $user->name);

        // Add new address
        $addressResponse = $this->actingAs($user)->post(route('account.addresses.store'), [
            'full_name' => 'Archibald Sterling',
            'phone' => '+91 98200 77777',
            'address_line_1' => 'Suite 501, Horizon Plaza',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'postal_code' => '400050',
            'address_type' => 'work',
            'is_default' => 1,
        ]);
        $addressResponse->assertRedirect();
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'address_line_1' => 'Suite 501, Horizon Plaza',
        ]);
    }
}
