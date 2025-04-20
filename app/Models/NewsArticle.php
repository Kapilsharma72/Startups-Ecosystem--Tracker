<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'source',
        'url',
        'published_at',
        'comments_count',
        'likes_count',
        'shares_count',
        'related_startups',
        'related_investors',
        'tags'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'related_startups' => 'array',
        'related_investors' => 'array',
        'tags' => 'array'
    ];

    public function startups()
    {
        return $this->belongsToMany(Startup::class, 'startup_news_article')
            ->withTimestamps();
    }

    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'investor_news_article')
            ->withTimestamps();
    }

    public function getCategoryBadgeClassAttribute()
    {
        return match($this->category) {
            'Funding' => 'badge-success',
            'Product Launch' => 'badge-primary',
            'M&A' => 'badge-warning',
            'Partnership' => 'badge-info',
            'Market Analysis' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at->format('M d, Y H:i');
    }

    public function getTimeAgoAttribute()
    {
        return $this->published_at->diffForHumans();
    }
} 