<?php

namespace App\Repositories\Interface;

interface VillageInterface
{
    public function getAll();
    public function getById($id);
    public function create($attributes);
    public function update($id, $attributes);
    public function delete($id);
    public function search($search);
    public function getByDistrict($districtId);
}
