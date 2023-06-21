<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\BuildingTypeInterface;
use App\Repositories\Interface\DistrictInterface;
use App\Repositories\Interface\EnvironmentTypeInterface;
use App\Repositories\Interface\FloorTypeInterface;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\LocationTypeInterface;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SettlementTypeInterface;
use App\Repositories\Interface\TpaTypeInterface;
use App\Repositories\Interface\VillageInterface;
use Illuminate\Http\Request;

class LarvaeController extends Controller
{
    private $regency;
    private $district;
    private $village;
    private $larvae;
    private $locationType;
    private $settlementType;
    private $environmentType;
    private $buildingType;
    private $floorType;
    private $tpaType;

    public function __construct(
        RegencyInterface $regency,
        DistrictInterface $district,
        VillageInterface $village,
        LarvaeInterface $larvae,
        LocationTypeInterface $locationType,
        SettlementTypeInterface $settlementType,
        EnvironmentTypeInterface $environmentType,
        BuildingTypeInterface $buildingType,
        FloorTypeInterface $floorType,
        TpaTypeInterface $tpaType
    ) {
        $this->regency = $regency;
        $this->district = $district;
        $this->village = $village;
        $this->larvae = $larvae;
        $this->locationType = $locationType;
        $this->settlementType = $settlementType;
        $this->environmentType = $environmentType;
        $this->buildingType = $buildingType;
        $this->floorType = $floorType;
        $this->tpaType = $tpaType;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->larvae->getAll())
                ->addColumn('larva_code', function ($data) {
                    return $data->larva_code;
                })
                ->addColumn('district', function ($data) {
                    return ucwords(strtolower($data->district->name));
                })
                ->addColumn('location', function ($data) {
                    return $data->locationType->name;
                })
                ->addColumn('settlement', function ($data) {
                    return $data->settlementType->name;
                })
                ->addColumn('environment', function ($data) {
                    return $data->environmentType->name;
                })
                ->addColumn('building', function ($data) {
                    return $data->buildingType->name;
                })
                ->addColumn('floor', function ($data) {
                    return $data->floorType->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.larvae.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.larvae.index', [
            'larvae' => $this->larvae->getAll(),
            'months' => [
                '1' => 'Januari',
                '2' => 'Februari',
                '3' => 'Maret',
                '4' => 'April',
                '5' => 'Mei',
                '6' => 'Juni',
                '7' => 'Juli',
                '8' => 'Agustus',
                '9' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.larvae.create', [
            'regencies' => $this->regency->getAll(),
            'districts' => $this->district->getAll(),
            'villages' => $this->village->getAll(),
            'locationTypes' => $this->locationType->getAll(),
            'settlementTypes' => $this->settlementType->getAll(),
            'environmentTypes' => $this->environmentType->getAll(),
            'buildingTypes' => $this->buildingType->getAll(),
            'floorTypes' => $this->floorType->getAll(),
            'tpaTypes' => $this->tpaType->getAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'address' => ['required'],
            'location_type_id' => ['required'],
            'settlement_type_id' => ['required'],
            'environment_type_id' => ['required'],
            'building_type_id' => ['required'],
            'floor_type_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        try {
            $this->larvae->create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->larvae->getById($id)->detailLarvaes)
                ->addColumn('tpa', function ($data) {
                    return $data->tpaType->name;
                })
                ->addColumn('amount_larva', function ($data) {
                    return $data->amount_larva;
                })
                ->addColumn('amount_egg', function ($data) {
                    return $data->amount_egg;
                })
                ->addColumn('number_of_adults', function ($data) {
                    return $data->number_of_adults;
                })
                ->addColumn('water_temperature', function ($data) {
                    return $data->water_temperature;
                })
                ->addColumn('salinity', function ($data) {
                    return $data->salinity;
                })
                ->addColumn('ph', function ($data) {
                    return $data->ph;
                })
                ->addColumn('aquatic_plant', function ($data) {
                    return $data->getAquaticPlantTranslation();
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.larvae.show', [
            'larva' => $this->larvae->getById($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.larvae.edit', [
            'larva' => $this->larvae->getById($id),
            'regencies' => $this->regency->getAll(),
            'districts' => $this->district->getAll(),
            'villages' => $this->village->getAll(),
            'locationTypes' => $this->locationType->getAll(),
            'settlementTypes' => $this->settlementType->getAll(),
            'environmentTypes' => $this->environmentType->getAll(),
            'buildingTypes' => $this->buildingType->getAll(),
            'floorTypes' => $this->floorType->getAll(),
            'tpaTypes' => $this->tpaType->getAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'address' => ['required'],
            'location_type_id' => ['required'],
            'settlement_type_id' => ['required'],
            'environment_type_id' => ['required'],
            'building_type_id' => ['required'],
            'floor_type_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        try {
            $this->larvae->update($request->all(), $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // CUSTOM FUNCTION
    public function createDetail($id)
    {
        return view('admin.larvae.detail.create', [
            'larva' => $this->larvae->getById($id),
            'tpaTypes' => $this->tpaType->getAll(),
        ]);
    }

    public function storeDetail(Request $request, string $id)
    {
        try {
            $this->larvae->createDetail($request->all(), $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail larva berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function storeDetailNew(Request $request, string $id)
    {
        try {
            $this->larvae->createDetailNew($request->all(), $id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail larva berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function editDetail($id)
    {
        return view('admin.larvae.detail.edit', [
            'larva' => $this->larvae->getById($id),
            'tpaTypes' => $this->tpaType->getAll(),
        ]);
    }

    public function deleteDetail($id)
    {
        try {
            $this->larvae->deleteDetail($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Detail larva berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function filterMonth(Request $request)
    {
        $larvae = $this->larvae->filterMonth($request->month);
        $data = $larvae->map(function ($data) {
            return [
                'DT_RowIndex' => $data->id,
                'larva_code' => $data->larva_code,
                'district' => ucwords(strtolower($data->district->name)),
                'location' => $data->locationType->name,
                'settlement' => $data->settlementType->name,
                'environment' => $data->environmentType->name,
                'building' => $data->buildingType->name,
                'floor' => $data->floorType->name,
                'action' => view('admin.larvae.column.action', compact('data'))->render(),
            ];
        });
        return response()->json([
            'data' => $data,
            'larvae' => $larvae,
        ]);
    }

    public function filterDateRange(Request $request)
    {
        $larvae = $this->larvae->filterDateRange($request->start_date, $request->end_date);
        $data = $larvae->map(function ($data) {
            return [
                'DT_RowIndex' => $data->id,
                'larva_code' => $data->larva_code,
                'district' => ucwords(strtolower($data->district->name)),
                'location' => $data->locationType->name,
                'settlement' => $data->settlementType->name,
                'environment' => $data->environmentType->name,
                'building' => $data->buildingType->name,
                'floor' => $data->floorType->name,
                'action' => view('admin.larvae.column.action', compact('data'))->render(),
            ];
        });
        return response()->json([
            'data' => $data,
            'larvae' => $larvae,
        ]);
    }

    public function import(Request $request)
    {
    }
}
