<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\KshInterface;
use App\Repositories\Interface\LarvaeInterface;
use App\Repositories\Interface\SampleInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $larvae;
    private $ksh;

    public function __construct(LarvaeInterface $larvae, KshInterface $ksh) {
        $this->larvae = $larvae;
        $this->ksh = $ksh;
    }

    public function index()
    {
        return view('user.index');
    }

    public function ksh()
    {
        return view('user.ksh');
    }
}
