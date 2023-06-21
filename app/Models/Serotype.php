<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serotype extends Model
{
    use HasFactory;

    public $table = 'serotypes';
    protected $fillable = [
        'name',
        'is_active'
    ];

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // MUTATORS & ACCESSORS
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    // RELATIONSHIP
    public function detailSampleSerotypes()
    {
        return $this->hasMany(DetailSampleSerotype::class, 'serotype_id');
    }
}
