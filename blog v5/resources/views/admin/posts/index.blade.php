@extends('layouts.app')

@section('title', 'Admin – lista postów')

@push('styles')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .btn {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.9rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }

    .btn-primary { background: var(--accent); color: #000; }
    .btn-danger  { background: #7f1d1d; color: #fca5a5; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
    }

    th { color: var(--muted); font-family: monospace; font-weight: normal; }

    .actions { display: flex; gap: 0.5rem; }
</style>
@endpush

@section('content')
    <div class="admin-header">
        <h1>Posty</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ Nowy post</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tytuł</th>
                <th>Kategoria</th>
                <th>Status</th>
                <th>Data</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td><span class="badge badge-{{ $post->category }}">{{ $post->category }}</span></td>
                    <td>{{ $post->published ? '✅ opublikowany' : '⏳ szkic' }}</td>
                    <td>{{ $post->published_at?->format('d.m.Y') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Edytuj</a>
                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Na pewno usunąć?')">Usuń</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:1.5rem">{{ $posts->links() }}</div>
@endsection
