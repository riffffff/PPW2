<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Please login to access the dashboard.',
                ])->onlyInput('email');
        }
        $users = User::all();
        return view('users.index', compact('users'));
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
        $user = User::findOrFail($id); // Fetch the user by ID or fail if not found
        return view('users.edit', compact('user')); // Return the edit view with the user data
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input untuk update
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users,email,' . $id,
            'password' => 'nullable|min:8|confirmed', // Password is optional during update
            'photo' => 'image|nullable|max:1999',
        ]);

        $user = User::findOrFail($id); // Fetch the user by ID or fail if not found

        // Menyimpan file foto baru jika ada
        if ($request->hasFile('photo')) {
            // Path foto lama
            $oldFile = public_path('storage/' . $user->photo);

            // Menghapus foto lama jika ada
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            // Mengunggah foto baru
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan); // Menyimpan foto baru
        }

        // Update data pengguna
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'photo' => isset($path) ? $path : $user->photo, // If photo is provided, update it
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Hash password if provided
        ]);

        return redirect('users')->with('success', 'User updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        $file = public_path() . '/storage/' . $user->photo;

        try {
            if (File::exists($file)) {
                File::delete($file);
            }

            $user->delete();
        } catch (\Throwable $th) {
            return redirect('users')->with('error', 'Gagal hapus data');
        }

        return redirect('users')->with('success', 'Berhasil hapus data');
    }
}
