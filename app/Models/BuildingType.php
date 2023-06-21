<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingType extends Model
{
    use HasFactory;

    public $table = 'building_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // RELATIONSHIPS
    public function larvae()
    {
        return $this->hasMany(Larvae::class);
    }

}
