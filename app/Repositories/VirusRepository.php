<?php

namespace App\Repositories;

use App\Models\Virus;
use App\Repositories\Interface\VirusInterface;
use Illuminate\Support\Facades\Storage;

class VirusRepository implements VirusInterface
{
    private $virus;

    public function __construct(Virus $virus)
    {
        $this->virus = $virus;
    }

    public function getAll()
    {
        return $this->virus->active()->get();
    }

    public function getById($id)
    {
        return $this->virus->active()->findOrFail($id);
    }

    public function create(array $attributes)
    {
        if (isset($attributes['image'])) {
            $filename = uniqid() . '.' . $attributes['image']->extension();
            $attributes['image']->storeAs('public/virus', $filename);

            $attributes['image'] = $filename;
        }

        return $this->virus->create([
            'name' => $attributes['name'],
            'description' => $attributes['description'] ?? null,
            'image' => $attributes['image'] ?? null
        ]);
    }

    public function update($id, array $attributes)
    {
        $virus = $this->virus->findOrFail($id);
        if (isset($attributes['image'])) {
            $oldFile = $virus->image;
            if ($oldFile) {
                Storage::delete('public/virus/' . $oldFile);
            }

            $filename = uniqid() . '.' . $attributes['image']->extension();
            $attributes['image']->storeAs('public/virus', $filename);

            $attributes['image'] = $filename;
        }

        return $virus->update([
            'name' => $attributes['name'],
            'description' => $attributes['description'] ?? null,
            'image' => $attributes['image'] ?? null
        ]);
    }

    public function delete($id)
    {
        $virus = $this->virus->findOrFail($id);
        $virus->is_active = false;
        $virus->save();

        return $virus;
    }
}
