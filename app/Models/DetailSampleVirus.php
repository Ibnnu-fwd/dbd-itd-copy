<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSampleVirus extends Model
{
    use HasFactory;

    public $table = 'detail_sample_viruses';
    protected $fillable = [
        'sample_id',
        'virus_id',
        'amount',
    ];

    // RELATIONSHIP
    public function sample()
    {
        return $this->belongsTo(Sample::class, 'sample_id');
    }

    public function virus()
    {
        return $this->belongsTo(Virus::class, 'virus_id');
    }

    public function detailSampleMorphotypes()
    {
        return $this->hasMany(DetailSampleMorphotype::class, 'detail_sample_virus_id');
    }
}
