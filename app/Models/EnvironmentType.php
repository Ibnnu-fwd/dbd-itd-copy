<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentType extends Model
{
    use HasFactory;

    public $table = 'environment_types';

    protected $fillable = [
        'name',
        'is_active'
    ];

    // RELATIONSHIPS

    public function larvae()
    {
        return $this->hasMany(Larvae::class);
    }
}
