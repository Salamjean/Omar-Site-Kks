<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Models\Admin;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        $vetementsCount = Article::where('categorie', 'Vêtement')->count();
        $chaussureCount = Article::where('categorie', 'Chaussure')->count();
        $accessoireCount = Article::where('categorie', 'Accessoire')->count();
        $total = $vetementsCount + $accessoireCount + $chaussureCount;
        return view('admin.dashboard',compact('vetementsCount', 'chaussureCount', 'accessoireCount','total'));
    }

    public function register(){
        return view('admin.auth.register');
    }

    public function handleRegister(AdminRegisterRequest $request){
        try {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->save();

            return redirect()->route('admin.dashboard')->with('success','Bienvenu sur votre page administrateur');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function login(){
        return view('admin.auth.login');
    }

    public function handleLogin(Request $request){
        $request->validate([
            'email' => 'required|exists:admins',
            'password' => 'required|min:8'
        ],[
            'email.exists' => 'Cet email n\'existe pas donne autre',
            'email.required' => 'L\'email est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères'
        ]);
        try {
            $credentials = $request->only('email', 'password');
            if (auth()->guard('admin')->attempt($credentials)) {
                return redirect()->route('admin.dashboard');
            }
            return back()->withErrors(['email' => 'Mot de passe incorrect']);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function logout(){
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
