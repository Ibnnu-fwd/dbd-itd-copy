<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSampleMorphotype extends Model
{
    use HasFactory;

    public $table = 'detail_sample_morphotypes';
    protected $fillable = [
        'detail_sample_virus_id',
        'morphotype_id',
        'amount'
    ];

    // RELATIONSHIPS
    public function detailSampleVirus()
    {
        return $this->belongsTo(DetailSampleVirus::class, 'detail_sample_virus_id', 'id');
    }

    public function morphotype()
    {
        return $this->belongsTo(Morphotype::class, 'morphotype_id');
    }

    public function detailSampleSerotypes()
    {
        return $this->hasMany(DetailSampleSerotype::class, 'detail_sample_morphotype_id');
    }
}
