<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Job;
use App\Models\News;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $urls = collect([
                url('/'),
                route('jobs.index'),
                route('events.index'),
                route('news.index'),
            ]);

            Job::where('status', 'active')->select('id')->latest('created_at')->chunk(500, function ($jobs) use ($urls) {
                foreach ($jobs as $job) {
                    $urls->push(route('jobs.show', $job));
                }
            });
            News::where('is_published', true)->select('id')->latest('created_at')->chunk(500, function ($items) use ($urls) {
                foreach ($items as $news) {
                    $urls->push(route('news.show', $news));
                }
            });
            Event::select('id')->latest('start_time')->chunk(500, function ($items) use ($urls) {
                foreach ($items as $event) {
                    $urls->push(route('events.show', $event));
                }
            });

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            foreach ($urls->unique() as $url) {
                $xml .= '  <url><loc>' . e($url) . '</loc></url>' . "\n";
            }
            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
