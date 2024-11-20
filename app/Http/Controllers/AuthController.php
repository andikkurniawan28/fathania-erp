<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        $setup = Setup::get()->last();
        return view('auth.login', compact('setup'));
    }

    public function loginProcess(Request $request){
        $attempt = Auth::attempt([
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active'  => 1,
        ]);
        if ($attempt){
            $request->session()->regenerate();

            $from_datetime = Carbon::today()->addHours(6);
            $to_datetime = $from_datetime->copy()->addHours(24);
            $request->session()->put('from_datetime', $from_datetime);
            $request->session()->put('to_datetime', $to_datetime);

            ActivityLog::insert(["user_id" => Auth::id(), "description" => "Logged in."]);
            return redirect()->intended();
        }
        else
        {
            return redirect('login')->with('fail', 'Username / password wrong.');
        }
    }

    public function logout(Request $request){
        ActivityLog::insert(["user_id" => Auth::id(), "description" => "Logged out."]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public static function changeDatetime(Request $request)
    {
        $request->session()->put('from_datetime', $request->input('from_datetime'));
        $request->session()->put('to_datetime', $request->input('to_datetime'));
        return redirect()->back()->with("success", "Timeframe has been updated");
    }

}
