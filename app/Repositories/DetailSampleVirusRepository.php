<?php

namespace App\Repositories;

use App\Models\DetailSampleMorphotype;
use App\Models\DetailSampleSerotype;
use App\Models\DetailSampleVirus;
use App\Repositories\Interface\DetailSampleVirusInterface;
use Illuminate\Support\Facades\DB;

class DetailSampleVirusRepository implements DetailSampleVirusInterface
{
    private $detailSampleVirus;
    private $detailSampleMorphotype;
    private $detailSampleSerotype;

    public function __construct(
        DetailSampleVirus $detailSampleVirus,
        DetailSampleMorphotype $detailSampleMorphotype,
        DetailSampleSerotype $detailSampleSerotype
    ) {
        $this->detailSampleVirus        = $detailSampleVirus;
        $this->detailSampleMorphotype   = $detailSampleMorphotype;
        $this->detailSampleSerotype     = $detailSampleSerotype;
    }

    public function getById($id)
    {
        return $this->detailSampleVirus->with('detailSampleMorphotypes', 'virus', 'detailSampleMorphotypes.detailSampleSerotypes', 'detailSampleMorphotypes.morphotype', 'detailSampleMorphotypes.detailSampleSerotypes.serotype')->find($id);
    }

    public function store($attributes, $detailSampleVirusId)
    {
        DB::beginTransaction();

        try {
            foreach ($attributes['morphotypeValues'] as $morphotype) {
                //    check if morphotype is exist, if exist then update, if not exist then create
                $detailMorphotypeId = $this->detailSampleMorphotype->updateOrCreate(
                    [
                        'detail_sample_virus_id' => $detailSampleVirusId,
                        'morphotype_id' => $morphotype['morphotype_id']
                    ],
                    [
                        'amount' => $morphotype['amount']
                    ]
                );

                foreach ($morphotype['serotypes'] as $serotype) {
                    $this->detailSampleSerotype->updateOrCreate(
                        [
                            'detail_sample_morphotype_id' => $detailMorphotypeId->id,
                            'serotype_id' => $serotype['serotype_id']
                        ],
                        [
                            'amount' => $serotype['amount']
                        ]
                    );
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $this->detailSampleSerotype->whereHas('detailSampleMorphotype', function ($query) use ($id) {
                $query->where('detail_sample_virus_id', $id);
            })->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleMorphotype->where('detail_sample_virus_id', $id)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleVirus->where('id', $id)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function deleteDetailSampleVirusMorphotype($detailSampleMorphotypeId)
    {
        DB::beginTransaction();

        try {
            $this->detailSampleSerotype->where('detail_sample_morphotype_id', $detailSampleMorphotypeId)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            $this->detailSampleMorphotype->where('id', $detailSampleMorphotypeId)->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }
}
