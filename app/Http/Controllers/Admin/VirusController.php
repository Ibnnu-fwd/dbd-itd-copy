<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\VirusInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class VirusController extends Controller
{
    private $virus;

    public function __construct(VirusInterface $virus)
    {
        $this->virus = $virus;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->virus->getAll())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('description', function ($data) {
                    return $data->description ?? '-';
                })
                ->addColumn('image', function ($data) {
                    return view('admin.virus.column.image', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.virus.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.virus.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.virus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:viruses,name'],
            'description' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        try {
            $this->virus->create($request->all());
            return redirect()->route('admin.virus.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
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
        return view('admin.virus.edit', [
            'virus' => $this->virus->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'unique:viruses,name,' . $id],
            'description' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        try {
            $this->virus->update($id, $request->all());
            return redirect()->route('admin.virus.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->virus->delete($id);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    // ------------------- CUSTOM FUNCTION -------------------
    public function list(Request $request)
    {
        $virus = $this->virus->getAll();
        return response()->json($virus);
    }
}
