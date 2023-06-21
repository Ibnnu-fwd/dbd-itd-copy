<?php

namespace App\Repositories;

use App\Models\Province;
use App\Models\Regency;
use App\Repositories\Interface\RegencyInterface;

class RegencyRepository implements RegencyInterface
{
    private $regency;
    private $province;

    public function __construct(Regency $regency, Province $province)
    {
        $this->regency = $regency;
        $this->province = $province;
    }

    public function getAll()
    {
        return $this->regency->with('province')->get();
    }

    public function getById(string $id)
    {
        return $this->regency->with('province')->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->regency->create($attributes);
    }

    public function update(string $id, array $attributes)
    {
        return $this->regency->findOrFail($id)->update($attributes);
    }

    public function delete(string $id)
    {
        return $this->regency->findOrFail($id)->update(['is_active' => 0]);
    }

    public function getByProvince(string $provinceId)
    {
        return $this->regency->where('province_id', $provinceId)->get();
    }
}
