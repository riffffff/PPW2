<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class LoginRegisterController extends Controller
{
    /**
     * Konstruktor untuk menginisialisasi middleware.
     * Middleware 'guest' diterapkan, kecuali pada method 'logout' dan 'dashboard'.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'dashboard'
        ]);
    }

    /**
     * Menampilkan form registrasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register'); // Mengarahkan ke view registrasi
    }

    /**
     * Menyimpan pengguna baru ke database.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' => 'image|nullable|max:1999',
        ]);

        if ($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . time() . '.' . $extension;
            $path = $request->file('photo')->storeAs('photos', $filenameSimpan);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $path ?? null,
        ]);

        $registrationDate = Carbon::now()->format('d M Y');

        Mail::to($user->email)->send(new WelcomeMail($user, $registrationDate));

        Auth::attempt($request->only('email', 'password'));
        $request->session()->regenerate();

        return redirect()->route('dashboard')
        ->withSuccess('You have successfully registered & logged in!');
    }

    /**
     * Menampilkan form login.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login'); // Mengarahkan ke view login
    }

    /**
     * Mengautentikasi pengguna berdasarkan kredensial yang diberikan.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // Validasi input untuk login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Jika autentikasi berhasil
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect sesuai level pengguna
            if ($user->level === 'admin') {
                return redirect()->route('book.index')
                    ->withSuccess('You have successfully logged in as admin!');
            } else {
                return redirect()->route('dashboard')
                    ->withSuccess('You have successfully logged in!');
            }
        }

        // Jika autentikasi gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan dashboard untuk pengguna yang terautentikasi.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();

        if (Auth::check()) {
            return view('auth.dashboard', compact('user')); // Menampilkan dashboard jika pengguna login
        }

        // Redirect ke halaman login jika pengguna belum login
        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

    /**
     * Logout pengguna dari aplikasi.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna
        $request->session()->invalidate(); // Menghapus sesi saat ini
        $request->session()->regenerateToken(); // Mengamankan token sesi baru

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }
}
