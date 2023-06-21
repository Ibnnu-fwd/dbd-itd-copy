<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interface\AbjInterface;
use Illuminate\Http\Request;

class AbjController extends Controller
{

    private $abj;

    public function __construct(AbjInterface $abj) {
        $this->abj = $abj;
    }

    public function index(Request $request)
    {
        // return $this->abj->getAllGroupByDistrict();
        if($request->ajax())
        {
            return datatables()
            ->of($this->abj->getAllGroupByDistrict())
            ->addColumn('district', function($data) {
                return $data['district'];
            })
            ->addColumn('total_sample', function($data) {
                return $data['total_sample'];
            })
            ->addColumn('total_check', function($data) {
                return $data['total_check'];
            })
            // ->addColumn('location', function($data) {
            //     return view('admin.abj.column.location', ['data' => $data['location']]);
            // })
            ->addColumn('abj', function($data) {
                return view('admin.abj.column.abj_total', ['data' => $data]);
            })
            ->addColumn('created_at', function($data) {
                return $data['created_at'];
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.abj.index', [
            'abj' => $this->abj->getAllGroupByDistrict()
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // CUSTOM FUNCTION
    public function geojson(Request $request)
    {
        return response()->json($this->abj->getGeoJson());
    }
}
