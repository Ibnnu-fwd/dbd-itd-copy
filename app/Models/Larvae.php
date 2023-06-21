<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Larvae extends Model
{
    use HasFactory;

    public $table = 'larvae';
    protected $fillable = [
        'larva_code', // ADD THIS LINE
        'regency_id',
        'district_id',
        'village_id',
        'location_type_id',
        'settlement_type_id',
        'environment_type_id',
        'building_type_id',
        'floor_type_id',
        'address',
        'latitude',
        'longitude',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELATIONSHIP
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

    public function locationType()
    {
        return $this->belongsTo(LocationType::class);
    }

    public function settlementType()
    {
        return $this->belongsTo(SettlementType::class);
    }

    public function environmentType()
    {
        return $this->belongsTo(EnvironmentType::class);
    }

    public function buildingType()
    {
        return $this->belongsTo(BuildingType::class);
    }

    public function floorType()
    {
        return $this->belongsTo(FloorType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function detailLarvaes()
    {
        return $this->hasMany(DetailLarvae::class, 'larva_id', 'id');
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function generateLarvaCode() // ADD THIS FUNCTION
    {
        // LC-yearmonthdate-0001
        $larvaCode = 'LC-' . date('Ymd') . '-0001';
        $lastLarva = $this->where('larva_code', 'like', '%' . date('Ymd') . '%')->orderBy('larva_code', 'desc')->first();
        if ($lastLarva) {
            $larvaCode = $lastLarva->larva_code;
            $larvaCode = explode('-', $larvaCode);
            $larvaCode = $larvaCode[2] + 1;
            $larvaCode = 'LC-' . date('Ymd') . '-' . str_pad($larvaCode, 4, '0', STR_PAD_LEFT);
        }
        return $larvaCode;
    }
}
