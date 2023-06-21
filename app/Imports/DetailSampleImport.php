<?php

namespace App\Imports;

use App\Models\DetailSampleVirus;
use App\Models\Virus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DetailSampleImport implements WithMultipleSheets
{

    private $sampleId;
    public function __construct($sampleId)
    {
        $this->sampleId = $sampleId;
    }

    public function sheets(): array
    {
        $detailSamples = DetailSampleVirus::where('sample_id', $this->sampleId)->with('detailSampleMorphotypes', 'detailSampleMorphotypes.morphotype', 'detailSampleMorphotypes.detailSampleSerotypes', 'virus')->get()->groupBy('virus_id');

        $sheets = [];

        // buat worksheet untuk setiap virus,  dan beri nama sesuai nama virus
        foreach ($detailSamples as $detailSample) {
            $virus = Virus::find($detailSample[0]->virus_id);
            $sheets[] = new DetailSampleSheetImport($detailSample[0]->virus_id, $detailSample, $virus->name);
        }


        return $sheets;
    }
}
