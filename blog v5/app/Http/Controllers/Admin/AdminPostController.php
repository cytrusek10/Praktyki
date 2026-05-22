<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index()
    {
        $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|max:255',
            'excerpt'         => 'nullable|max:500',
            'content'         => 'required',
            'category'        => 'required',
            'seo_title'       => 'nullable|max:255',
            'seo_description' => 'nullable|max:160',
            'new_tags'        => 'nullable|string',
            'published'       => 'boolean',
            'published_at'    => 'nullable|date',
        ]);

        $validated['slug']         = Str::slug($validated['title']);
        $validated['published']    = $request->has('published');
        $validated['published_at'] = $validated['published_at'] ?? now();

        $post = Post::create($validated);

        $this->syncTags($post, $request->input('tags', []), $request->input('new_tags'));

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        $tags = Tag::orderBy('name')->get();
        $post->load('tags');

        return view('admin.posts.edit', compact('post', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'           => 'required|max:255',
            'excerpt'         => 'nullable|max:500',
            'content'         => 'required',
            'category'        => 'required',
            'seo_title'       => 'nullable|max:255',
            'seo_description' => 'nullable|max:160',
            'new_tags'        => 'nullable|string',
            'published'       => 'boolean',
            'published_at'    => 'nullable|date',
        ]);

        $validated['slug']         = Str::slug($validated['title']);
        $validated['published']    = $request->has('published');
        $validated['published_at'] = $validated['published_at'] ?? now();

        $post->update($validated);

        $this->syncTags($post, $request->input('tags', []), $request->input('new_tags'));

        return redirect()->route('admin.posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index');
    }

    public function preview(Request $request)
    {
        $post = new Post([
            'title'        => $request->input('title'),
            'excerpt'      => $request->input('excerpt'),
            'content'      => $request->input('content'),
            'category'     => $request->input('category'),
            'published_at' => now(),
        ]);

        return view('posts.show', compact('post'));
    }

    private function syncTags(Post $post, array $existingTagIds, ?string $newTagsString): void
    {
        $tagIds = array_filter($existingTagIds);

        if ($newTagsString) {
            $newNames = array_map('trim', explode(',', $newTagsString));
            foreach ($newNames as $name) {
                if ($name === '') continue;
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                );
                $tagIds[] = $tag->id;
            }
        }

        $post->tags()->sync($tagIds);
    }
}
