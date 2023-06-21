<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorType extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }

    public $table = 'floor_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    // RELATIONSHIPS
    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function larvae()
    {
        return $this->hasMany(Larvae::class);
    }
}
