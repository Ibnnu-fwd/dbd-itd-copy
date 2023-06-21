<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\ProvinceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProvinceController extends Controller
{
    private $province;

    public function __construct(ProvinceInterface $province)
    {
        $this->province = $province;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->province->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('admin.province.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.province.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.province.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'unique:provinces,name'
        ], [
            'name.unique' => 'Nama provinsi sudah ada'
        ]);

        try {
            $this->province->create($request->all());
            return redirect()->route('admin.province.index')->with('success', 'Tambah Provinsi Berhasil');
        } catch (Exception $e) {
            return back()->with('error', 'Tambah Provinsi Gagal');
        }
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
        try {
            $this->province->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Hapus Provinsi Berhasil'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $provinces = $this->province->getAll();
            $data = new Collection();
            foreach ($provinces as $province) {
                $data->push([
                    'id' => $province->id,
                    'text' => $province->name
                ]);
            }
            return response()->json($data);
        }
    }
}
