@extends('layouts.app')

@section('title', $post->seo_title . ' – randomblog.pl')

@push('meta')
<meta name="description" content="{{ $post->seo_description }}">
<meta name="keywords" content="{{ $post->tags->pluck('name')->join(', ') }}">
<meta property="og:title" content="{{ $post->seo_title }}">
<meta property="og:description" content="{{ $post->seo_description }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
@if($post->exists)
<link rel="canonical" href="{{ route('post.show', $post->slug) }}">
@endif
@endpush

@push('styles')
<style>
    .back-link { display: inline-block; color: var(--muted); text-decoration: none; font-family: monospace; font-size: 0.9rem; margin-bottom: 2.5rem; transition: color 0.2s; }
    .back-link:hover { color: var(--accent); }

    article { max-width: 680px; margin: 0 auto; }
    .post-header { margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border); }
    .post-meta { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .post-date { color: var(--muted); font-family: monospace; font-size: 0.85rem; }

    article h1 { font-size: 2.2rem; line-height: 1.2; margin-bottom: 1rem; }
    .post-excerpt { color: var(--muted); font-size: 1.1rem; line-height: 1.6; font-style: italic; }

    .post-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 1.25rem; }
    .post-tag { display: inline-block; padding: 0.2rem 0.6rem; background: var(--surface); border: 1px solid var(--border); border-radius: 4px; font-family: monospace; font-size: 0.75rem; color: var(--muted); text-decoration: none; transition: border-color 0.2s, color 0.2s; }
    .post-tag:hover { border-color: var(--accent); color: var(--accent); }

    .post-content { line-height: 1.8; font-size: 1.05rem; }
    .post-content p { margin-bottom: 1.5rem; }
    .post-content h2, .post-content h3 { margin: 2rem 0 1rem; color: var(--accent); }
    .post-content strong { color: var(--accent); }
    .post-content a { color: var(--accent2); }
    .post-content ul, .post-content ol { margin: 1rem 0 1.5rem 1.5rem; }
    .post-content li { margin-bottom: 0.5rem; }
    .post-content blockquote { border-left: 3px solid var(--accent); padding-left: 1.25rem; margin: 1.5rem 0; color: var(--muted); font-style: italic; }

    .comments-section { max-width: 680px; margin: 3rem auto 0; padding-top: 2.5rem; border-top: 1px solid var(--border); }
    .comments-section h2 { font-size: 1.3rem; margin-bottom: 1.5rem; }

    .comment { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1rem; }
    .comment-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
    .comment-author { font-family: monospace; font-size: 0.85rem; color: var(--accent); }
    .comment-date { font-family: monospace; font-size: 0.75rem; color: var(--muted); }
    .comment-body { font-size: 0.95rem; line-height: 1.6; }
    .comment-delete { background: none; border: none; color: var(--muted); font-size: 0.75rem; font-family: monospace; cursor: pointer; padding: 0; margin-left: 1rem; transition: color 0.2s; }
    .comment-delete:hover { color: #f87171; }

    .comment-form { margin-top: 2rem; }
    .comment-form textarea { width: 100%; background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; color: var(--text); font-family: inherit; font-size: 0.95rem; resize: vertical; min-height: 100px; transition: border-color 0.2s; }
    .comment-form textarea:focus { outline: none; border-color: var(--accent); }

    .btn-comment { margin-top: 0.75rem; padding: 0.55rem 1.5rem; background: var(--accent); color: #000; border: none; border-radius: 6px; font-family: monospace; font-size: 0.9rem; font-weight: bold; cursor: pointer; }

    .login-prompt { background: var(--surface); border: 1px solid var(--border); border-radius: 8px; padding: 1.25rem; text-align: center; color: var(--muted); font-size: 0.9rem; margin-top: 1.5rem; }
    .login-prompt a { color: var(--accent); text-decoration: none; }

    .alert-success { background: #1a3a2a; color: #4ade80; border-radius: 6px; padding: 0.75rem 1rem; margin-bottom: 1rem; font-family: monospace; font-size: 0.85rem; }
    .preview-banner { background: #7f6a00; color: #fef08a; padding: 0.75rem 1.25rem; border-radius: 8px; margin-bottom: 2rem; font-family: monospace; font-size: 0.9rem; }
</style>
@endpush

@section('content')
    @if(!$post->exists)
        <div class="preview-banner">⚠️ To jest podgląd — post nie został jeszcze zapisany</div>
    @endif

    <a href="{{ route('home') }}" class="back-link">← wróć do listy</a>

    <article>
        <div class="post-header">
            <div class="post-meta">
                <span class="badge badge-{{ $post->category }}">{{ $post->category }}</span>
                <span class="post-date">{{ $post->published_at?->format('d.m.Y') ?? 'brak daty' }}</span>
            </div>
            <h1>{{ $post->title }}</h1>
            @if($post->excerpt)
                <p class="post-excerpt">{{ $post->excerpt }}</p>
            @endif
            @if($post->exists && $post->tags->count())
                <div class="post-tags">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('home', ['tag' => $tag->slug]) }}" class="post-tag">#{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="post-content">
            {!! $post->content !!}
        </div>
    </article>

    @if($post->exists)
        <section class="comments-section">
            <h2>Komentarze ({{ $post->comments->count() }})</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @forelse($post->comments as $comment)
                <div class="comment">
                    <div class="comment-header">
                        <div style="display:flex;align-items:center">
                            <span class="comment-author">{{ $comment->user->name }}</span>
                            @auth
                                @if(auth()->id() === $comment->user_id)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="comment-delete" onclick="return confirm('Usunąć komentarz?')">usuń</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <span class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="comment-body">{{ $comment->content }}</div>
                </div>
            @empty
                <p style="color:var(--muted);font-size:0.9rem;margin-bottom:1.5rem">Brak komentarzy. Bądź pierwszy!</p>
            @endforelse

            @auth
                <div class="comment-form">
                    <form method="POST" action="{{ route('comments.store', $post) }}">
                        @csrf
                        <textarea name="content" placeholder="Napisz coś..." required>{{ old('content') }}</textarea>
                        @error('content') <div style="color:#f87171;font-size:0.8rem;margin-top:0.3rem;font-family:monospace">{{ $message }}</div> @enderror
                        <button type="submit" class="btn-comment">Dodaj komentarz</button>
                    </form>
                </div>
            @else
                <div class="login-prompt">
                    <a href="{{ route('login') }}">Zaloguj się</a> albo <a href="{{ route('register') }}">załóż konto</a> żeby komentować.
                </div>
            @endauth
        </section>
    @endif
@endsection
