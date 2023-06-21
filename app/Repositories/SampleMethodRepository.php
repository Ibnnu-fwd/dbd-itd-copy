<?php

namespace App\Repositories;

use App\Models\SampleMethod;
use App\Repositories\Interface\SampleMethodInterface;

class SampleMethodRepository implements SampleMethodInterface
{
    private $sampleMethod;

    public function __construct(SampleMethod $sampleMethod) {
        $this->sampleMethod = $sampleMethod;
    }

    public function getAll()
    {
        return $this->sampleMethod->active()->get();
    }

    public function getById($id)
    {
        return $this->sampleMethod->active()->find($id);
    }

    public function create(array $attributes)
    {
        return $this->sampleMethod->create([
            'name' => $attributes['name'],
        ]);
    }

    public function update($id, array $attributes)
    {
        return $this->sampleMethod->find($id)->update([
            'name' => $attributes['name'],
        ]);
    }

    public function delete($id)
    {
        return $this->sampleMethod->find($id)->update(['is_active' => 0]);
    }
}
