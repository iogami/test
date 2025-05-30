<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\ShortLinkVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShortLinkRedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_original_url(): void
    {
        $link = ShortLink::create([
            'original_url' => 'https://example.com',
            'code'         => 'Ab12Cd',
            'created_at'   => now(),
        ]);

        $response = $this->get('/Ab12Cd');

        $response->assertRedirect('https://example.com');
    }

    /** @test */
    public function it_returns_404_for_invalid_code(): void
    {
        $response = $this->get('/invalid1');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_records_a_visit(): void
    {
        $link = ShortLink::create([
            'original_url' => 'https://example.com',
            'code'         => 'XyZ123',
            'created_at'   => now(),
        ]);

        $this->get('/XyZ123');

        $this->assertDatabaseCount('short_link_visits', 1);
        $this->assertDatabaseHas('short_link_visits', [
            'short_link_id' => $link->id,
        ]);
    }

    /** @test */
    public function it_stores_ip_address_on_visit(): void
    {
        $link = ShortLink::create([
            'original_url' => 'https://example.com',
            'code'         => 'IpTest1',
            'created_at'   => now(),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '123.123.123.123'])
            ->get('/IpTest1');

        $visit = ShortLinkVisit::first();

        $this->assertEquals('123.123.123.123', $visit->ip_address);
    }
}
