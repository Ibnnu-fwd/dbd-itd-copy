<?php

namespace App\Repositories;

use App\Models\Abj;
use App\Models\DetailKsh;
use App\Models\Ksh;
use App\Models\TpaType;
use App\Repositories\Interface\DetailKshInterface;
use Illuminate\Support\Facades\DB;

class DetailKshRepository implements DetailKshInterface
{
    private $abj;
    private $detailKsh;
    private $tpaType;
    private $ksh;

    public function __construct(Abj $abj, DetailKsh $detailKsh, TpaType $tpaType, Ksh $ksh)
    {
        $this->abj = $abj;
        $this->detailKsh = $detailKsh;
        $this->tpaType = $tpaType;
        $this->ksh = $ksh;
    }

    public function getById($id)
    {
        return $this->detailKsh->with('tpaType', 'ksh')->find($id);
    }

    public function create($attributes, $id)
    {
        DB::beginTransaction();

        try {
            $this->detailKsh->create([
                'ksh_id' => $id,
                'house_name' => $attributes['house_name'],
                'house_owner' => $attributes['house_owner'],
                'tpa_type_id' => $attributes['tpa_type_id'],
                'larva_status' => $attributes['larva_status'] == 1 ? true : false,
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ]);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        try {
            $abj = $this->abj->where('ksh_id', $id)->first();

            $negativeLarvaCount = 0;
            $ksh = $this->ksh->find($id);
            $detailKsh = $ksh->detailKsh;

            if ($abj == null) {
                foreach ($detailKsh as $detail) {
                    if ($detail->larva_status == 0) {
                        $negativeLarvaCount++;
                    }
                }
                $this->abj->create([
                    'regency_id' => $ksh->regency_id,
                    'district_id' => $ksh->district_id,
                    'village_id' => $ksh->village_id,
                    'ksh_id' => $id,
                    'abj_total' => ($negativeLarvaCount / $detailKsh->count()) * 100
                ]);
            } else {
                foreach ($detailKsh as $detail) {
                    if ($detail->larva_status == 0) {
                        $negativeLarvaCount++;
                    }
                }
                $abj->update([
                    'abj_total' => ($negativeLarvaCount / $detailKsh->count()) * 100
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        DB::commit();
    }

    public function edit($attributes, $id)
    {
        DB::beginTransaction();

        try {
            $this->detailKsh->find($id)->update([
                'house_name' => $attributes['house_name'],
                'house_owner' => $attributes['house_owner'],
                'tpa_type_id' => $attributes['tpa_type_id'],
                'larva_status' => $attributes['larva_status'] == 1 ? true : false,
                'latitude' => $attributes['latitude'],
                'longitude' => $attributes['longitude'],
            ]);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        try {
            $detailKsh = $this->detailKsh->find($id);
            $ksh = $detailKsh->ksh;
            $abj = $this->abj->where('ksh_id', $ksh->id)->first();

            $negativeLarvaCount = 0;
            $detailKsh = $ksh->detailKsh;

            foreach ($detailKsh as $detail) {
                if ($detail->larva_status == 0) {
                    $negativeLarvaCount++;
                }
            }

            $abj->update([
                'abj_total' => ($negativeLarvaCount / $detailKsh->count()) * 100
            ]);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        DB::commit();
    }
}
