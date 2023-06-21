<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\SampleInterface;
use App\Repositories\Interface\AbjInterface;
use App\Models\Sample;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $sample;
    private $larva;
    private $larvae;
    private $abj;

    public function __construct(
        SampleInterface $sample,
        LarvaeInterface $larva,
        AbjInterface $abj,
        LarvaeInterface $larvae,
    ) {
        $this->sample = $sample;
        $this->larva = $larva;
        $this->abj = $abj;
        $this->larvae = $larvae;
    }

    public function __invoke(Request $request)
    {
        return view('admin.dashboard.index', [
            'samplePerYear' => $this->sample->getSamplePerYear(date('Y')),
            'usersCount' => User::all()->count(),
            'totalSample' => $this->sample->getTotalSample(),
            'totalMosquito' => $this->sample->getTotalMosquito(),
            'totalLarva' => $this->larva->getTotalLarva(),
            'abj' => $this->abj->getAllGroupByDistrict(),
            'larvae' => $this->larvae->getAll(),
            'sample' => $this->sample->getAll(),
        ]);
    }
}
