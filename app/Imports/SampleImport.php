<?php

namespace App\Imports;

use App\Models\District;
use App\Models\LocationType;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Sample;
// use App\Models\SampleMethod;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SampleImport implements ToModel, WithStartRow, WithMultipleSheets, WithValidation, WithChunkReading
{
    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 3;
    }

    public $fileCode;

    public function __construct($fileCode)
    {
        $this->fileCode = $fileCode;
    }

    public function rules(): array
    {
        return [
            '*.0' => ['required'],
            '*.1' => ['required', 'string'],
            '*.2' => ['required', 'string'],
            '*.3' => ['required', 'string'],
            '*.4' => ['required', 'string'],
            '*.5' => ['required', 'string'],
            '*.6' => ['required', 'string'],
            '*.7' => ['required', 'string'],
            '*.8' => ['required'],
            '*.9' => ['required'],
            // '*.10' => ['required', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.0.required' => 'Tanggal tidak boleh kosong',
            '*.1.required' => 'Provinsi tidak boleh kosong',
            '*.1.string' => 'Provinsi harus berupa string',
            '*.2.required' => 'Kabupaten/Kota tidak boleh kosong',
            '*.2.string' => 'Kabupaten/Kota harus berupa string',
            '*.3.required' => 'Kecamatan tidak boleh kosong',
            '*.3.string' => 'Kecamatan harus berupa string',
            '*.4.required' => 'Desa/Kelurahan tidak boleh kosong',
            '*.4.string' => 'Desa/Kelurahan harus berupa string',
            '*.5.required' => 'Tipe Lokasi tidak boleh kosong',
            '*.5.string' => 'Tipe Lokasi harus berupa string',
            '*.6.required' => 'Nama Lokasi tidak boleh kosong',
            '*.6.string' => 'Nama Lokasi harus berupa string',
            '*.7.required' => 'Nama Puskesmas tidak boleh kosong',
            '*.7.string' => 'Nama Puskesmas harus berupa string',
            '*.8.required' => 'Latitude tidak boleh kosong',
            '*.8.string' => 'Latitude harus berupa string',
            '*.9.required' => 'Longitude tidak boleh kosong',
            '*.9.string' => 'Longitude harus berupa string',
            // '*.10.required' => 'Metode Pengambilan Sampel tidak boleh kosong',
            // '*.10.string' => 'Metode Pengambilan Sampel harus berupa string',
        ];
    }

    public function model(array $row)
    {
        $createdAt          = $row[0];
        $createdAt          = Carbon::instance(Date::excelToDateTimeObject($createdAt))->format('Y-m-d');
        $province           = $this->province($row[1]);
        $regency            = $this->regency($row[2]);
        $district           = $this->district($row[3]);
        $village            = $this->village($row[4]);
        $locationType       = $this->locationType($row[5]);
        $locationName       = $row[6];
        $publicHealthName   = $row[7];
        $latitude           = str_replace(',', '.', $row[8]);
        $longitude          = str_replace(',', '.', $row[9]);
        // $sampleMethodId     = $this->sampleMethodId($row[10]);
        $sampleCode         = $this->generateSampleCode();
        $sample             = Sample::where('sample_code', $sampleCode)->first();

        if ($sample) {
            $sampleCode = $this->generateSampleCode();
        }

        if ($createdAt == null || $province == null || $regency == null || $district == null || $village == null || $locationType == null || $locationName == null || $publicHealthName == null || $latitude == null || $longitude == null ) {
            return null;
        } else {
            return Sample::create([
                'sample_code' => $sampleCode,
                'file_code' => $this->fileCode,
                'created_at' => $createdAt,
                'province_id' => $province,
                'regency_id' => $regency,
                'district_id' => $district,
                'village_id' => $village,
                'location_type_id' => $locationType,
                'location_name' => $locationName,
                'public_health_name' => $publicHealthName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                // 'sample_method_id' => $sampleMethodId
            ]);
        }
    }

    public function generateSampleCode()
    {
        $lastSample = Sample::orderBy('id', 'desc')->first();
        $lastId = $lastSample ? $lastSample->id : 0;
        $year = date('Y');
        $code = 'SC-' . $year . '-' . sprintf('%04s', $lastId + 1);
        return $code;
    }

    public function province($province)
    {
        $province = strtoupper($province);
        $province = Province::where('name', 'like', '%' .  $province . '%')->first();
        return $province->id;
    }

    public function regency($regency)
    {
        $regency = strtoupper($regency);
        $regency = Regency::where('name', 'like', '%' . $regency . '%')->first();

        return $regency->id ?? null;
    }

    public function district($param)
    {
        $param = strtoupper($param);
        $district = District::where('name', 'like', '%' . $param . '%')->first();
        return $district->id ?? null;
    }

    public function village($param)
    {
        $param = strtoupper($param);
        $village = DB::table('villages')->where('name', $param)->cursor();
        return $village->first()->id;
    }

    // public function sampleMethodId($param)
    // {
    //     $sampleMethodId = SampleMethod::where('name', 'like', '%' . $param . '%')->first();
    //     if ($sampleMethodId == null) {
    //         $sampleMethodId = SampleMethod::create([
    //             'name' => ucwords($param)
    //         ]);
    //         return $sampleMethodId->id;
    //     }

    //     return $sampleMethodId->id;
    // }

    public function locationType($param)
    {
        $param = strtoupper($param);
        $locationType = LocationType::where('name', 'like', '%' . $param . '%')->first();
        if ($locationType == null) {
            $locationType = LocationType::create([
                'name' => $param
            ]);
            return $locationType->id;
        }
        return $locationType->id;
    }
}
