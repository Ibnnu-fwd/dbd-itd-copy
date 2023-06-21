<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\DistrictInterface;
use App\Repositories\Interface\ProvinceInterface;
use App\Repositories\Interface\RegencyInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DistrictController extends Controller
{
    private $district;
    private $province;
    private $regency;

    public function __construct(DistrictInterface $district, ProvinceInterface $province, RegencyInterface $regency)
    {
        $this->district = $district;
        $this->province = $province;
        $this->regency = $regency;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->district->getAll())
                ->addColumn('district', function ($data) {
                    return $data->name;
                })
                ->addColumn('regency', function ($data) {
                    return $data->regency->name;
                })
                ->addColumn('province', function ($data) {
                    return $data->regency->province->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.district.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.district.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.district.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Custom Function
    public function list(Request $request)
    {
        $districts = $this->district->getByRegency($request->regency_id);
        return response()->json($districts);
    }
}
