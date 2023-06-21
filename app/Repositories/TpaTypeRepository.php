<?php

namespace App\Repositories;

use App\Models\TpaType;
use App\Repositories\Interface\TpaTypeInterface;

class TpaTypeRepository implements TpaTypeInterface
{
    private $tpaType;

    public function __construct(TpaType $tpaType) {
        $this->tpaType = $tpaType;
    }

    public function getAll()
    {
        return $this->tpaType->all();
    }

    public function getById(string $id)
    {
        return $this->tpaType->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->tpaType->create($attributes);
    }

    public function update(string $id, array $attributes)
    {
        $tpaType = $this->getById($id);
        $tpaType->update($attributes);
        return $tpaType;
    }

    public function delete(string $id)
    {
        return $this->tpaType->findOrFail($id)->update(['is_active' => 0]);
    }
}
