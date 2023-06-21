<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettlementType extends Model
{
    use HasFactory;

    public $table = 'settlement_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

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
