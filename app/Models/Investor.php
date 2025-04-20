<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'location',
        'type',
        'focus_areas',
        'portfolio_size',
        'total_investment',
        'description',
        'website',
        'team_members',
        'investment_criteria',
        'portfolio_companies'
    ];

    protected $casts = [
        'focus_areas' => 'array',
        'total_investment' => 'decimal:2',
        'team_members' => 'array',
        'investment_criteria' => 'array',
        'portfolio_companies' => 'array'
    ];

    public function startups()
    {
        return $this->belongsToMany(Startup::class, 'startup_investor')
            ->withPivot('investment_amount', 'investment_date', 'equity_stake')
            ->withTimestamps();
    }

    public function newsArticles()
    {
        return $this->belongsToMany(NewsArticle::class, 'investor_news_article')
            ->withTimestamps();
    }

    public function getFormattedTotalInvestmentAttribute()
    {
        return '$' . number_format($this->total_investment, 2) . 'M';
    }

    public function getTypeBadgeClassAttribute()
    {
        return match($this->type) {
            'Venture Capital' => 'badge-primary',
            'Angel Investor' => 'badge-success',
            'Private Equity' => 'badge-warning',
            'Corporate VC' => 'badge-info',
            'Accelerator' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }
} 