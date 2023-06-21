<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\VillageInterface;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    private $village;

    public function __construct(VillageInterface $village) {
        $this->village = $village;
    }

    public function index(Request $request)
    {
        $villages = $this->village->getAll();

        if($request->ajax()) {
            return datatables()
            ->of(
                $villages
            )
            ->addColumn('name', function($data) {
                return $data->name ?? '-';
            })
            ->addColumn('district', function($data) {
                return $data->district->name ?? '-';
            })
            ->addColumn('regency', function($data) {
                return $data->district->regency->name ?? '-';
            })
            ->addColumn('province', function($data) {
                return $data->district->regency->province->name ?? '-';
            })
            // ->addColumn('action', function($data) {
            //     return view('admin.village.column.action', compact('data'));
            // })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.village.index');
    }

    public function create()
    {
        return view('admin.village.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:villages,name'],
            'district_id' => ['required', 'exists:districts,id']
        ]);

        $this->village->create($request->only('name', 'district_id'));
        return redirect()->route('admin.village.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $village = $this->village->getById($id);
        $address = $village->name . ', ' . $village->district->name . ', ' . $village->district->regency->name . ', ' . $village->district->regency->province->name;
        $address = strtolower($address);
        $validAddress = ucwords($address);

        $json = file_get_contents("https://geocode.maps.co/search?q=$village->name");
        $json = json_decode($json);

        return response()->json([
            'address' => $validAddress,
            'detail' => $json[0],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.village.edit', [
            'village' => $this->village->getById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'unique:villages,name,' . $id],
            'district_id' => ['required', 'exists:districts,id']
        ]);

        $this->village->update($id, $request->only('name', 'district_id'));
        return redirect()->route('admin.village.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->village->delete($id);
        return redirect()->route('admin.village.index')->with('success', 'Data berhasil dihapus');
    }

    // CUSTOM FUNCTION
    public function list(Request $request)
    {
        $villages = $this->village->getByDistrict($request->district_id);
        return response()->json($villages);
    }
}
