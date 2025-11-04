<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkDisableTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    public function testDisableLink()
    {
        $link = Link::factory()->create();
        $this->assertTrue($link->is_active);
        $this->patch('/api/links/' . $link->slug . '/disable');
        $link->refresh();
        $this->assertFalse($link->is_active);
    }
}
