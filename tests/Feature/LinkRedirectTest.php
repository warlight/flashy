<?php

namespace Tests\Feature;

use App\Jobs\LogLinkHitJob;
use App\Models\BotUserAgent;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LinkRedirectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    public function testRedirectLinkWorks()
    {
        Queue::fake();

        $link = Link::factory()->create();
        $response = $this->get('/r/' . $link->slug);
        $response->assertStatus(302);
        $response->assertRedirect($link->target_url);

        Queue::assertPushed(LogLinkHitJob::class);
    }

    public function testRedirectLink404()
    {
        $response = $this->get('/r/' . $this->faker->slug);
        $response->assertStatus(404);
    }

    public function testRedirectDisabledLink()
    {
        $link = Link::factory()->create([
            'is_active' => false,
        ]);
        $response = $this->get('/r/' . $link->slug);
        $response->assertStatus(410);
    }

    public function testRedirectLinkByBot()
    {
        Queue::fake();

        $botRecord = BotUserAgent::factory()->create();

        $link = Link::factory()->create();
        $response = $this->withHeaders([
            'User-Agent' => $this->faker->shuffleString() . $botRecord->bot_user_agent_part . $this->faker->shuffleString()
        ])
            ->get('/r/' . $link->slug);
        $response->assertStatus(302);
        $response->assertRedirect($link->target_url);

        Queue::assertNothingPushed();
    }
}
