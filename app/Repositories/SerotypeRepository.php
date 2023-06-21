<?php

namespace App\Repositories;

use App\Models\Serotype;
use App\Repositories\Interface\SerotypeInterface;

class SerotypeRepository implements SerotypeInterface
{
    private $serotype;

    public function __construct(Serotype $serotype) {
        $this->serotype = $serotype;
    }

    public function getAll()
    {
        return $this->serotype->active()->get();
    }

    public function getById($id)
    {
        return $this->serotype->active()->find($id);
    }

    public function create(array $attributes)
    {
        return $this->serotype->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update($id, array $attributes)
    {
        return $this->serotype->find($id)->update([
            'name' => $attributes['name'],
        ]);
    }

    public function delete($id)
    {
        return $this->serotype->find($id)->update([
            'is_active' => 0,
        ]);
    }
}
