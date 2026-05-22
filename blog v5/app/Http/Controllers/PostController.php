<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::published()->with('tags');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $request->tag));
        }

        $posts        = $query->paginate(6)->withQueryString();
        $currentTag   = $request->filled('tag') ? Tag::where('slug', $request->tag)->first() : null;

        return view('posts.index', compact('posts', 'currentTag'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('published', true)
            ->with(['comments.user', 'tags'])
            ->firstOrFail();

        return view('posts.show', compact('post'));
    }
}
