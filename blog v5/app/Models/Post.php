<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'seo_title',
        'seo_description',
        'category',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('published', true)->orderBy('published_at', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getSeoTitleAttribute($value): string
    {
        return $value ?: $this->title;
    }

    public function getSeoDescriptionAttribute($value): string
    {
        return $value ?: Str::limit(strip_tags($this->excerpt ?: $this->content), 155);
    }
}
