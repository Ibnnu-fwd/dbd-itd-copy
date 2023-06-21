<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public $table = 'provinces';

    protected $fillable = [
        'name',
        'is_active'
    ];

    // RELATIONSHIPS
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function regencies()
    {
        return $this->hasMany(Regency::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    // SCOPES

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    public function scopeFilterByProvince($query, array $filters)
    {
        $query->when($filters['province_id'] ?? false, function ($query, $province_id) {
            $query->where('province_id', $province_id);
        });
    }

    public function scopeFilterByRegency($query, array $filters)
    {
        $query->when($filters['regency_id'] ?? false, function ($query, $regency_id) {
            $query->where('regency_id', $regency_id);
        });
    }

    public function scopeFilterByDistrict($query, array $filters)
    {
        $query->when($filters['district_id'] ?? false, function ($query, $district_id) {
            $query->where('district_id', $district_id);
        });
    }

    public function scopeFilterByVillage($query, array $filters)
    {
        $query->when($filters['village_id'] ?? false, function ($query, $village_id) {
            $query->where('village_id', $village_id);
        });
    }
}
