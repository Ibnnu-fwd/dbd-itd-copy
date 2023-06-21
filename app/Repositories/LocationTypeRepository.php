<?php

namespace App\Repositories;

use App\Models\LocationType;
use App\Repositories\Interface\LocationTypeInterface;

class LocationTypeRepository implements LocationTypeInterface
{
    private $locationType;

    public function __construct(LocationType $locationType) {
        $this->locationType = $locationType;
    }

    public function getAll()
    {
        return $this->locationType->active()->get();
    }

    public function getById(string $id)
    {
        return $this->locationType->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->locationType->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update(string $id, array $attributes)
    {
        $locationType = $this->locationType->findOrFail($id)->update([
            'name' => $attributes['name'],
        ]);
        return $locationType;
    }

    public function delete(string $id)
    {
        return $this->locationType->find($id)->update([
            'is_active' => 0,
        ]);
    }
}
