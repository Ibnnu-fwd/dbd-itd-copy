<?php

namespace App\Repositories\Interface;

interface RegencyInterface
{
    public function getAll();
    public function getById(string $id);
    public function create(array $attributes);
    public function update(string $id, array $attributes);
    public function delete(string $id);
    public function getByProvince(string $provinceId);
}
