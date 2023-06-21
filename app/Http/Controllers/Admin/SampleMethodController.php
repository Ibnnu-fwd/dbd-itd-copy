<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\SampleMethodInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SampleMethodController extends Controller
{
    private $sampleMethod;

    public function __construct(SampleMethodInterface $sampleMethod) {
        $this->sampleMethod = $sampleMethod;
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {
            return datatables()
            ->of($this->sampleMethod->getAll())
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('action', function($data) {
                return view('admin.sample-method.column.action', compact('data'));
            })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.sample-method.index');
    }

    public function create()
    {
        return view('admin.sample-method.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', Rule::unique('sample_methods', 'name')->where('is_active', 1)]
        ]);

        try {
            $this->sampleMethod->create($request->all());
            return redirect()->route('admin.sample-method.index')->with('success', 'Metode Sampling berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->route('admin.sample-method.index')->with('error', 'Metode Sampling gagal ditambahkan');
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view('admin.sample-method.edit', [
            'sampleMethod' => $this->sampleMethod->getById($id)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', Rule::unique('sample_methods', 'name')->where('is_active', 1)->ignore($id)]
        ]);

        try {
            $this->sampleMethod->update($id, $request->all());
            return redirect()->route('admin.sample-method.index')->with('success', 'Metode Sampling berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.sample-method.index')->with('error', 'Metode Sampling gagal diubah');
        }
    }

    public function destroy(string $id)
    {
        $this->sampleMethod->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Metode Sampling berhasil dihapus'
        ]);
    }

    // ------------------ CUSTOM FUNCTION ------------------
    public function list()
    {
        $sampleMethods = $this->sampleMethod->getAll();
        $arr_data = new Collection();
        foreach ($sampleMethods as $sampleMethod) {
            $arr_data->push([
                'id' => $sampleMethod->id,
                'text' => $sampleMethod->name
            ]);
        }

        return json_encode($arr_data);
    }
}
