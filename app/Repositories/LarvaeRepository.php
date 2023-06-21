<?php

namespace App\Repositories;

use App\Models\DetailLarvae;
use App\Models\Larvae;
use App\Repositories\Interface\LarvaeInterface;
use Illuminate\Support\Facades\DB;

class LarvaeRepository implements LarvaeInterface
{
    private $larvae;
    private $detailLarvae;

    public function __construct(Larvae $larvae, DetailLarvae $detailLarvae)
    {
        $this->larvae = $larvae;
        $this->detailLarvae = $detailLarvae;
    }

    public function getAll()
    {
        return $this->larvae->with('regency', 'district', 'village', 'locationType', 'settlementType', 'environmentType', 'buildingType', 'floorType', 'createdBy', 'updatedBy', 'detailLarvaes', 'detailLarvaes.tpaType')->orderBy('larva_code', 'desc')->get();
    }

    public function getById($id)
    {
        return $this->larvae->with('detailLarvaes.tpaType')->find($id);
    }

    public function create($attributes)
    {
        DB::beginTransaction();
        try {
            $larvae = $this->larvae->create([
                'larva_code'           => $this->larvae->generateLarvaCode(),
                'regency_id'            => $attributes['regency_id'],
                'district_id'           => $attributes['district_id'],
                'village_id'            => $attributes['village_id'],
                'location_type_id'      => $attributes['location_type_id'],
                'settlement_type_id'    => $attributes['settlement_type_id'],
                'environment_type_id'   => $attributes['environment_type_id'],
                'building_type_id'      => $attributes['building_type_id'],
                'floor_type_id'         => $attributes['floor_type_id'],
                'address'               => $attributes['address'],
                'latitude'              => $attributes['latitude'],
                'longitude'             => $attributes['longitude']
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        try {
            foreach ($attributes['detailLarva'] as $detailLarva) {
                $this->detailLarvae->create([
                    'larva_id'          => $larvae->id,
                    'tpa_type_id'       => $detailLarva['tpa_type_id'],
                    'amount_larva'      => $detailLarva['amount_larva'],
                    'amount_egg'        => $detailLarva['amount_egg'],
                    'number_of_adults'  => $detailLarva['number_of_adults'],
                    'water_temperature' => $detailLarva['water_temperature'],
                    'salinity'          => $detailLarva['salinity'],
                    'ph'                => $detailLarva['ph'],
                    'aquatic_plant'    => $detailLarva['aquatic_plant'],
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function update($attributes, $id)
    {
        DB::beginTransaction();

        try {
            $larvae = $this->larvae->find($id);
            $larvae->update([
                'regency_id'            => $attributes['regency_id'],
                'district_id'           => $attributes['district_id'],
                'village_id'            => $attributes['village_id'],
                'location_type_id'      => $attributes['location_type_id'],
                'settlement_type_id'    => $attributes['settlement_type_id'],
                'environment_type_id'   => $attributes['environment_type_id'],
                'building_type_id'      => $attributes['building_type_id'],
                'floor_type_id'         => $attributes['floor_type_id'],
                'address'               => $attributes['address'],
                'latitude'              => $attributes['latitude'],
                'longitude'             => $attributes['longitude']
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function deleteDetail($id)
    {
        return $this->detailLarvae->find($id)->delete();
    }

    public function createDetail($attributes, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($attributes['detailLarva'] as $detailLarva) {
                $this->detailLarvae->updateOrCreate(
                    [
                        'id' => $detailLarva['id']
                    ],
                    [
                        'larva_id'          => $id,
                        'tpa_type_id'       => $detailLarva['tpa_type_id'],
                        'amount_larva'      => $detailLarva['amount_larva'],
                        'amount_egg'        => $detailLarva['amount_egg'],
                        'number_of_adults'  => $detailLarva['number_of_adults'],
                        'water_temperature' => $detailLarva['water_temperature'],
                        'salinity'          => $detailLarva['salinity'],
                        'ph'                => $detailLarva['ph'],
                        'aquatic_plant'    => $detailLarva['aquatic_plant'],
                    ]
                );
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function createDetailNew($attributes, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($attributes['detailLarva'] as $detailLarva) {
                $this->detailLarvae->create([
                    'larva_id'          => $id,
                    'tpa_type_id'       => $detailLarva['tpa_type_id'],
                    'amount_larva'      => $detailLarva['amount_larva'],
                    'amount_egg'        => $detailLarva['amount_egg'],
                    'number_of_adults'  => $detailLarva['number_of_adults'],
                    'water_temperature' => $detailLarva['water_temperature'],
                    'salinity'          => $detailLarva['salinity'],
                    'ph'                => $detailLarva['ph'],
                    'aquatic_plant'    => $detailLarva['aquatic_plant'],
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function filterMonth($month)
    {
        return $this->larvae->with('regency', 'district', 'village', 'locationType', 'settlementType', 'environmentType', 'buildingType', 'floorType', 'createdBy', 'updatedBy', 'detailLarvaes', 'detailLarvaes.tpaType')->orderBy('larva_code', 'desc')->where([
            [DB::raw('MONTH(created_at)'), $month]
        ])->get();
    }

    public function filterDateRange($startDate, $endDate)
    {
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        return $this->larvae->with('regency', 'district', 'village', 'locationType', 'settlementType', 'environmentType', 'buildingType', 'floorType', 'createdBy', 'updatedBy', 'detailLarvaes', 'detailLarvaes.tpaType')->orderBy('larva_code', 'desc')->where([
            ['created_at', '>=', $startDate],
            ['created_at', '<=', $endDate]
        ])->get();
    }

    public function getTotalLarva()
    {
        $detailLarva = $this->detailLarvae->all();
        $totalLarva = 0;
        foreach ($detailLarva as $detail) {
            $totalLarva += $detail->amount_larva;
        }

        return $totalLarva;
    }

    public function filterMapYear($year)
    {
        return $this->larvae->with('regency', 'district', 'village', 'locationType', 'settlementType', 'environmentType', 'buildingType', 'floorType', 'createdBy', 'updatedBy', 'detailLarvaes', 'detailLarvaes.tpaType')->orderBy('larva_code', 'desc')->where([
            [DB::raw('YEAR(created_at)'), $year]
        ])->get();
    }

    public function filterMapRegency($regency_id)
    {
        return $this->larvae->with('regency', 'district', 'village', 'locationType', 'settlementType', 'environmentType', 'buildingType', 'floorType', 'createdBy', 'updatedBy', 'detailLarvaes', 'detailLarvaes.tpaType')->orderBy('larva_code', 'desc')->where([
            ['regency_id', $regency_id]
        ])->get();
    }
}
