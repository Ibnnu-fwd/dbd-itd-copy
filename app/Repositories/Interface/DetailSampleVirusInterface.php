<?php

namespace App\Repositories\Interface;

interface DetailSampleVirusInterface
{
    public function getById($id);
    public function store($attributes, $detailSampleVirusId);
    public function delete($id);
    public function deleteDetailSampleVirusMorphotype($detailSampleMorphotypeId);
}
