<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Cliente;

class ClienteTest extends TestCase
{
    public function testsClientesAreCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
            'name' => 'Lorem Ipsum',
            'email' => 'user@test.com',
        ];

        $this->json('POST', '/api/clientes', $payload, $headers)
            ->assertStatus(201)
            ->assertJson(['id' => 51, 'name' => 'Lorem Ipsum', 'email' => 'user@test.com']);
    }

    public function testsClientesAreUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $cliente = factory(Cliente::class)->create([
            'name' => 'First cliente',
            'email' => 'user@test.com',
        ]);

        $payload = [
            'name' => 'Second cliente',
            'email' => 'cliente@test.com',
        ];

        $response = $this->json('PUT', '/api/clientes/' . $cliente->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJson([ 
                'id' => 51, 
                'name' => 'Second cliente',
                'email' => 'cliente@test.com',
            ]);
    }

    public function testsClientesAreDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $cliente = factory(Cliente::class)->create([
            'name' => 'First cliente',
            'email' => 'user@test.com',
        ]);

        $this->json('DELETE', '/api/clientes/' . $cliente->id, [], $headers)
            ->assertStatus(204);
    }

    public function testClientesAreListedCorrectly()
    {
        factory(Cliente::class)->create([
            'name' => 'First cliente',
            'email' => 'first_cliente@test.com',
        ]);

        factory(Cliente::class)->create([
            'name' => 'Second cliente',
            'email' => 'second_cliente@test.com',
        ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/clientes?offset=50', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                [ 'id' => 51, 'name' => 'First cliente', 'email' => 'first_cliente@test.com' ],
                [ 'id' => 52, 'name' => 'Second cliente', 'email' => 'second_cliente@test.com' ]
            ])
            ->assertJsonStructure([
                '*' => ['id', 'name', 'email', 'created_at', 'updated_at'],
            ]);
    }
}
