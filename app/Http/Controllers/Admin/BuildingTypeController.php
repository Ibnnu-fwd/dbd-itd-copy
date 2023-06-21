<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\BuildingTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BuildingTypeController extends Controller
{
    private $buildingType;

    public function __construct(BuildingTypeInterface $buildingType) {
        $this->buildingType = $buildingType;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return datatables()
            ->of($this->buildingType->getAll())
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('action', function($data) {
                return view('admin.building-type.column.action', ['data' => $data]);
            })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.building-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.building-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('building_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
        ]);

        $this->buildingType->create($request->all());
        return redirect()->route('admin.building-type.index')->with('success', 'Jenis Bangunan Berhasil Ditambahkan');
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
        return view('admin.building-type.edit', [
            'buildingType' => $this->buildingType->getById($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('building_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })->ignore($id)],
        ]);

        $this->buildingType->update($id, $request->all());
        return redirect()->route('admin.building-type.index')->with('success', 'Jenis Bangunan Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->buildingType->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Jenis Bangunan Berhasil Dihapus'
        ]);
    }
}
