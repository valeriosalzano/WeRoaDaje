<?php

namespace Tests\Feature;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourStoreApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function admin_can_create_new_tour()
    {
        $travel = Travel::first();

        // Admin gets a token 
        $admin = User::where('email', 'LIKE', 'admin%')->first();
        $token = $this->getUserToken($admin);

        // Make a POST request to create a new tour with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson("/api/travels/$travel->slug/tours", [
            'name' => 'New Tour',
            'startingDate' => '2024-05-01',
            'endingDate' => '2024-05-07',
            'price' => 1000,
        ]);

        // Assert that the tour was created successfully
        $response->assertStatus(201);

        // Assert that the response contains the correct data
        $response->assertJson([
            'success' => true,
            'message' => 'Tour Created',
        ]);

        // Assert that the tour is associated with the correct travel
        $this->assertDatabaseHas('tours', [
            'travelId' => $travel->id,
            'name' => 'New Tour',
        ]);
    }

    /** @test */
    public function editor_cannot_create_new_tour()
    {
        $travel = Travel::first();

        // Editor gets a token 
        $editor = User::where('email', 'LIKE', 'editor%')->first();
        $token = $this->getUserToken($editor);

        // Make a POST request to create a new tour with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson("/api/travels/$travel->slug/tours", [
            'name' => 'New Tour',
            'startingDate' => '2024-05-01',
            'endingDate' => '2024-05-07',
            'price' => 1000,
        ]);

        // Assert that the response is forbidden
        $response->assertForbidden();

        // Assert that the tour was not created
        $this->assertDatabaseMissing('tours', [
            'travelId' => $travel->id,
            'name' => 'New Tour',
        ]);
    }

    /** @test */
    public function subscriber_cannot_create_new_tour()
    {
        $travel = Travel::first();

        // Subrsciber gets a token 
        $subscriber = User::where('email', 'LIKE', 'badUser%')->first();
        $token = $this->getUserToken($subscriber);

        // Make a POST request to create a new tour with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson("/api/travels/$travel->slug/tours", [
            'name' => 'New Tour',
            'startingDate' => '2024-05-01',
            'endingDate' => '2024-05-07',
            'price' => 1000,
        ]);

        // Assert that the response is forbidden
        $response->assertForbidden();

        // Assert that the tour was not created
        $this->assertDatabaseMissing('tours', [
            'travelId' => $travel->id,
            'name' => 'New Tour',
        ]);
    }

    /** @test */
    public function unauthenticated_cannot_create_new_tour()
    {
        $travel = Travel::first();

        // Make a POST request to create a new tour
        $response = $this->postJson("/api/travels/$travel->slug/tours", [
            'name' => 'New Tour',
            'startingDate' => '2024-05-01',
            'endingDate' => '2024-05-07',
            'price' => 1000,
        ]);

        // Assert that the response is forbidden
        $response->assertUnauthorized();

        // Assert that the tour was not created
        $this->assertDatabaseMissing('tours', [
            'travelId' => $travel->id,
            'name' => 'New Tour',
        ]);
    }


    /** @test */
    public function request_must_contain_required_fields()
    {
        $travel = Travel::first();

        // Admin gets a token 
        $admin = User::where('email', 'LIKE', 'admin%')->first();
        $token = $this->getUserToken($admin);

        // Make a POST request without fields to create a new tour with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson("/api/travels/$travel->slug/tours", []);

        // Assert that the request is invalid and returns validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['startingDate', 'endingDate', 'price']);
    }

    // Helper to get the token
    private function getUserToken($user)
    {
        // Make a POST request to get a token
        $response = $this->postJson('/api/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Return the editor's token
        return $response->json('token');
    }
}
