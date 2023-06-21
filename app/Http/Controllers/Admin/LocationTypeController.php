<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\LocationTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class LocationTypeController extends Controller
{
    private $locationType;

    public function __construct(LocationTypeInterface $locationType)
    {
        $this->locationType = $locationType;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->locationType->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.location-type.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.location-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.location-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('location_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ]);

        $this->locationType->create($request->all());
        return redirect()->route('admin.location-type.index')->with('success', 'Jenis lokasi baru berhasil ditambahkan!');
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
        return view('admin.location-type.edit', [
            'locationType' => $this->locationType->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('location_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ]);

        $this->locationType->update($id, $request->all());
        return redirect()->route('admin.location-type.index')->with('success', 'Jenis lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->locationType->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Jenis lokasi berhasil dihapus!'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis lokasi gagal dihapus!'
            ]);
        }
    }
}
