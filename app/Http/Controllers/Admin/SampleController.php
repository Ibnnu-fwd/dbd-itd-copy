<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DetailSampleExport;
use App\Http\Controllers\Controller;
use App\Imports\DetailSampleImport;
use App\Imports\SampleImport;
use App\Repositories\Interface\DetailSampleVirusInterface;
use App\Repositories\Interface\DistrictInterface;
use App\Repositories\Interface\LocationTypeInterface;
use App\Repositories\Interface\MorphotypeInterface;
use App\Repositories\Interface\ProvinceInterface;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SampleInterface;
use App\Repositories\Interface\SampleMethodInterface;
use App\Repositories\Interface\SerotypeInterface;
use App\Repositories\Interface\VillageInterface;
use App\Repositories\Interface\VirusInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class SampleController extends Controller
{
    private $sample;
    private $sampleMethod;
    private $province;
    private $regency;
    private $district;
    private $village;
    private $morphotype;
    private $viruses;
    private $serotype;
    private $locationType;
    private $detailSampleVirus;

    public function __construct(
        SampleInterface $sample,
        SampleMethodInterface $sampleMethod,
        ProvinceInterface $province,
        RegencyInterface $regency,
        DistrictInterface $district,
        VillageInterface $village,
        MorphotypeInterface $morphotype,
        VirusInterface $viruses,
        SerotypeInterface $serotype,
        LocationTypeInterface $locationType,
        DetailSampleVirusInterface $detailSampleVirus
    ) {
        $this->sample = $sample;
        $this->sampleMethod = $sampleMethod;
        $this->province = $province;
        $this->regency = $regency;
        $this->district = $district;
        $this->village = $village;
        $this->morphotype = $morphotype;
        $this->viruses = $viruses;
        $this->serotype = $serotype;
        $this->locationType = $locationType;
        $this->detailSampleVirus = $detailSampleVirus;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->sample->getAll())
                ->addColumn('sample_code', function ($data) {
                    return $data->sample_code;
                })
                // ->addColumn('sample_method', function ($data) {
                //     return $data->sampleMethod->name;
                // })
                ->addColumn('address', function ($data) {
                    $address = $data->village->name . ', ' . $data->district->name . ', ' . $data->regency->name . ', ' . $data->province->name;

                    $address = strtolower($address);
                    return ucwords($address);

                    // return view('admin.sample.column.address', [
                    //     'address' => $data->village->name . ', ' . $data->district->name . ', ' . $data->regency->name . ', ' . $data->province->name,
                    //     'coordinate' => $data->latitude . ', ' . $data->longitude
                    // ]);
                })
                ->addColumn('sample_count', function ($data) {
                    return $data->total_sample;
                })
                ->addColumn('location', function ($data) {
                    return $data->location_name ?? '-';
                })
                ->addColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addColumn('updated_by', function ($data) {
                    return $data->updatedBy->name ?? '-';
                })
                ->addColumn('action', function ($data) {
                    return view('admin.sample.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.sample.index');
    }

    public function create()
    {
        return view('admin.sample.create', [
            'provinces' => $this->province->getAll(),
            'regencies' => $this->regency->getAll(),
            'districts' => $this->district->getAll(),
            'villages' => $this->village->getAll(),
            'sampleMethods' => $this->sampleMethod->getAll(),
            'morphotypes' => $this->morphotype->getAll(),
            'serotypes' => $this->serotype->getAll(),
            'viruses' => $this->viruses->getAll(),
            'locationTypes' => $this->locationType->getAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'sample_method_id' => ['required'],
            'location_name' => ['required'],
            'location_type_id' => ['required'],
            'description' => ['nullable'],
            'province_id' => ['required'],
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'viruses' => ['required', 'array'],
        ], [
            // 'sample_method_id.required' => 'Metode pengambilan sampel harus diisi.',
            'location_name.required' => 'Nama lokasi harus diisi.',
            'location_type_id.required' => 'Tipe lokasi harus diisi.',
            'province_id.required' => 'Provinsi harus diisi.',
            'regency_id.required' => 'Kabupaten/Kota harus diisi.',
            'district_id.required' => 'Kecamatan harus diisi.',
            'village_id.required' => 'Desa/Kelurahan harus diisi.',
            'latitude.required' => 'Latitude harus diisi.',
            'longitude.required' => 'Longitude harus diisi.',
            'viruses.required' => 'Jenis vektor harus diisi. Minimal 1.',
        ]);

        try {
            $this->sample->create($request->all());
            return redirect()->route('admin.sample.index')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.sample.edit', [
            'provinces' => $this->province->getAll(),
            'regencies' => $this->regency->getAll(),
            'districts' => $this->district->getAll(),
            'villages' => $this->village->getAll(),
            'sampleMethods' => $this->sampleMethod->getAll(),
            'morphotypes' => $this->morphotype->getAll(),
            'serotypes' => $this->serotype->getAll(),
            'viruses' => $this->viruses->getAll(),
            'locationTypes' => $this->locationType->getAll(),
            'sample' => $this->sample->getById($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            // 'sample_method_id' => ['required'],
            'location_name' => ['required'],
            'location_type_id' => ['required'],
            'description' => ['nullable'],
            'province_id' => ['required'],
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'viruses' => ['nullable', 'array'],
        ], [
            // 'sample_method_id.required' => 'Metode pengambilan sampel harus diisi.',
            'location_name.required' => 'Nama lokasi harus diisi.',
            'location_type_id.required' => 'Tipe lokasi harus diisi.',
            'province_id.required' => 'Provinsi harus diisi.',
            'regency_id.required' => 'Kabupaten/Kota harus diisi.',
            'district_id.required' => 'Kecamatan harus diisi.',
            'village_id.required' => 'Desa/Kelurahan harus diisi.',
            'latitude.required' => 'Latitude harus diisi.',
            'longitude.required' => 'Longitude harus diisi.',
        ]);

        try {
            $this->sample->update($id, $request->all());
            $sample = $this->sample->getById($id);
            return redirect()->route('admin.sample.detail-sample', $sample)->with('success', 'Sampel berhasil diubah');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->sample->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }

    // CUSTOM FUNCTION
    public function detailSample($id)
    {
        // dd($this->sample->detailSample($id));
        return view('admin.sample.detail-sample', [
            'sample' => $this->sample->detailSample($id),
            'serotypes' => $this->serotype->getAll(),
        ]);
    }

    public function detailSampleVirus($id)
    {
        return view('admin.sample.detail-sample-virus', [
            'sample' => $this->detailSampleVirus->getById($id),
            'morphotypes' => $this->morphotype->getAll(),
            'serotypes' => $this->serotype->getAll()
        ]);
    }

    public function storeDetailSampleVirus($id, Request $request)
    {
        try {
            $this->detailSampleVirus->store($request->all(), $id);
            return response()->json(true);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response()->json(false);
        }
    }

    public function deleteDetailSampleVirusMorphotype(Request $request)
    {
        try {
            $this->detailSampleVirus->deleteDetailSampleVirusMorphotype($request->detailSampleMorphotypeId);
            return response()->json(true);
        } catch (\Throwable $th) {
            return response()->json(false);
        }
    }

    public function deleteDetailSampleVirus($id)
    {
        try {
            $this->detailSampleVirus->delete($id);
            return response()->json(true);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return response()->json(false);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => ['required', 'mimes:xls,xlsx']
        ]);

        try {
            $fileCode = uniqid();
            Excel::import(new SampleImport($fileCode), $request->file('import_file'));
            $filename = $fileCode . '.' . $request->file('import_file')->getClientOriginalExtension();
            $request->file('import_file')->storeAs('public/sample-imported', $filename);
            return redirect()->route('admin.sample.index')->with('success', 'Data berhasil diimport.');
        } catch (ValidationException $th) {
            return view('admin.sample.index', [
                'failures' => $th->failures() ?? null
            ]);
        }
    }

    public function importDetailSample(Request $request)
    {
        $request->validate([
            'import_file' => ['required', 'mimes:xls,xlsx'],
            'sample_id' => ['required']
        ]);

        try {
            $fileCode = uniqid();
            Excel::import(new DetailSampleImport($request->sample_id), $request->file('import_file'));
            // $filename = $fileCode . '.' . $request->file('import_file')->getClientOriginalExtension();
            // $request->file('import_file')->storeAs('public/detail-sample-imported', $filename);
            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } catch (ValidationException $th) {
            dd($th->getMessage());
            return view('admin.sample.detail-sample', [
                'failures' => $th->failures() ?? null,
                'sample' => $this->sample->detailSample($request->sample_id),
            ]);
        }
    }

    public function exportDetailSample($id)
    {
        $sample = $this->sample->getById($id);
        return Excel::download(new DetailSampleExport($id), 'DETAIL SAMPLE_' . $sample->sample_code . uniqid() . '.xlsx');
    }
}
