<?php

namespace App\Repositories;

use App\Repositories\Interface\SettlementTypeInterface;
use App\Models\SettlementType;

class SettlementTypeRepository implements SettlementTypeInterface
{
    private $settlementType;

    public function __construct(SettlementType $settlementType) {
        $this->settlementType = $settlementType;
    }

    public function getAll()
    {
        return $this->settlementType->active()->get();
    }

    public function getById($id)
    {
        return $this->settlementType->active()->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->settlementType->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update($id, array $attributes)
    {
        $settlementType = $this->settlementType->findOrFail($id);
        $settlementType->update([
            'name' => $attributes['name'],
        ]);
        return $settlementType;
    }

    public function delete($id)
    {
        $settlementType = $this->settlementType->findOrFail($id);
        $settlementType->update([
            'is_active' => 0,
        ]);
        return $settlementType;
    }
}
