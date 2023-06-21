<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Virus extends Model
{
    use HasFactory;

    public $table = 'viruses';
    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    // RElATIONSHIP
    public function detailSampleVirus()
    {
        return $this->hasMany(DetailSampleVirus::class, 'virus_id');
    }

    public function detailSamples()
    {
        return $this->hasMany(DetailSample::class, 'viruses_id');
    }

    // SCOPE
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
