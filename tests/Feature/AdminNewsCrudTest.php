<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNewsCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeNews(array $overrides = []): News
    {
        return News::create(array_merge([
            'title' => 'Tips Karir',
            'category' => 'career_tips',
            'content' => 'Konten berita karir.',
            'is_published' => true,
            'published_at' => now(),
        ], $overrides));
    }

    public function test_admin_news_index_edit_update_and_destroy_work_with_slug_binding(): void
    {
        $news = $this->makeNews();
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.news.index'))->assertStatus(200);

        // Binding slug: route() menghasilkan /admin/news/{slug} dan harus resolve.
        $this->actingAs($admin)->get(route('admin.news.edit', $news))->assertStatus(200);

        $this->actingAs($admin)
            ->put(route('admin.news.update', $news), [
                'title' => 'Tips Karir Updated',
                'category' => 'career_tips',
                'content' => 'Konten diperbarui.',
                'is_published' => 1,
            ])
            ->assertRedirect();

        $this->assertSame('Tips Karir Updated', $news->fresh()->title);

        $this->actingAs($admin)
            ->delete(route('admin.news.destroy', $news))
            ->assertRedirect();

        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_public_cannot_access_admin_news(): void
    {
        $news = $this->makeNews();
        $umum = User::factory()->create(['role' => 'umum']);

        $this->actingAs($umum)->get(route('admin.news.index'))->assertForbidden();
        $this->actingAs($umum)->get(route('admin.news.edit', $news))->assertForbidden();
    }
}
