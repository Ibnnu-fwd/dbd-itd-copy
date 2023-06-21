<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKsh extends Model
{
    use HasFactory;

    public $table = 'detail_ksh';
    protected $fillable = [
        'ksh_id',
        'house_name',
        'house_owner',
        'latitude',
        'longitude',
        'tpa_type_id',
        'larva_status',
        'created_by',
        'updated_by',
        'is_active'
    ];

    // RELATION
    public function ksh()
    {
        return $this->belongsTo(Ksh::class, 'ksh_id', 'id');
    }

    public function tpaType()
    {
        return $this->belongsTo(TpaType::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
