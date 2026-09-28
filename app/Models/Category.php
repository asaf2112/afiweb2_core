<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'spec_fields'];

    protected $casts = [
        'spec_fields' => 'array',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function specTypes()
    {
        return $this->belongsToMany(SpecType::class, 'category_spec_type')->distinct();
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Sadece ana kategorileri (parent_id == null) döndürür.
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
