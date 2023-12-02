<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user)
    {
        if ($user->akses == 'admin') {
            return redirect()->route('admin.beranda');
        } elseif ($user->akses == 'superadmin') {
            return redirect()->route('superadmin.beranda');
        } elseif ($user->akses == 'polisi') {
            return redirect()->route('polisi.beranda');
        } elseif ($user->akses == 'pengacara') {
            return redirect()->route('pengacara.beranda');
        } elseif ($user->akses == 'seksi') {
            return redirect()->route('seksi.beranda');
        } elseif ($user->akses == 'masyarakat') {
            return redirect()->route('masyarakat.beranda');
        } else {
            Auth::logout();
            flash('Anda tidak memiliki hak akses')->error();
            return redirect()->route('login');
        }
    }

    protected function loginApi(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($loginData)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;
            return response()->json([
                'data' => Auth::user(),
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid Credentials',
        ], 401);
    }
}
