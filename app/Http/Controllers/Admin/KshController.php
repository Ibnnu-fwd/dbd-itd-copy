<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewKshMemberRegistered;
use App\Repositories\Interface\DetailKshInterface;
use App\Repositories\Interface\KshInterface;
use App\Repositories\Interface\RegencyInterface;
use App\Repositories\Interface\TpaTypeInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KshController extends Controller
{
    private $regency;
    private $tpaType;
    private $ksh;
    private $detailKsh;

    public function __construct(RegencyInterface $regency, TpaTypeInterface $tpaType, KshInterface $ksh, DetailKshInterface $detailKsh)
    {
        $this->regency = $regency;
        $this->tpaType = $tpaType;
        $this->ksh = $ksh;
        $this->detailKsh = $detailKsh;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->ksh->getAll())
                ->addColumn('sample_code', function($data) {
                    return 'KSH-' . date('Ymd', strtotime($data->created_at)) . '-' . $data->id;
                })
                ->addColumn('regency', function ($data) {
                    return ucwords(strtolower($data->regency->name));
                })
                ->addColumn('district', function ($data) {
                    return ucwords(strtolower($data->district->name));
                })
                ->addColumn('village', function ($data) {
                    return ucwords(strtolower($data->village->name));
                })
                ->addColumn('latitude', function ($data) {
                    return $data->latitude;
                })
                ->addColumn('longitude', function ($data) {
                    return $data->longitude;
                })
                ->addColumn('total_sample', function ($data) {
                    return $data->total_sample ?? 0;
                })
                ->addColumn('created_by', function($data) {
                    $name = explode(' ', $data->createdBy->name);
                    if(count($name) > 1) {
                        return $name[1];
                    } else {
                        return $data->createdBy->name;
                    }
                })
                ->addColumn('action', function ($data) {
                    return view('admin.ksh.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.ksh.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ksh.create', [
            'regencies' => $this->regency->getAll(),
            'tpaTypes' => $this->tpaType->getAll()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        try {
            $this->ksh->create($request->all());
            return redirect()->route('admin.ksh.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->ksh->getById($id)->detailKsh)
                ->addColumn('house_name', function ($data) {
                    return $data->house_name;
                })
                ->addColumn('house_owner', function ($data) {
                    return $data->house_owner;
                })
                ->addColumn('tpa_type', function ($data) {
                    return $data->tpaType->name;
                })
                ->addColumn('larva_status_true', function ($data) {
                    return $data->larva_status == 1 ? '✓' : '';
                })
                ->addColumn('larva_status_false', function ($data) {
                    return $data->larva_status == 0 ? '✓' : '';
                })
                ->addColumn('action', function($data) {
                    return view('admin.ksh.column.action-detail', [
                        'data' => $data
                    ]);
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.ksh.show', [
            'ksh' => $this->ksh->getById($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.ksh.edit', [
            'regencies' => $this->regency->getAll(),
            'tpaTypes' => $this->tpaType->getAll(),
            'ksh' => $this->ksh->getById($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'regency_id' => ['required'],
            'district_id' => ['required'],
            'village_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ]);

        try {
            $this->ksh->edit($request->all(), $id);
            return redirect()->route('admin.ksh.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // CUSTOM FUNCTION
    public function createDetail(string $id)
    {
        return view('admin.ksh.detail.create', [
            'ksh' => $this->ksh->getById($id),
            'tpaTypes' => $this->tpaType->getAll()
        ]);
    }

    public function storeDetail(Request $request, string $id)
    {
        $request->validate([
            'house_name' => ['required'],
            'house_owner' => ['required'],
            'larva_status' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ], [
            'house_name.required' => 'Nama rumah tidak boleh kosong',
            'house_owner.required' => 'Nama pemilik rumah tidak boleh kosong',
            'larva_status.required' => 'Status larva tidak boleh kosong',
            'latitude.required' => 'Latitude tidak boleh kosong',
            'longitude.required' => 'Longitude tidak boleh kosong',
        ]);

        try {
            $this->detailKsh->create($request->all(), $id);
            return redirect()->route('admin.ksh.show', $id)->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editDetail($id)
    {
        return view('admin.ksh.detail.edit', [
            'data' => $this->detailKsh->getById($id),
            'tpaTypes' => $this->tpaType->getAll(),
        ]);
    }

    public function updateDetail(Request $request, string $id)
    {
        $request->validate([
            'house_name' => ['required'],
            'house_owner' => ['required'],
            'larva_status' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
        ], [
            'house_name.required' => 'Nama rumah tidak boleh kosong',
            'house_owner.required' => 'Nama pemilik rumah tidak boleh kosong',
            'larva_status.required' => 'Status larva tidak boleh kosong',
            'latitude.required' => 'Latitude tidak boleh kosong',
            'longitude.required' => 'Longitude tidak boleh kosong',
        ]);

        try {
            $this->detailKsh->edit($request->all(), $id);
            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function member(Request $request)
    {
        if($request->ajax())
        {
            return datatables()
            ->of($this->ksh->getAllMember())
            ->addColumn('name', function($data){
                return $data->name;
            })
            ->addColumn('sex', function($data) {
                return $data->sex == 1 ? 'Laki-laki' : 'Perempuan';
            })
            ->addColumn('phone', function($data) {
                return $data->phone;
            })
            ->addColumn('email', function($data) {
                return $data->email;
            })
            ->addColumn('role', function($data) {
                return strtoupper($data->role);
            })
            ->addColumn('created_at', function($data) {
                return date('d-m-Y', strtotime($data->created_at));
            })
            ->addColumn('action', function($data) {
                return view('admin.ksh.column.action-member', compact('data'));
            })
            ->addIndexColumn()
            ->make(true);
        }

        return view('admin.ksh.member');
    }

    public function storeMember(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'sex' => ['required'],
            'phone' => ['required'],
            'birthday' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'email' => ['required'],
        ]);

        try {
            $password = uniqid();
            $request->merge([
                'password' => $password,
            ]);

            Mail::send(new NewKshMemberRegistered($request->all()));
            $this->ksh->createMember($request->all());
            return redirect()->route('admin.ksh.member')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function changeStatusMember(Request $request)
    {
        try {
            $this->ksh->changeStatusMember($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
}
