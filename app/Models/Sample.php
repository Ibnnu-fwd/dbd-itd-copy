<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    public $table = "samples";
    protected $fillable = [
        'sample_code',
        'file_code',
        'public_health_name',
        // 'sample_method_id',
        'latitude',
        'longitude',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'location_type_id',
        'location_name',
        'created_by',
        'updated_by',
        'is_active',
        'description'
    ];

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // RELATIONSHIPS
    public function detailSampleViruses()
    {
        return $this->hasMany(DetailSampleVirus::class);
    }

    public function locationType()
    {
        return $this->belongsTo(LocationType::class);
    }

    // public function sampleMethod()
    // {
    //     return $this->belongsTo(SampleMethod::class);
    // }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
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
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function detailSamples()
    {
        return $this->hasMany(DetailSample::class);
    }

    // generate sample code : SC-2021-0001
    public function generateSampleCode()
    {
        $lastSample = $this->orderBy('id', 'desc')->first();
        $lastId = $lastSample ? $lastSample->id : 0;
        $year = date('Y');
        $code = 'SC-' . $year . '-' . sprintf('%04s', $lastId + 1);
        return $code;
    }
}
