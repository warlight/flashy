<?php

namespace Tests\Unit;

use App\Models\Link;
use App\Services\SlugGeneratorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlugGeneratorServiceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    public function testGenerateSlugHasAllowedCharacters()
    {
        $slug = SlugGeneratorService::generateUniqueSlug();
        $this->assertNotEmpty($slug);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]+$/', $slug);
    }

    public function testCheckSlugIsUnique()
    {
        $slug = $this->faker->unique()->slug;
        $this->assertTrue(SlugGeneratorService::checkSlugIsUnique($slug));
        Link::factory()->create(['slug' => $slug]);
        $this->assertFalse(SlugGeneratorService::checkSlugIsUnique($slug));
    }
}
