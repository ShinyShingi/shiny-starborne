<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'published_at',
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = ['clean_excerpt'];

    public function getCleanExcerptAttribute()
    {
        // Decode HTML entities and strip tags for excerpt
        $decoded = html_entity_decode($this->content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Replace &nbsp; with regular spaces
        $decoded = str_replace(['&nbsp;', ' '], ' ', $decoded);
        // Strip HTML tags
        $stripped = strip_tags($decoded);
        // Return first 150 characters
        return mb_substr($stripped, 0, 150) . (mb_strlen($stripped) > 150 ? '...' : '');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
