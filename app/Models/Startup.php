<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Startup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'location',
        'industry',
        'stage',
        'funding',
        'funding_round',
        'description',
        'website',
        'emp_numbers',
        'technologies'
    ];

    protected $casts = [
        'funding' => 'decimal:2',
        'emp_numbers' => 'integer',
    ];

    // Handle technologies as a comma-separated string
    public function setTechnologiesAttribute($value)
    {
        // If value is an array, convert to comma-separated string
        if (is_array($value)) {
            $this->attributes['technologies'] = implode(', ', $value);
        } else {
            $this->attributes['technologies'] = $value;
        }
    }

    public function getTechnologiesAttribute($value)
    {
        // Return technologies as an array for backward compatibility in the UI
        if ($value && !is_array($value) && strpos($value, '[') !== 0) {
            // If it's a comma-separated string, explode it
            return array_map('trim', explode(',', $value));
        }
        // If it's already JSON, handle it accordingly
        return $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
    }

    // Relationships
    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'startup_investor')
            ->withPivot('investment_amount', 'investment_date', 'equity_stake')
            ->withTimestamps();
    }

    public function newsArticles()
    {
        return $this->belongsToMany(NewsArticle::class, 'startup_news_article')
            ->withTimestamps();
    }

    public function getFormattedFundingAttribute()
    {
        return '$' . number_format($this->funding, 2) . 'M';
    }

    public function getStageBadgeClassAttribute()
    {
        return match($this->stage) {
            'Seed' => 'badge-warning',
            'Series A' => 'badge-primary',
            'Series B', 'Series C', 'Growth' => 'badge-success',
            default => 'badge-secondary'
        };
    }
}