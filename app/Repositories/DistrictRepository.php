<?php

namespace App\Repositories;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Repositories\Interface\DistrictInterface;
use Illuminate\Support\Facades\DB;

class DistrictRepository implements DistrictInterface
{
    private $province;
    private $regency;
    private $district;

    public function __construct(District $district, Province $province, Regency $regency)
    {
        $this->district = $district;
        $this->province = $province;
        $this->regency = $regency;
    }

    public function getAll()
    {
        return $this->district->with(['province', 'regency'])->get();
    }

    public function getById($id)
    {
        return $this->district->with(['province', 'regency'])->findOrFail($id);
    }

    public function search($search)
    {
        return $this->district->with(['province', 'regency'])
            ->where('name', 'like', '%' . $search . '%')->get();
    }

    public function create(array $attributes)
    {
        return $this->district->create($attributes);
    }

    public function update($id, array $attributes)
    {
        return $this->district->findOrFail($id)->update($attributes);
    }

    public function delete($id)
    {
        return $this->district->findOrFail($id)->update(['is_active' => 0]);
    }

    public function getByRegency($regencyId)
    {
        return $this->district->where('regency_id', $regencyId)->get();
    }
}
