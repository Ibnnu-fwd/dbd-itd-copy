<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
        $request->validate([
            'name' => ['required'],
            'sex' => ['required'],
            'birthday' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
        ]);

        try {
            User::find($id)->update([
                'name' => $request->name,
                'sex' => $request->sex,
                'birthday' => date('Y-m-d', strtotime($request->birthday)),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            return redirect()->back()->with('success', 'Berhasil mengubah data');
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
    public function updateProfilePicture(Request $request)
    {
        try {
            $user = User::find($request->id);
            $oldProfilePicture = $user->profile_picture;

            if ($oldProfilePicture != null) {
                $oldProfilePicturePath = public_path('storage/profile-picture' . $oldProfilePicture);
                if (file_exists($oldProfilePicturePath)) {
                    unlink($oldProfilePicturePath);
                }
            }

            $filename = uniqid() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('public/profile-picture', $filename);

            $user->profile_picture = $filename;

            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah foto profil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah foto profil',
            ]);
        }
    }

    public function updateUserAccount(Request $request, $id)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'old_password' => ['required'],
            'new_password' => ['required'],
            'new_password_confirmation' => ['required'],
        ], [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'old_password.required' => 'Password lama tidak boleh kosong',
            'new_password.required' => 'Password baru tidak boleh kosong',
            'new_password_confirmation.required' => 'Konfirmasi password baru tidak boleh kosong',
        ]);

        try {
            // check if email is valid
            $user = User::where('email', $request->email)->first();
            if ($user == null) {
                return redirect()->back()->with('error', 'Email tidak ditemukan');
            }

            // check if old password is valid
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak valid');
            }

            // check if new password and new password confirmation is match
            if ($request->new_password != $request->new_password_confirmation) {
                return redirect()->back()->with('error', 'Konfirmasi password baru tidak valid');
            }

            // update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Berhasil mengubah akun');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
