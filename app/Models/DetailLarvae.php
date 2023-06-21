<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLarvae extends Model
{
    use HasFactory;

    public $table = 'detail_larvae';
    protected $fillable = [
        'larva_id',
        'tpa_type_id',
        'amount_larva',
        'amount_egg',
        'number_of_adults',
        'water_temperature',
        'salinity',
        'ph',
        'aquatic_plant'
    ];

    // RELATIONSHIPS
    public function larvae()
    {
        return $this->belongsTo(Larvae::class, 'larva_id', 'id');
    }

    public function tpaType()
    {
        return $this->belongsTo(TpaType::class, 'tpa_type_id', 'id');
    }

    // translate aquatic_plant value
    public function getAquaticPlantTranslation()
    {
        if($this->aquatic_plant == 'available') {
            return 'Tersedia';
        } elseif($this->aquatic_plant == 'not_available') {
            return 'Tidak Tersedia';
        } else {
            return '-';
        }
    }
}
