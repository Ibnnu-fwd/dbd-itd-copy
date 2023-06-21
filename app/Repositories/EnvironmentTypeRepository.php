<?php

namespace App\Repositories;

use App\Models\EnvironmentType;
use App\Repositories\Interface\EnvironmentTypeInterface;

class EnvironmentTypeRepository implements EnvironmentTypeInterface
{
    private $environmentType;

    public function __construct(EnvironmentType $environmentType)
    {
        $this->environmentType = $environmentType;
    }

    public function getAll()
    {
        return $this->environmentType->all();
    }

    public function getById($id)
    {
        return $this->environmentType->findOrFail($id);
    }

    public function store($attributes)
    {
        return $this->environmentType->create($attributes);
    }

    public function update($id, $attributes)
    {
        return $this->environmentType->findOrFail($id)->update($attributes);
    }

    public function destroy($id)
    {
        return $this->environmentType->findOrFail($id)->delete();
    }
}
