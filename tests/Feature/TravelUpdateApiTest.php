<?php

namespace Tests\Feature;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelUpdateApiTest extends TestCase
{
    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function editor_can_edit_travel()
    {
        $travel = Travel::with('mood')->first();

        // Editor gets a token 
        $editor = User::where('email', 'LIKE', 'editor%')->first();
        $token = $this->getUserToken($editor);

        // Make a PUT request to update a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->putJson('/api/travels/' . $travel->slug, [
            'name' => 'Test Travel Modify',
            'description' => 'This is a test modified travel',
            'numberOfDays' => 10,
            'mood' => [
                'nature' => 99,
                'relax' => 30,
                'party' => 30,
                'culture' => 30,
                'history' => 30
            ]
        ]);

        // Assert that the response is successful
        $response->assertSuccessful();

        // Assert that the response contains the correct data
        $response->assertJson([
            'success' => true,
            'message' => 'Travel Updated',
        ]);

        // Assert that the travel fields are updated in the database
        $this->assertDatabaseHas('travels', [
            'id' => $travel->id,
            'name' => 'Test Travel Modify',
            'description' => 'This is a test modified travel',
            'numberOfDays' => 10,
        ]);

        $this->assertDatabaseHas('moods', [
            'travelId' => $travel->id,
            'nature' => 99,
            'relax' => 30,
            'party' => 30,
            'culture' => 30,
            'history' => 30
        ]);
    }

    /** @test */
    public function subscriber_cannot_update_travel()
    {
        $travel = Travel::with('mood')->first();
        // Subrsciber gets a token 
        $subscriber = User::where('email', 'LIKE', 'badUser%')->first();
        $token = $this->getUserToken($subscriber);

        // Make a PUT request to update a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->putJson("/api/travels/$travel->slug", [
            'name' => 'Test Travel Modify 2 times',
            'description' => 'This is a test modified travel 2 times',
            'numberOfDays' => 10,
            'mood' => ['nature' => 99, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
        ]);

        // Assert that the response is forbidden
        $response->assertForbidden();

        // Assert that the travel fields remain unchanged in the database
        $this->assertDatabaseMissing('travels', [
            'id' => $travel->id,
            'name' => 'Test Travel Modify 2 times',
            'description' => 'This is a test modified travel 2 times'
        ]);
    }

    /** @test */
    public function unauthenticated_cannot_update_travel()
    {
        $travel = Travel::with('mood')->first();

        // Make a PUT request to update a new travel with the new token
        $response = $this->putJson("/api/travels/$travel->slug", [
            'name' => 'Test Travel Modify 3 times',
            'description' => 'This is a test modified travel 3 times',
            'numberOfDays' => 10,
            'mood' => ['nature' => 99, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
        ]);

        // Assert that the response is forbidden
        $response->assertUnauthorized();

        // Assert that the travel fields remain unchanged in the database
        $this->assertDatabaseMissing('travels', [
            'id' => $travel->id,
            'name' => 'Test Travel Modify 3 times',
            'description' => 'This is a test modified travel 3 times',
        ]);
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
