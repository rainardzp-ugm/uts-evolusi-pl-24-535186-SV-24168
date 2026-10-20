<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiBookTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_books()
    {
        Sanctum::actingAs(User::factory()->create());

        Book::factory()->create([
            'title' => 'Pemrograman Web',
            'author' => 'John Doe',
            'year' => 2024,
            'stock' => 10,
        ]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_can_create_book_and_returns_201()
    {
        Sanctum::actingAs(User::factory()->create());

        $payload = [
            'title' => 'Belajar Laravel',
            'author' => 'Jane Doe',
            'year' => 2024,
            'stock' => 15,
        ];

        $response = $this->postJson('/api/books', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Belajar Laravel']);
    }

    public function test_validation_fails_returns_422()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/books', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'author', 'year', 'stock']);
    }
}