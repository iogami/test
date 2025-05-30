<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ShortLink;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_short_link_with_valid_url(): void
    {
        $response = $this->postJson('/api/short-links', [
            'url' => 'https://example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'original_url',
                    'short_url',
                    'code'
                ],
            ]);

        $this->assertDatabaseCount('short_links', 1);
        $this->assertDatabaseHas('short_links', [
            'original_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function it_returns_validation_error_for_empty_url(): void
    {
        $response = $this->postJson('/api/short-links', []);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['url']);
    }

    /** @test */
    public function it_returns_validation_error_for_invalid_url(): void
    {
        $response = $this->postJson('/api/short-links', [
            'url' => 'not-a-valid-url',
        ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['url']);
    }

    /** @test */
    public function it_generates_unique_codes(): void
    {
        $urls = ['https://a.com', 'https://b.com', 'https://c.com'];

        foreach ($urls as $url) {
            $this->postJson('/api/short-links', ['url' => $url])->assertStatus(200);
        }

        $codes = ShortLink::pluck('code')->toArray();

        $this->assertCount(3, array_unique($codes));
    }
}
