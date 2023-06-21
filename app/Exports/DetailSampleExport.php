<?php

namespace App\Exports;

use App\Models\DetailSampleVirus;
use App\Models\Virus;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DetailSampleExport implements WithMultipleSheets
{
    private $sampleId;

    public function __construct($sampleId)
    {
        $this->sampleId = $sampleId;
    }

    // create multiple sheets based on each virus
    public function sheets(): array
    {
        $detailSamples = DetailSampleVirus::where('sample_id', $this->sampleId)->with('detailSampleMorphotypes', 'detailSampleMorphotypes.morphotype', 'detailSampleMorphotypes.detailSampleSerotypes', 'virus')->get()->groupBy('virus_id');

        $sheets = [];

        // buat worksheet untuk setiap virus,  dan beri nama sesuai nama virus
        foreach ($detailSamples as $detailSample) {
            $virus = Virus::find($detailSample[0]->virus_id);
            $sheets[] = new DetailSampleSheet($detailSample[0]->virus_id, $detailSample, $virus->name);
        }


        return $sheets;
    }
}
