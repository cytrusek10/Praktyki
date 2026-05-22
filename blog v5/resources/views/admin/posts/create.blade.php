@extends('layouts.app')

@section('title', 'Nowy post')

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
    .btn-ai { background: #2a1a3a; border: 1px solid #6b21a8; color: #c084fc; }
    .btn-ai:hover { border-color: #c084fc; }

    .back-link { display: inline-block; color: var(--muted); text-decoration: none; font-family: monospace; font-size: 0.9rem; margin-bottom: 2rem; }

    .section-title { font-size: 1rem; color: var(--muted); font-family: monospace; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); }

    .ai-box {
        background: #1a0f2a;
        border: 1px solid #6b21a8;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }
    .ai-box-title { color: #c084fc; font-family: monospace; font-size: 0.9rem; margin-bottom: 1rem; }
    .ai-row { display: flex; gap: 0.75rem; }
    .ai-row input, .ai-row select { flex: 1; }
    .ai-status { margin-top: 0.75rem; font-family: monospace; font-size: 0.8rem; color: var(--muted); min-height: 1.2rem; }

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
    <h1 style="margin-bottom:2rem">Nowy post</h1>

    {{-- Panel AI --}}
    <div class="ai-box">
        <div class="ai-box-title">✨ Generuj treść przez AI</div>
        <div class="ai-row">
            <input type="text" id="ai-topic" placeholder="Temat posta (np. 'dlaczego herbata jest lepsza od kawy')">
            <select id="ai-category">
                <option value="technologia">💻 Technologia</option>
                <option value="zycie">🌿 Życie</option>
                <option value="jedzenie">🍕 Jedzenie</option>
                <option value="muzyka">🎵 Muzyka</option>
                <option value="sport">⚽ Sport</option>
                <option value="inne">🎲 Inne</option>
            </select>
            <button type="button" class="btn btn-ai" id="ai-generate-btn" onclick="generateWithAi()">Generuj</button>
        </div>
        <div class="ai-status" id="ai-status"></div>
    </div>

    <form method="POST" action="{{ route('admin.posts.store') }}" id="post-form">
        @csrf

        <div class="section-title">Treść</div>

        <div class="form-group">
            <label>Tytuł</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label>Kategoria</label>
            <select name="category" id="category" required>
                <option value="technologia">💻 Technologia</option>
                <option value="zycie">🌿 Życie</option>
                <option value="jedzenie">🍕 Jedzenie</option>
                <option value="muzyka">🎵 Muzyka</option>
                <option value="sport">⚽ Sport</option>
                <option value="inne">🎲 Inne</option>
            </select>
        </div>

        <div class="form-group">
            <label>Krótki opis (zajawka)</label>
            <textarea name="excerpt" id="excerpt" rows="3">{{ old('excerpt') }}</textarea>
        </div>

        <div class="form-group">
            <label>Treść (HTML)</label>
            <textarea name="content" id="content" required>{{ old('content') }}</textarea>
        </div>

        <div class="section-title" style="margin-top:2rem">SEO</div>

        <div class="form-group">
            <label>SEO tytuł <span style="color:var(--muted)">(max 60 znaków)</span></label>
            <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title') }}" maxlength="60">
            <div class="char-count" id="seo-title-count">0/60</div>
        </div>

        <div class="form-group">
            <label>Meta opis <span style="color:var(--muted)">(max 160 znaków)</span></label>
            <textarea name="seo_description" id="seo_description" rows="2" maxlength="160">{{ old('seo_description') }}</textarea>
            <div class="char-count" id="seo-desc-count">0/160</div>
        </div>

        <div class="section-title" style="margin-top:2rem">Tagi</div>

        @if($tags->count())
            <div class="form-group">
                <label>Istniejące tagi</label>
                <div class="tags-checkboxes">
                    @foreach($tags as $tag)
                        <label class="tag-check">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="form-group">
            <label>Nowe tagi <span style="color:var(--muted)">(oddziel przecinkami)</span></label>
            <input type="text" name="new_tags" id="new_tags" value="{{ old('new_tags') }}" placeholder="np. php, docker, mysql">
        </div>

        <div class="section-title" style="margin-top:2rem">Publikacja</div>

        <div class="form-group">
            <label>Data publikacji</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
                Opublikowany
            </label>
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1rem">
            <button type="submit" class="btn btn-primary">Zapisz post</button>
            <button type="button" class="btn btn-secondary" onclick="preview()">Podgląd</button>
        </div>
    </form>

    <script>
        // Liczniki znaków SEO
        function charCounter(inputId, countId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(countId);
            input.addEventListener('input', () => {
                const len = input.value.length;
                counter.textContent = `${len}/${max}`;
                counter.className = 'char-count' + (len > max * 0.9 ? (len >= max ? ' over' : ' warn') : '');
            });
        }
        charCounter('seo_title', 'seo-title-count', 60);
        charCounter('seo_description', 'seo-desc-count', 160);

        // Generowanie AI
        async function generateWithAi() {
            const topic = document.getElementById('ai-topic').value.trim();
            const category = document.getElementById('ai-category').value;
            const status = document.getElementById('ai-status');
            const btn = document.getElementById('ai-generate-btn');

            if (!topic) { status.textContent = '⚠️ Podaj temat'; return; }

            btn.disabled = true;
            btn.textContent = 'Generuję...';
            status.textContent = '⏳ AI pisze post, chwila...';

            try {
                const res = await fetch('{{ route('admin.ai.generate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ topic, category }),
                });

                const data = await res.json();

                if (data.error) { status.textContent = '❌ ' + data.error; return; }

                document.getElementById('title').value           = data.title;
                document.getElementById('excerpt').value         = data.excerpt;
                document.getElementById('content').value         = data.content;
                document.getElementById('seo_title').value       = data.seo_title;
                document.getElementById('seo_description').value = data.seo_description;
                document.getElementById('category').value        = category;

                if (data.suggested_tags && data.suggested_tags.length) {
                    const newTagsInput = document.getElementById('new_tags');
                    newTagsInput.value = data.suggested_tags.join(', ');
                }

                // odpalamy liczniki
                ['seo_title', 'seo_description'].forEach(id => {
                    document.getElementById(id).dispatchEvent(new Event('input'));
                });

                status.textContent = '✅ Gotowe! Sprawdź i popraw jeśli trzeba, a potem zapisz.';
            } catch (e) {
                status.textContent = '❌ Coś poszło nie tak: ' + e.message;
            } finally {
                btn.disabled = false;
                btn.textContent = 'Generuj';
            }
        }

        // Podgląd
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
