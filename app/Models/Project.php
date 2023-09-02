<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'pid',
        'user_id',
        'title',
        'slug',
        'description',
        'category_id',
        'budget_min',
        'budget_max',
        'budget_type',
        'duration',
        'status',
        'rejection_reason',
        'is_featured',
        'is_urgent',
        'is_highlighted',
        'is_alert',
        'expiry_date_featured',
        'expiry_date_urgent',
        'expiry_date_highlight',
        'counter_views',
        'counter_impressions',
        'counter_bids',
        'expiry_date'
    ];

    /**
     * Get project skills
     *
     * @return object
     */
    public function skills()
    {
        return $this->hasMany(ProjectRequiredSkill::class, 'project_id');
    }

    /**
     * Get category
     *
     * @return object
     */
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    /**
     * Get project bids
     *
     * @return object
     */
    public function bids()
    {
        return $this->hasMany(ProjectBid::class, 'project_id');
    }

    /**
     * Get project's milestones
     */
    public function milestones()
    {
        return $this->hasMany(ProjectMilestone::class, 'project_id');
    }

    /**
     * Get project's client
     *
     * @return object
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * Get awarded bid for this project
     *
     * @return object
     */
    public function awarded_bid()
    {
        return $this->hasOne(ProjectBid::class, 'project_id')->where('is_awarded', true);
    }

    /**
     * Get shared filesa
     *
     * @return object
     */
    public function shared_files()
    {
        return $this->hasMany(ProjectSharedFile::class, 'project_id');
    }
}
