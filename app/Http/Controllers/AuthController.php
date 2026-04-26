<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
    public function loginForm(){ return view('auth.login'); }
    public function registerForm(){ return view('auth.register'); }
    public function login(Request $request){
        $credentials=$request->validate(['email'=>'required|email','password'=>'required']);
        if(Auth::attempt($credentials, $request->boolean('remember'))){ $request->session()->regenerate(); return redirect()->intended(route('dashboard')); }
        return back()->withErrors(['email'=>'بيانات الدخول غير صحيحة'])->onlyInput('email');
    }
    public function register(Request $request){
        $data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users','password'=>'required|min:8|confirmed','affiliation'=>'nullable|string|max:255']);
        $user=User::create(['name'=>$data['name'],'email'=>$data['email'],'password'=>Hash::make($data['password']),'affiliation'=>$data['affiliation'] ?? null,'role'=>'researcher']);
        Auth::login($user); return redirect()->route('dashboard');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('home'); }
}
