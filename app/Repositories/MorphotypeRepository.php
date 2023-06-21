<?php

namespace App\Repositories;

use App\Models\Morphotype;
use App\Repositories\Interface\MorphotypeInterface;

class MorphotypeRepository implements MorphotypeInterface
{
    private $morphotype;

    public function __construct(Morphotype $morphotype)
    {
        $this->morphotype = $morphotype;
    }

    public function getAll()
    {
        return $this->morphotype->active()->get();
    }

    public function getById($id)
    {
        return $this->morphotype->active()->find($id);
    }

    public function create(array $attributes)
    {
        return $this->morphotype->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update($id, array $attributes)
    {
        return $this->morphotype->find($id)->update([
            'name' => $attributes['name'],
        ]);
    }

    public function delete($id)
    {
        return $this->morphotype->find($id)->update(['is_active' => false]);
    }
}
