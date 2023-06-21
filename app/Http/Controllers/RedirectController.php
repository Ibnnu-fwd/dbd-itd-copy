<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function check()
    {
        if(auth()->user()->role == 'admin')
        {
            return redirect()->route('admin.dashboard');
        } else if(auth()->user()->role == 'ksh')
        {
            return redirect()->route('ksh.index');
        }
    }
}
