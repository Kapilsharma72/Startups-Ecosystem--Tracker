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
        'avg_check',
        'description',
        'founded',
        'website',
        'team_members',
        'investment_criteria',
        'portfolio_companies'
    ];

    protected $casts = [
        'total_investment' => 'decimal:2',
        'avg_check' => 'decimal:2',
        'portfolio_size' => 'integer',
        'founded' => 'integer'
    ];

    // Handle focus_areas as a comma-separated string
    public function setFocusAreasAttribute($value)
    {
        // If value is an array, convert to comma-separated string
        if (is_array($value)) {
            $this->attributes['focus_areas'] = implode(', ', $value);
        } else {
            $this->attributes['focus_areas'] = $value;
        }
    }

    public function getFocusAreasAttribute($value)
    {
        // Return focus areas as an array for backward compatibility in the UI
        if ($value && !is_array($value) && strpos($value, '[') !== 0) {
            // If it's a comma-separated string, explode it
            return array_map('trim', explode(',', $value));
        }
        // If it's already JSON, handle it accordingly
        return $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
    }

    // Handle team_members as a comma-separated string
    public function setTeamMembersAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['team_members'] = implode(', ', $value);
        } else {
            $this->attributes['team_members'] = $value;
        }
    }

    public function getTeamMembersAttribute($value)
    {
        if ($value && !is_array($value) && strpos($value, '[') !== 0) {
            return array_map('trim', explode(',', $value));
        }
        return $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
    }

    // Handle investment_criteria as a comma-separated string
    public function setInvestmentCriteriaAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['investment_criteria'] = implode(', ', $value);
        } else {
            $this->attributes['investment_criteria'] = $value;
        }
    }

    public function getInvestmentCriteriaAttribute($value)
    {
        if ($value && !is_array($value) && strpos($value, '[') !== 0) {
            return array_map('trim', explode(',', $value));
        }
        return $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
    }

    // Handle portfolio_companies as a comma-separated string
    public function setPortfolioCompaniesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['portfolio_companies'] = implode(', ', $value);
        } else {
            $this->attributes['portfolio_companies'] = $value;
        }
    }

    public function getPortfolioCompaniesAttribute($value)
    {
        if ($value && !is_array($value) && strpos($value, '[') !== 0) {
            return array_map('trim', explode(',', $value));
        }
        return $value ? (is_array($value) ? $value : json_decode($value, true)) : [];
    }

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