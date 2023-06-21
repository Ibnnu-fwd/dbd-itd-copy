<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\SampleInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VariableAgentController extends Controller
{
    private $sample;
    private $regency;

    public function __construct(SampleInterface $sample, RegencyInterface $regency)
    {
        $this->sample = $sample;
        $this->regency = $regency;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->sample->getAllRegency())
                ->addColumn('regency', function ($data) {
                    return $data['regency'];
                })
                // ->addColumn('location', function ($data) {
                //     return $data['location'];
                // })
                ->addColumn('count', function ($data) {
                    return $data['count'] ?? 0;
                })
                ->addColumn('type', function ($data) {
                    return view('admin.variable-agent.column.type', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('admin.variable-agent.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.variable-agent.index');
    }

    public function show($id, Request $request)
    {
        // return $this->sample->getAllGroupByDistrict($id);
        if ($request->ajax()) {
            return datatables()
                ->of($this->sample->getAllGroupByDistrict($id))
                ->addColumn('district', function ($data) {
                    return $data['district'];
                })
                ->addColumn('location', function ($data) {
                    return $data['latitude'] . ', ' . $data['longitude'];
                })
                ->addColumn('count', function ($data) {
                    return $data['count'] ?? 0;
                })
                ->addColumn('type', function ($data) {
                    return view('admin.variable-agent.column.type', compact('data'));
                })
                ->addColumn('created_at', function($data) {
                    return Carbon::parse($data['created_at'])->locale('id')->isoFormat('D MMMM Y');
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.variable-agent.show', [
            'regency' => $this->regency->getById($id),
            'id' => $id,
            'samples' => $this->sample->getAllGroupByDistrict($id),
            'months' => [
                '1' => 'Januari',
                '2' => 'Februari',
                '3' => 'Maret',
                '4' => 'April',
                '5' => 'Mei',
                '6' => 'Juni',
                '7' => 'Juli',
                '8' => 'Agustus',
                '9' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
            ],
        ]);
    }

    public function showFilterMonth($id, Request $request)
    {
        $samples = $this->sample->getAllGroupByDistrictFilterByMonth($id, $request->month);
        $data = $samples->map(function ($data) {
            return [
                'district' => $data['district'],
                'location' => $data['latitude'] . ', ' . $data['longitude'],
                'count' => $data['count'] ?? 0,
                'type' => view('admin.variable-agent.column.type', compact('data'))->render(),
                'created_at' => Carbon::parse($data['created_at'])->locale('id')->isoFormat('D MMMM Y'),
            ];
        });

        return response()->json([
            'data' => $data,
            'samples' => $samples,
        ]);
    }

    public function showFilterDateRange($id, Request $request)
    {
        $samples = $this->sample->getAllGroupByDistrictFilterByDateRange($id, $request->start_date, $request->end_date);
        $data = $samples->map(function ($data) {
            return [
                'district' => $data['district'],
                'location' => $data['latitude'] . ', ' . $data['longitude'],
                'count' => $data['count'] ?? 0,
                'type' => view('admin.variable-agent.column.type', compact('data'))->render(),
                'created_at' => Carbon::parse($data['created_at'])->locale('id')->isoFormat('D MMMM Y'),
            ];
        });

        return response()->json([
            'data' => $data,
            'samples' => $samples,
        ]);
    }
}
