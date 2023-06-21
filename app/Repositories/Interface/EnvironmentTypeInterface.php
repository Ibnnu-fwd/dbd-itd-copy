<?php

namespace App\Repositories\Interface;

interface EnvironmentTypeInterface
{
    public function getAll();
    public function getById($id);
    public function store($attributes);
    public function update($id, $attributes);
    public function destroy($id);
}
