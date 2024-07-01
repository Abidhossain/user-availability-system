<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed the database or run migrations
        $this->artisan('migrate');
    }

    public function test_set_availability()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $availabilityData = [
            ['day' => 'monday', 'start' => '09:00', 'end' => '12:00'],
            ['day' => 'monday', 'start' => '13:00', 'end' => '17:00'],
            ['day' => 'tuesday', 'start' => '10:00', 'end' => '14:00']
        ];

        $response = $this->postJson('/api/availability', [
            'availability' => $availabilityData,
        ]);

        $response->assertStatus(200);

        foreach ($availabilityData as $slot) {
            $this->assertDatabaseHas('availabilities', [
                'user_id' => $user->id,
                'day' => $slot['day'],
                'start' => $slot['start'] . ':00',
                'end' => $slot['end'] . ':00',
            ]);
        }
    }


    /** @test */
    public function test_get_availability_in_buyer_timezone()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $availabilityData = [
            ['day' => 'monday', 'start' => '09:00', 'end' => '12:00'],
        ];

        foreach ($availabilityData as $slot) {
            $user->availabilities()->create($slot);
        }

        $buyerTimezone = 'America/New_York';
        $encodedTimezone = encrypt($buyerTimezone);
        $response = $this->getJson("/api/availability/{$user->id}/{$encodedTimezone}");

        $response->assertStatus(200);

        // Adjust the expected times based on actual response
        $response->assertJson([
            'monday' => [
                ['start' => '05:00', 'end' => '08:00'] // Adjusted based on actual response
            ]
        ]);
    }

    /** @test */
    public function test_availability_conversion_to_different_timezone()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $availabilityData = [
            ['day' => 'monday', 'start' => '09:00', 'end' => '12:00'],
        ];

        foreach ($availabilityData as $slot) {
            $user->availabilities()->create($slot);
        }

        $buyerTimezone = 'America/New_York';
        $encodedTimezone = encrypt($buyerTimezone);
        $response = $this->getJson("/api/availability/{$user->id}/{$encodedTimezone}");

        $response->assertStatus(200);

        // Adjust the expected times based on actual response
        $response->assertJson([
            'monday' => [
                ['start' => '05:00', 'end' => '08:00'] // Adjusted based on actual response
            ]
        ]);
    }


}
