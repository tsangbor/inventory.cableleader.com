<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use App\Models\User;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/login';
    public function login()
    {
        return view('backend.auth.login');
    }

    /**
     * Show the application loginprocess.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validator($request);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()
                ->intended(route('dashboard.home.index'))
                ->with('status', '登入成功');
        }
        /*
        if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {

        }*/
        //Authentication failed...
        return $this->loginFailed();
    }

    private function validator(Request $request)
    {
        //validation rules.
        $rules = [
            'email'    => 'required|email|exists:users',
            'password' => 'required',
            /*'captcha' => 'required|captcha',*/
        ];

        //custom validation error messages.
        $messages = [
            'email.required' => '請輸入登入帳號',
            'email,email' => '請輸入正確帳號格式（Email）',
            'email.exists' => '無此登入帳號.',
            'password.required' => '請輸入登入密碼',
        ];

        //validate the request.
        $request->validate($rules, $messages);
    }

    private function loginFailed()
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '登入失敗！請重新輸入帳號密碼.');
    }
    /**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Session::flush();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('dashboard.login'));
    }
}
