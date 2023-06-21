<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSampleSerotype extends Model
{
    use HasFactory;

    public $table = 'detail_sample_serotypes';
    protected $fillable = [
        'detail_sample_morphotype_id',
        'serotype_id',
        'amount'
    ];

    // RELATIONSHIPS
    public function detailSampleMorphotype()
    {
        return $this->belongsTo(DetailSampleMorphotype::class, 'detail_sample_morphotype_id');
    }

    public function serotype()
    {
        return $this->belongsTo(Serotype::class, 'serotype_id');
    }

    public function detailSampleVirus()
    {
        return $this->belongsTo(DetailSampleVirus::class, 'detail_sample_virus_id');
    }
}
