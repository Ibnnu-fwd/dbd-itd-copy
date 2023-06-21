<?php

namespace App\Repositories;

use App\Models\FloorType;
use App\Repositories\Interface\FloorTypeInterface;

class FloorTypeRepository implements FloorTypeInterface
{
    private $floorType;

    public function __construct(FloorType $floorType) {
        $this->floorType = $floorType;
    }

    public function getAll()
    {
        return $this->floorType->all();
    }

    public function getById(string $id)
    {
        return $this->floorType->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->floorType->create($attributes);
    }

    public function update(string $id, array $attributes)
    {
        return $this->floorType->findOrFail($id)->update($attributes);
    }

    public function delete(string $id)
    {
        return $this->floorType->findOrFail($id)->update(['is_active' => false]);
    }
}
