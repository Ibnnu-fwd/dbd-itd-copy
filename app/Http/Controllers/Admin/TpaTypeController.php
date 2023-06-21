<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\TpaTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TpaTypeController extends Controller
{
    private $tpaType;

    public function __construct(TpaTypeInterface $tpaType)
    {
        $this->tpaType = $tpaType;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->tpaType->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.tpa-type.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.tpa-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tpa-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('tpa_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
        ]);

        $this->tpaType->create($request->all());
        return redirect()->route('admin.tpa-type.index')->with('success', 'Tipe TPA berhasil ditambahkan');
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
        return view('admin.tpa-type.edit', [
            'tpaType' => $this->tpaType->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('tpa_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
        ], [
            'name.required' => 'Nama jenis TPA tidak boleh kosong',
            'name.unique' => 'Nama jenis TPA sudah terdaftar'
        ]);

        $this->tpaType->update($id, $request->all());
        return redirect()->route('admin.tpa-type.index')->with('success', 'Tipe TPA berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->tpaType->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Tipe TPA berhasil dihapus'
        ]);
    }
}
