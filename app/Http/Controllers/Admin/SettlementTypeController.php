<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\SettlementTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettlementTypeController extends Controller
{
    private $settlementType;

    public function __construct(SettlementTypeInterface $settlementType) {
        $this->settlementType = $settlementType;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return datatables()
            ->of($this->settlementType->getAll())
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('action', function($data) {
                return view('admin.settlement-type.column.action', ['data' => $data]);
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.settlement-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settlement-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('settlement_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
        ]);

        $this->settlementType->create($request->all());

        return redirect()->route('admin.settlement-type.index')->with('success', 'Jenis Pemukiman Berhasil Ditambahkan');
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
        return view('admin.settlement-type.edit', [
            'settlementType' => $this->settlementType->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('settlement_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })],
        ]);

        $this->settlementType->update($id, $request->all());
        return redirect()->route('admin.settlement-type.index')->with('success', 'Jenis Pemukiman Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->settlementType->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Jenis Pemukiman Berhasil Dihapus'
        ]);
    }
}
