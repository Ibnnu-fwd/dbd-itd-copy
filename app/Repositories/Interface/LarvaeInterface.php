<?php

namespace App\Repositories\Interface;

interface LarvaeInterface
{
    public function getAll();
    public function getById($id);
    public function create($attributes);
    public function update($attributes, $id);

    public function deleteDetail($id);
    public function createDetail($attributes, $id);
    public function createDetailNew($attributes, $id);

    public function filterMonth($month);
    public function filterDateRange($startDate, $endDate);

    public function getTotalLarva();

    public function filterMapYear($year);
    public function filterMapRegency($regency_id);
}
