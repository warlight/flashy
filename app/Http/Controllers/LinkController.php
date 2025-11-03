<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Models\Link;
use App\Services\SlugGeneratorService;
use Illuminate\Http\Request;
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
        $link = Link::where('slug', $slug)->firstOrFail();

        $data = $link->loadCount('linkHits as totalHits')
            ->load(['linkHits' => function ($query) {
                $query->limit(5);
            }]);

        return response()->json(['data' => $data]);
    }

    public function redirect(string $slug, Request $request)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        if (!$link->is_active) {
            abort(Response::HTTP_GONE);
        }

        // fire job to log agent and IP from request
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
