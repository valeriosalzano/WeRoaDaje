<?php

namespace Tests\Feature;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelStoreApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function admin_can_create_new_travel()
    {
        // Admin gets a token 
        $admin = User::where('email', 'LIKE', 'admin%')->first();
        $token = $this->getUserToken($admin);

        // Make a POST request to create a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson('/api/travels', [
            'name' => 'Test Travel',
            'description' => 'This is a test travel',
            'numberOfDays' => 7,
            'moods' => ['nature' => 30, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
        ]);

        // Assert that the response is successful
        $response->assertSuccessful();

        // Assert that the response contains the correct data
        $response->assertJson([
            'success' => true,
            'message' => 'Travel Created',
        ]);
    }

    /** @test */
    public function editor_cannot_create_new_travel()
    {

        // Editor gets a token 
        $editor = User::where('email', 'LIKE', 'editor%')->first();
        $token = $this->getUserToken($editor);

        // Make a POST request to create a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson('/api/travels', [
            'name' => 'Test Travel',
            'description' => 'This is a test travel',
            'numberOfDays' => 7,
            'moods' => ['nature' => 30, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
        ]);

        // Assert that the response is forbidden
        $response->assertForbidden();
    }

    /** @test */
    public function subscriber_cannot_create_new_travel()
    {
        // Subrsciber gets a token 
        $subscriber = User::where('email', 'LIKE', 'badUser%')->first();
        $token = $this->getUserToken($subscriber);

        // Make a POST request to create a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson('/api/travels', [
            'name' => 'Test Travel',
            'description' => 'This is a test travel',
            'numberOfDays' => 7,
            'moods' => ['nature' => 30, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
        ]);

        // Assert that the response is forbidden
        $response->assertForbidden();
    }

     /** @test */
     public function unauthenticated_cannot_create_new_travel()
     {
 
         // Make a POST request to create a new travel without token
         $response = $this->postJson('/api/travels', [
             'name' => 'Test Travel',
             'description' => 'This is a test travel',
             'numberOfDays' => 7,
             'moods' => ['nature' => 30, 'relax' => 30, 'party' => 30, 'culture' => 30, 'history' => 30],
         ]);
 
         // Assert that the response is unauthorized
         $response->assertUnauthorized();
     }
    
    /** @test */
    public function store_request_must_contain_required_fields()
    {

        // Admin gets a token 
        $admin = User::where('email', 'LIKE', 'admin%')->first();
        $token = $this->getUserToken($admin);

        // Make a POST request without fields to create a new travel with the new token
        $this->withHeader('Authorization', 'Bearer ' . $token);
        $response = $this->postJson('/api/travels', []);

        // Assert that the response contains validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'slug', 'numberOfDays']);
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
