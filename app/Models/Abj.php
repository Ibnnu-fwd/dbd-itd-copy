<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abj extends Model
{
    use HasFactory;

    public $table = 'abj';
    protected $fillable = [
        'district_id',
        'village_id',
        'ksh_id',
        'abj_total',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELATIONSHIP
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id', 'id');
    }

    public function ksh()
    {
        return $this->belongsTo(Ksh::class, 'ksh_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // SCOPE
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
