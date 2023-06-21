<?php

namespace App\Repositories;

use App\Models\Province;
use App\Repositories\Interface\ProvinceInterface;
use Illuminate\Support\Facades\DB;

class ProvinceRepository implements ProvinceInterface
{
    private $province;

    public function __construct(Province $province) {
        $this->province = $province;
    }

    public function getAll()
    {
        return $this->province->with('regencies')->get();
    }
    public function getById(string $id)
    {

    }
    public function create(array $data)
    {
        return $this->province->create($data);
    }

    public function update(string $id, array $data)
    {

    }

    public function delete(string $id)
    {
        return $this->province->findOrFail($id)->update(['is_active' => 0]);
    }
}
