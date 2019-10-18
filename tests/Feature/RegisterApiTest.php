<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function should_create_a_new_user_and_return()
    {
        $data = [
            'name' => 'user',
            'email' => 'dummy@emali.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->json('POST', route('register'), $data);
        $user = User::first();
        $this->assertSame($data['name'], $user->name);
        $response
            ->assertStatus(200)
            ->assertJson(['name' => $user->name]);
    }
}
