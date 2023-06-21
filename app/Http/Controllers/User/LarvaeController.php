<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\RegencyInterface;
use Illuminate\Http\Request;

class LarvaeController extends Controller
{
    private $larvae;
    private $regency;

    public function __construct(LarvaeInterface $larvae, RegencyInterface $regency) {
        $this->larvae = $larvae;
        $this->regency = $regency;
    }

    public function index()
    {
        return view('user.larvae', [
            'larvae' => $this->larvae->getAll(),
            'regencies' => $this->regency->getAll(),
        ]);
    }

    public function filterMapYear(Request $request)
    {
        return response()->json([
            'larvae' => $this->larvae->filterMapYear($request->year)
        ]);
    }

    public function filterMapRegency(Request $request)
    {
        return response()->json([
            'larvae' => $this->larvae->filterMapRegency($request->regency_id)
        ]);
    }
}
