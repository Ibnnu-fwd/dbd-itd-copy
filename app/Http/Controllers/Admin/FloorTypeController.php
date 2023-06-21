<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\FloorTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FloorTypeController extends Controller
{
    private $floorType;

    public function __construct(FloorTypeInterface $floorType)
    {
        $this->floorType = $floorType;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->floorType->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.floor-type.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.floor-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.floor-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('floor_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ], [
            'name.required' => 'Jenis lantai tidak boleh kosong',
            'name.unique' => 'Jenis lantai sudah ada'
        ]);

        $this->floorType->create($request->all());
        return redirect()->route('admin.floor-type.index')->with('success', 'Jenis lantai berhasil ditambahkan');
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
        return view('admin.floor-type.edit', [
            'floorType' => $this->floorType->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('floor_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ], [
            'name.required' => 'Jenis lantai tidak boleh kosong',
            'name.unique' => 'Jenis lantai sudah ada'
        ]);

        $this->floorType->update($id, $request->all());
        return redirect()->route('admin.floor-type.index')->with('success', 'Jenis lantai berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->floorType->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Jenis lantai berhasil dihapus'
        ]);
    }
}
