<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SampleInterface;
use Illuminate\Http\Request;

class VectorController extends Controller
{
    private $sample;
    private $regency;

    public function __construct(SampleInterface $sample, RegencyInterface $regency) {
        $this->sample = $sample;
        $this->regency = $regency;
    }

    public function index()
    {
        return view('user.vector', [
            'samples' => $this->sample->getAllForUser(date('Y'), null),
            'samplePerYear' => $this->sample->getSamplePerYear(date('Y')),
            'samplePerDistrict' => $this->sample->getHighestSampleInDistrictPerYear(date('Y')),
            'regencies' => $this->regency->getAll()
        ]);
    }

    public function filterYear(Request $request)
    {
        return response()->json([
            'samplePerYear' => $this->sample->getSamplePerYear($request->year)
        ]);
    }

    public function filterYearDistrict(Request $request)
    {
        return response()->json([
            'samplePerDistrict' => $this->sample->getHighestSampleInDistrictPerYear($request->year)
        ]);
    }

    public function filterRegency(Request $request)
    {
        return response()->json([
            'samples' => $this->sample->getAllSampleByRegency($request->regency_id)
        ]);
    }

    public function filterMapYear(Request $request)
    {
        return response()->json([
            'samples' => $this->sample->getAllForUser($request->year, null)
        ]);
    }

    public function filterMapRegency(Request $request)
    {
        return response()->json([
            'samples' => $this->sample->getAllForUser(null, $request->regency_id)
        ]);
    }
}
