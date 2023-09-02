<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projects_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'slug',
        'seo_description'
    ];

    /**
     * Get category translation
     *
     * @return object
     */
    public function translation()
    {
        return $this->hasMany(ProjectCategoryTranslation::class, 'projects_category_id');
    }

    /**
     * Get projects in this category
     *
     * @return object
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'category_id');
    }
}
