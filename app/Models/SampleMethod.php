<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleMethod extends Model
{
    use HasFactory;

    public $table = 'sample_methods';
    protected $fillable = [
        'name',
        'is_active'
    ];

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // RELATIONSHIPS
    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
