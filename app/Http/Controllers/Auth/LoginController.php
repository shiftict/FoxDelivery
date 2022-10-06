<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    protected $redirectTo = 'dashboard';//RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'           => 'required|max:255|email',
            'password'           => 'required|min:6',
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Success
            if(Auth::user()->delivery()->exists()){
                Auth::logout();
                return redirect()->route('login');
            }
            if(Auth::user()->vendor_id) {
                return redirect()->route('read.order.employee');
            }
            toastr()->success(__('alert.successLogin'));
            return redirect()->route('dashboard.index');
        } else {
            // Go back on error (or do what you want)
            session()->flash('message', __('alert.errorLogin'));
            return redirect()->back();
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    
    protected function authenticated(Request $request, $user)
    {
        dd("HI");
      // auth()->logout();
    }
}
