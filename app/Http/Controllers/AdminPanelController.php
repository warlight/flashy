<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function dashboard(Request $request)
    {
        $linksQuery = Link::withCount('linkHits as hitsCount');

        $search = $request->search ?? null;

        if ($search) {
            $linksQuery->where('slug', 'LIKE', '%' . $request->search . '%');
        }

        $links = $linksQuery->paginate(10);

        return view('dashboard', compact('links', 'search'));
    }
}
