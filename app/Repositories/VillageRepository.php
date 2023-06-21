<?php

namespace App\Repositories;

use App\Models\Village;
use App\Repositories\Interface\VillageInterface;

class VillageRepository implements VillageInterface
{
    private $village;

    public function __construct(Village $village)
    {
        $this->village = $village;
    }

    public function getAll()
    {
        return $this->village->with(['regency', 'district', 'province'])->get();
    }

    public function search($search)
    {
        return $this->village->with(['regency', 'district', 'province'])
            ->where('name', 'like', '%' . $search . '%')
            ->get();
    }

    public function getById($id)
    {
        return $this->village->with(['regency', 'district', 'province'])->find($id);
    }

    public function create($attributes)
    {
        return $this->village->create([
            'id' => $this->village->generateId(),
            'name' => $attributes['name'],
            'district_id' => $attributes['district_id']
        ]);
    }

    public function update($id, $attributes)
    {
        return $this->village->find($id)->update([
            'name' => $attributes['name'],
            'district_id' => $attributes['district_id']
        ]);
    }

    public function delete($id)
    {
        return $this->village->find($id)->update(['is_active' => 0]);
    }

    public function getByDistrict($districtId)
    {
        return $this->village->where('district_id', $districtId)->get();
    }
}
