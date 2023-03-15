<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    use WithFaker;

    /**
     * @return void
     */
    public function test_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/logout');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_details(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/user');

        $response->assertStatus(Response::HTTP_OK);
    }
}
