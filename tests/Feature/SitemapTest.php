<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_xml_with_public_urls(): void
    {
        Job::factory()->create(['status' => 'active']);
        News::create([
            'title' => 'Tips Karir',
            'category' => 'career_tips',
            'content' => 'Konten berita karir.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/xml');
        $response->assertSee('<?xml', false);
        $response->assertSee('<urlset', false);
        $response->assertSee('<loc>' . url('/') . '</loc>', false);
        $response->assertSee('<loc>' . url('/jobs') . '</loc>', false);
        $response->assertSee('<loc>' . route('news.index') . '</loc>', false);
        $response->assertSee(route('jobs.show', Job::first()), false);
        $response->assertSee(route('news.show', News::first()), false);
    }

    public function test_sitemap_excludes_non_public_content(): void
    {
        Job::factory()->create(['status' => 'draft']);
        News::create([
            'title' => 'Draf Tersembunyi',
            'category' => 'career_tips',
            'content' => 'Belum terbit.',
            'is_published' => false,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertDontSee(route('jobs.show', Job::where('status', 'draft')->first()), false);
        $response->assertDontSee('draf-tersembunyi', false);
    }
}
