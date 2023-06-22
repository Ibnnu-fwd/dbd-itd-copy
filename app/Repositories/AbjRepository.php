<?php

namespace App\Repositories;

use App\Models\Abj;
use App\Models\Ksh;
use App\Repositories\Interface\AbjInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AbjRepository implements AbjInterface
{
    private $abj;
    private $ksh;

    public function __construct(Abj $abj, Ksh $ksh)
    {
        $this->abj = $abj;
        $this->ksh = $ksh;
    }

    public function getAllGroupByDistrict()
    {
        $abj = $this->abj->with('district', 'village', 'ksh', 'ksh.district', 'ksh.village', 'ksh.detailKsh', 'ksh.detailKsh.tpaType')->get()->groupBy('district_id');

        $data = [];

        foreach ($abj as $key => $value) {
            $data[$key]['district'] = $value->first()->district->name;
            $data[$key]['location'] = $value->map(function ($item) {
                return [
                    'village' => $item->ksh->village,
                    'coordinate' => $item->ksh->detailKsh->map(function ($item) {
                        return $item;
                    })
                ];
            });
            $data[$key]['abj'] = $value->map(function ($item) {
                return [
                    'abj_total' => $item->abj_total,
                    'created_at' => $item->created_at,
                ];
            });
            $data[$key]['total_sample'] = $value->count();
            $data[$key]['total_check'] = $value->sum(function ($item) {
                return $item->ksh->detailKsh->count();
            });
            $data[$key]['min_long'] = $value->min(function ($item) {
                return $item->ksh->detailKsh->map(function ($item) {
                    return $item->longitude;
                })->min();
            });
            $data[$key]['max_long'] = $value->max(function ($item) {
                return $item->ksh->detailKsh->map(function ($item) {
                    return $item->longitude;
                })->max();
            });
            $data[$key]['abj_total'] = (int) $value->sum('abj_total') / $value->count();
            // indonesia
            $data[$key]['created_at'] = Carbon::parse($value->first()->created_at)->format('d-m-Y');
        }

        return $data;
    }

    public function getGeoJson()
    {
    }
    public function cuttingData($attributes)
    {

        DB::beginTransaction();
        try {
            $ksh = $this->ksh->create([
                'latitude' => '-8.9',
                'longitude' => '113.723081',
                'regency_id' => $attributes['regency_id'],
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'created_by' => auth()->user()->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        try {
            $this->abj->create([
                'district_id' => $attributes['district_id'],
                'village_id' => $attributes['village_id'],
                'abj_total' => $attributes['abj_total'],
                'ksh_id' => $ksh->id
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        DB::commit();
    }
}
