<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkCreationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    const SERVICE_KEY = 'X-HEADER';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    public function testCreationWorks()
    {
        $link = Link::factory()->make();
        $requestBody = ['target_url' => $link->target_url, 'slug' => $link->slug];
        $response = $this->withHeaders([
            'X-Service-Key' => self::SERVICE_KEY,
        ])->postJson('/api/links', $requestBody);
        $response->assertStatus(201);
        $jsonResponse = json_decode($response->getContent(), true);
        $this->assertEquals($link->slug, $jsonResponse['slug']);
    }

    public function testCreationBadServiceKey()
    {
        $link = Link::factory()->make();
        $requestBody = ['target_url' => $link->target_url, 'slug' => $link->slug];
        $response = $this->postJson('/api/links', $requestBody);
        $response->assertStatus(401);
    }

    public function testCreationSlugNotNeeded()
    {
        $link = Link::factory()->make();
        $requestBody = ['target_url' => $link->target_url];
        $response = $this->withHeaders([
            'X-Service-Key' => self::SERVICE_KEY,
        ])->postJson('/api/links', $requestBody);
        $response->assertStatus(201);
        $jsonResponse = json_decode($response->getContent(), true);
        $this->assertNotEmpty($jsonResponse['slug']);
    }

    public static function creationValidationCases(): array
    {
        return [
            [
                // target url is required
                null, null, 'The target url field is required.',
            ],
            [
                //slug min
                'http://example.com', 'slug', 'The slug field must be at least 5 characters.',
            ],
            [
                //slug max
                'http://example.com',
                'slug1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678907890123',
                'The slug field must not be greater than 100 characters.',
            ],
            [
                // valid url
                'some_word', null, 'The target url field must be a valid URL.',
            ],
            [
                // at least string and also not numeric
                12345, null, 'The target url field must be a string. (and 1 more error)',
            ]
        ];
    }

    /** @dataProvider creationValidationCases */
    public function testCreationValidation($targetUrl, $slug, $message)
    {
        $requestBody = ['target_url' => $targetUrl];

        if ($slug) {
            $requestBody['slug'] = $slug;
        }

        $response = $this->withHeaders([
            'X-Service-Key' => self::SERVICE_KEY,
        ])->postJson('/api/links', $requestBody);
        $response->assertJsonPath('message', $message);
    }

    public static function creationValidationSlugCases(): array
    {
        return [
            ['slug!'], ['slug@'], ['slug#'], ['slug$'], ['slug%'], ['slug^'], ['slug&'], ['slug*'], ['slug('], ['slug)'], ['slug+'], ['slug='],
        ];
    }

    /** @dataProvider creationValidationSlugCases */
    public function testCreationValidationSlug($slug)
    {
        $requestBody = ['target_url' => Link::factory()->make()->target_url, 'slug' => $slug];
        $response = $this->withHeaders([
            'X-Service-Key' => self::SERVICE_KEY,
        ])->postJson('/api/links', $requestBody);
        $response->assertStatus(422);
    }
}
