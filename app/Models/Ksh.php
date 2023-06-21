<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ksh extends Model
{
    use HasFactory;

    public $table = 'ksh';
    protected $fillable = [
        'latitude',
        'longitude',
        'regency_id',
        'district_id',
        'village_id',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELATION
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function detailKsh()
    {
        return $this->hasMany(DetailKsh::class, 'ksh_id', 'id');
    }
}
