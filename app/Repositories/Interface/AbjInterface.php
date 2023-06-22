<?php

namespace App\Repositories\Interface;

interface AbjInterface
{
    public function getAllGroupByDistrict();
    public function getGeoJson();
    public function cuttingData($attributes);
}
