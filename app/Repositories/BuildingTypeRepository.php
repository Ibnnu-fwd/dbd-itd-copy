<?php

namespace App\Repositories;

use App\Models\BuildingType;
use App\Repositories\Interface\BuildingTypeInterface;

class BuildingTypeRepository implements BuildingTypeInterface
{
    private $buildingType;

    public function __construct(BuildingType $buildingType) {
        $this->buildingType = $buildingType;
    }

    public function getAll()
    {
        return $this->buildingType->active()->get();
    }

    public function getById($id)
    {
        return $this->buildingType->active()->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->buildingType->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update($id, array $attributes)
    {
        $buildingType = $this->buildingType->findOrFail($id);
        $buildingType->update([
            'name' => $attributes['name'],
        ]);
        return $buildingType;
    }

    public function delete($id)
    {
        $buildingType = $this->buildingType->findOrFail($id);
        $buildingType->update([
            'is_active' => 0,
        ]);
        return $buildingType;
    }
}
