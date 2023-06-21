<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\SerotypeInterface;
use App\Repositories\Interface\VirusInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SerotypeController extends Controller
{
    private $serotype;

    public function __construct(SerotypeInterface $serotype) {
        $this->serotype = $serotype;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables()
            ->of($this->serotype->getAll())
            ->addColumn('name', function($data) {
                return $data->name ;
            })
            ->addColumn('action', function($data) {
                return view('admin.serotype.column.action', compact('data'));
            })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.serotype.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.serotype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('serotypes', 'name')->where('is_active', 1)],
        ], [
            'name.required' => 'Nama serotipe harus diisi',
            'name.unique' => 'Nama serotipe sudah ada',
        ]);

        $this->serotype->create($request->all());
        return redirect()->route('admin.serotype.index')->with('success', 'Serotipe berhasil ditambahkan');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view('admin.serotype.edit', [
            'serotype' => $this->serotype->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('serotypes', 'name')->where('is_active', 1)->ignore($id)],
        ]);

        $this->serotype->update($id, $request->all());
        return redirect()->route('admin.serotype.index')->with('success', 'Serotipe berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->serotype->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Serotipe berhasil dihapus'
        ]);
    }
}
