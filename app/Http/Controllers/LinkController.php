<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Jobs\LogLinkHitJob;
use App\Models\Link;
use App\Services\SlugGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LinkController extends Controller
{
    public function store(LinkStoreRequest $request)
    {
        $slug = $request->slug ?? SlugGeneratorService::generateUniqueSlug();

        Link::create([
            'slug' => $slug,
            'target_url' => $request->target_url,
        ]);

        return response()->json(['slug' => $slug], 201);
    }

    public function stats(string $slug)
    {
        // try to get cache:
        $cache = Cache::get('cached_stat_' . $slug);
        if ($cache) {
            return response()->json(['data' => $cache]);
        }

        $link = Link::where('slug', $slug)
            ->withCount('linkHits as total_hits')
            ->with(['linkHits' => function ($query) {
                $query->orderByDesc('created_at')->limit(5);
            }])
            ->firstOrFail();

        $data = [
            'total_hits' => $link->total_hits,
            'target_url' => $link->target_url,
            'last_hits' => $link->linkHits->map->only('created_at', 'ip')
        ];

        Cache::put(['cached_stat_' . $slug => $data], now()->addSeconds(60));

        return response()->json(['data' => $data]);
    }

    public function redirect(string $slug, Request $request)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        if (!$link->is_active) {
            abort(Response::HTTP_GONE);
        }

        LogLinkHitJob::dispatch($link, $request->ip(), $request->userAgent());

        return redirect($link->target_url);
    }

    public function disable(string $slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();
        $link->is_active = false;
        $link->save();

        return response()->json(['message' => 'ok']);
    }
}
