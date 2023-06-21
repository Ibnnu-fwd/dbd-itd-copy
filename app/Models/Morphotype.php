<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Morphotype extends Model
{
    use HasFactory;

    public $table = 'morphotypes';
    protected $fillable = ['name', 'is_active'];

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // RELATIONSHIPS
    public function detailSample()
    {
        return $this->hasMany(DetailSample::class);
    }

    public function detailSampleMorphotypes()
    {
        return $this->hasMany(DetailSampleMorphotype::class);
    }
}
