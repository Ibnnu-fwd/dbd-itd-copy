<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\MorphotypeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class MorphotypeController extends Controller
{
    private $morphotype;

    public function __construct(MorphotypeInterface $morphotype) {
        $this->morphotype = $morphotype;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables()
            ->of($this->morphotype->getAll())
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('action', function($data) {
                return  view('admin.morphotype.column.action', compact('data'));
            })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.morphotype.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.morphotype.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('morphotypes', 'name')->where('is_active', true)],
        ]);

        try {
            $this->morphotype->create($request->all());
            return redirect()->route('admin.morphotype.index')->with('success', 'Morfotipe baru berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->route('admin.morphotype.index')->with('error', $th->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view('admin.morphotype.edit', [
            'morphotype' => $this->morphotype->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('morphotypes', 'name')->where('is_active', true)->ignore($id)],
        ]);

        try {
            $this->morphotype->update($id, $request->all());
            return redirect()->route('admin.morphotype.index')->with('success', 'Morfotipe berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->route('admin.morphotype.index')->with('error', 'Terjadi kesalahan saat memperbarui morfotipe');
        }
    }

    public function destroy(string $id)
    {
        $this->morphotype->delete($id);
        return response()->json(['status' => true, 'message' => 'Morfotipe berhasil dihapus']);
    }

    // ------------------ CUSTOM FUNCTION ------------------
    public function list()
    {
        $morphotypes = $this->morphotype->getAll();
        $arr_data = new Collection();
        foreach ($morphotypes as $morphotype) {
            $arr_data->push([
                'id' => $morphotype->id,
                'text' => $morphotype->name
            ]);
        }

        return json_encode($arr_data);
    }
}
