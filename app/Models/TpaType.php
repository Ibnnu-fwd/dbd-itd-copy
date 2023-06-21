<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpaType extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('is_active', true);
        });
    }

    public $table = 'tpa_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    // RELATIONSHIPS
    public function detailLarvae()
    {
        return $this->hasMany(DetailLarvae::class, 'tpa_type_id', 'id');
    }

    public function ksh()
    {
        return $this->hasMany(Ksh::class, 'tpa_type_id', 'id');
    }

    public function detailKsh()
    {
        return $this->hasMany(DetailKsh::class, 'tpa_type_id', 'id');
    }



}
