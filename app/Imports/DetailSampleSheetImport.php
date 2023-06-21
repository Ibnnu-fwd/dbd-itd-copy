<?php

namespace App\Imports;

use App\Models\DetailSampleMorphotype;
use App\Models\DetailSampleSerotype;
use App\Models\Morphotype;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailSampleSheetImport implements ToModel, WithStartRow
{
    public $virusId;
    public $detailSample;
    public $title;

    public function __construct($virusId, $detailSample, $title)
    {
        $this->virusId = $virusId;
        $this->detailSample = $detailSample;
        $this->title = $title;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $morphotypeName = 'Morfotipe ' . $row[0];

        $data = [];
        $data = [
            'morphotype_id' => Morphotype::where('name', $morphotypeName)->first()->id,
            'amount' => $row[1],
            'serotypes' => [
                0 => $row[2],
                1 => $row[3],
                2 => $row[4],
                3 => $row[5],
            ],
        ];

        // check if there's new morphotype in detail sample, if there is, create new morphotype. else, update existing morphotype
        $morphotype = DetailSampleMorphotype::where('detail_sample_virus_id', $this->detailSample[0]->id)->where('morphotype_id', $data['morphotype_id'])->first();
        if ($morphotype) {
            $morphotype->update([
                'amount' => $data['amount'],
            ]);
            $morphotype->detailSampleSerotypes()->delete();
            foreach ($data['serotypes'] as $key => $serotype) {
                if ($serotype) {
                    DetailSampleSerotype::create([
                        'detail_sample_morphotype_id' => $morphotype->id,
                        'serotype_id' => $key + 1,
                        'amount' => $serotype,
                    ]);
                }
            }
        } else {
            $morphotype = DetailSampleMorphotype::create([
                'detail_sample_virus_id' => $this->detailSample[0]->id,
                'morphotype_id' => $data['morphotype_id'],
                'amount' => $data['amount'],
            ]);
            foreach ($data['serotypes'] as $key => $serotype) {
                if ($serotype) {
                    DetailSampleSerotype::create([
                        'detail_sample_morphotype_id' => $morphotype->id,
                        'serotype_id' => $key + 1,
                        'amount' => $serotype,
                    ]);
                }
            }
        }
    }
}
