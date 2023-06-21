<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\EnvironmentTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnvironmentTypeController extends Controller
{
    private $environmentType;

    public function __construct(EnvironmentTypeInterface $environmentType)
    {
        $this->environmentType = $environmentType;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->environmentType->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.environment-type.column.action', ['data' => $data]);
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.environment-type.index');
    }

    public function create()
    {
        return view('admin.environment-type.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('environment_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ], [
            'name.required' => 'Nama jenis lingkungan tidak boleh kosong',
            'name.string' => 'Nama jenis lingkungan harus berupa string',
            'name.unique' => 'Nama jenis lingkungan sudah terdaftar'
        ]);

        try {
            $this->environmentType->store($request->all());
            return redirect()->route('admin.environment-type.index')->with('success', 'Jenis lingkungan berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        return view('admin.environment-type.edit', [
            'environmentType' => $this->environmentType->getById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('environment_types', 'name')->where(function ($query) {
                return $query->where('is_active', 1);
            })]
        ]);

        try {
            $this->environmentType->update($id, $request->all());
            return redirect()->route('admin.environment-type.index')->with('success', 'Jenis lingkungan berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->environmentType->destroy($id);
            return response()->json([
                'status' => true,
                'message' => 'Jenis lingkungan berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
