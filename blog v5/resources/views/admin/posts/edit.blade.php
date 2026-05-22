@extends('layouts.app')

@section('title', 'Edytuj post')

@push('styles')
<style>
    .form-group { margin-bottom: 1.25rem; }
    label { display: block; margin-bottom: 0.4rem; color: var(--muted); font-family: monospace; font-size: 0.85rem; }
    input[type="text"], input[type="datetime-local"], select, textarea {
        width: 100%;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.6rem 0.85rem;
        color: var(--text);
        font-size: 0.95rem;
        font-family: inherit;
        transition: border-color 0.2s;
    }
    input:focus, select:focus, textarea:focus { outline: none; border-color: var(--accent); }
    textarea { min-height: 300px; resize: vertical; }
    input[type="checkbox"] { margin-right: 0.5rem; }

    .btn { display: inline-block; padding: 0.6rem 1.5rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; cursor: pointer; border: none; text-decoration: none; }
    .btn-primary { background: var(--accent); color: #000; font-weight: bold; }
    .btn-secondary { background: var(--surface); border: 1px solid var(--border); color: var(--text); }

    .back-link { display: inline-block; color: var(--muted); text-decoration: none; font-family: monospace; font-size: 0.9rem; margin-bottom: 2rem; }
    .section-title { font-size: 1rem; color: var(--muted); font-family: monospace; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); }

    .tags-checkboxes { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .tag-check { display: flex; align-items: center; gap: 0.35rem; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 0.25rem 0.75rem; font-family: monospace; font-size: 0.8rem; cursor: pointer; }
    .tag-check input { margin: 0; }

    .char-count { font-family: monospace; font-size: 0.75rem; color: var(--muted); text-align: right; margin-top: 0.25rem; }
    .char-count.warn { color: #f59e0b; }
    .char-count.over { color: #f87171; }
</style>
@endpush

@section('content')
    <a href="{{ route('admin.posts.index') }}" class="back-link">← wróć do listy</a>
    <h1 style="margin-bottom:2rem">Edytuj post</h1>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" id="post-form">
        @csrf
        @method('PUT')

        <div class="section-title">Treść</div>

        <div class="form-group">
            <label>Tytuł</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label>Kategoria</label>
            <select name="category" required>
                @foreach(['technologia' => '💻 Technologia', 'zycie' => '🌿 Życie', 'jedzenie' => '🍕 Jedzenie', 'muzyka' => '🎵 Muzyka', 'sport' => '⚽ Sport', 'inne' => '🎲 Inne'] as $value => $label)
                    <option value="{{ $value }}" {{ old('category', $post->category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Krótki opis (zajawka)</label>
            <textarea name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div class="form-group">
            <label>Treść (HTML)</label>
            <textarea name="content" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="section-title" style="margin-top:2rem">SEO</div>

        <div class="form-group">
            <label>SEO tytuł <span style="color:var(--muted)">(max 60 znaków)</span></label>
            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $post->getRawOriginal('seo_title')) }}" maxlength="60">
            <div class="char-count" id="seo-title-count">0/60</div>
        </div>

        <div class="form-group">
            <label>Meta opis <span style="color:var(--muted)">(max 160 znaków)</span></label>
            <textarea name="seo_description" id="seo_description" rows="2" maxlength="160">{{ old('seo_description', $post->getRawOriginal('seo_description')) }}</textarea>
            <div class="char-count" id="seo-desc-count">0/160</div>
        </div>

        <div class="section-title" style="margin-top:2rem">Tagi</div>

        @if($tags->count())
            <div class="form-group">
                <label>Tagi</label>
                <div class="tags-checkboxes">
                    @foreach($tags as $tag)
                        <label class="tag-check">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                {{ $post->tags->contains($tag->id) ? 'checked' : '' }}>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="form-group">
            <label>Nowe tagi <span style="color:var(--muted)">(oddziel przecinkami)</span></label>
            <input type="text" name="new_tags" value="{{ old('new_tags') }}" placeholder="np. php, docker, mysql">
        </div>

        <div class="section-title" style="margin-top:2rem">Publikacja</div>

        <div class="form-group">
            <label>Data publikacji</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="published" value="1" {{ old('published', $post->published) ? 'checked' : '' }}>
                Opublikowany
            </label>
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1rem">
            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
            <button type="button" class="btn btn-secondary" onclick="preview()">Podgląd</button>
        </div>
    </form>

    <script>
        function charCounter(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            const update = () => {
                const len = input.value.length;
                counter.textContent = `${len}/${max}`;
                counter.className = 'char-count' + (len > max * 0.9 ? (len >= max ? ' over' : ' warn') : '');
            };
            input.addEventListener('input', update);
            update();
        }
        charCounter('seo_title', 'seo-title-count', 60);
        charCounter('seo_description', 'seo-desc-count', 160);

        function preview() {
            const form = document.getElementById('post-form');
            const data = new FormData(form);
            const pf = document.createElement('form');
            pf.method = 'POST';
            pf.action = '{{ route('admin.posts.preview') }}';
            pf.target = '_blank';
            for (const [k, v] of data.entries()) {
                const i = document.createElement('input');
                i.type = 'hidden'; i.name = k; i.value = v;
                pf.appendChild(i);
            }
            document.body.appendChild(pf);
            pf.submit();
            document.body.removeChild(pf);
        }
    </script>
@endsection
