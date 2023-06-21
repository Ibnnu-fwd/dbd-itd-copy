<?php

namespace App\Repositories\Interface;

interface DetailKshInterface
{
    public function getById($id);
    public function create($attributes, $id);
    public function edit($attributes, $id);
}
