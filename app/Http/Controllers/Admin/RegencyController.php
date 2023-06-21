<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\ProvinceInterface;
use App\Repositories\Interface\RegencyInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegencyController extends Controller
{
    private $regency;
    private $province;

    public function __construct(RegencyInterface $regency, ProvinceInterface $province)
    {
        $this->regency = $regency;
        $this->province = $province;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->regency->getAll())
                ->addColumn('regency', function ($data) {
                    return $data->name;
                })
                ->addColumn('province', function($data) {
                    return $data->province->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.regency.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.regency.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.regency.create');
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

    // CUSTOM FUNCTION
    public function list(Request $regency)
    {
        $regency = $this->regency->getByProvince($regency->province_id);
        return response()->json($regency);
    }
}
