<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Commande;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function acceuil(){
        $latestArticle = Article::where('categorie', 'Vêtement')
                                  ->latest()
                                  ->first();

        $articles = Article::where('categorie', 'Vêtement')
                                ->orderBy('created_at', 'desc')
                                ->get();
        
        $latestChaussure = Article::where('categorie', 'Chaussure')
                                ->latest()
                                ->first();
        
        $articlesAccessoire = Article::where('categorie', 'Accessoire')
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('pages.accueil',compact('articles','latestArticle','latestChaussure','articlesAccessoire'));
    }
    public function space(){
        $user = Auth::user();
        // Compter les articles  commandés par l'utilisateur
           // Compter les articles  commandés par l'utilisateur
           $articleCount = Commande::where('user_id', $user->id)
           ->where('status', '!=', 'annulée')
           ->count();
        $latestArticle = Article::where('categorie', 'Vêtement')
            ->latest()
            ->first();

        $articles = Article::where('categorie', 'Vêtement')
            ->orderBy('created_at', 'desc')
            ->get();

        $latestChaussure = Article::where('categorie', 'Chaussure')
            ->latest()
            ->first();

        $articlesAccessoire = Article::where('categorie', 'Accessoire')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.space',compact('articles','latestArticle','latestChaussure','articlesAccessoire','articleCount'));
    }
    public function logout(){
        auth()->guard('web')->logout();
        return redirect()->route('user.accueil');
    }
    public function register(){
        return view('user.auth.register');
    }
    public function handleRegister(Request $request){
        $request->validate([
            'name'=>'required',
            'prenom'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|same:password_confirm',
            'password_confirm'=>'required|min:6|same:password'
        ],[
            'name.required'=>'Le champ nom est obligatoire',
            'prenom.required'=>'Le champ prénom est obligatoire',
            'email.required'=>'Le champ email est obligatoire',
            'email.email'=>'Le champ email doit être un email',
            'email.unique'=>'Cet email est déjà utilisé',
            'password.required'=>'Le champ mot de passe est obligatoire',
            'password.min'=>'Le champ mot de passe doit contenir au moins 6 caractères',
            'password.same'=>'Les mots de passe ne correspondent pas',
            'password_confirm.required'=>'Le champ confirmation mot de passe est obligatoire',
            'password_confirm.min'=>'Le champ confirmation mot de passe doit contenir au moins 6 caractères',
            'password_confirm.same'=>'Les mots de passe ne correspondent pas'
        ]);
        try {
            $user = new User();
            $user->name = $request->name;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect()->route('user.space')->with('success','Vous êtes connecté effectuez vos commandes');
        } catch (Exception $e) {
           dd($e->getMessage());
        }
    }
    public function login(){
        return view('user.auth.login');
    }
    public function handleLogin(Request $request){
        // Validation des données
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ], [
            'email.exists' => 'Cet email n\'existe pas',
            'email.required' => 'Le champ email est obligatoire',
            'email.email' => 'Le champ email doit être un email valide',
            'password.required' => 'Le champ mot de passe est obligatoire',
            'password.min' => 'Le champ mot de passe doit contenir au moins 6 caractères'
        ]);
    
        // Authentification de l'utilisateur
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Authentification réussie, redirigez vers la page de l'utilisateur
            return redirect()->route('user.space');
        }
    
        // Authentification échouée, redirigez avec un message d'erreur
        return redirect()->back()->withErrors([
            'password' => 'Les informations d\'identification sont incorrectes.',
        ])->withInput($request->only('email'));
    }

    //La routes pour afficher les users chez l'admin  
    public function UserList(){
        $users = User::all();
        return view('user.list',compact('users'));
    }

    // Les routes pour afficher les types d'articles chez le users connecter 
    
    public function new()
    {
        $user = Auth::user();
        $articleCount = Commande::where('user_id', $user->id)
            ->where('status', '!=', 'annulée')
            ->count();
        
        $twoDaysAgo = now()->subDays(2);
        $query = Article::where('created_at', '>=', $twoDaysAgo);
        
        $categories = Article::distinct()->pluck('categorie');
        
        if(request()->has('categories') && !request()->input('all_categories', false)) {
            $selectedCategories = request()->input('categories');
            $query->whereIn('categorie', $selectedCategories);
        }
        
        $newItems = $query->get();
        
        return view('user.pages.new', compact('newItems', 'articleCount', 'categories'));
    }
    public function clothes(){
        $user = Auth::user();
    // Compter les articles  commandés par l'utilisateur
          $articleCount = Commande::where('user_id', $user->id)
           ->where('status', '!=', 'annulée')
           ->count();
        $clothes = Article::where('categorie', 'Vêtement')->get();
        return view('user.pages.clothes',compact('clothes','articleCount'));
    }
    public function shoes(){
        $user = Auth::user();
        // Compter les articles  commandés par l'utilisateur
          $articleCount = Commande::where('user_id', $user->id)
           ->where('status', '!=', 'annulée')
           ->count();
        $shoes = Article::where('categorie', 'Chaussure')->get();
        return view('user.pages.shoes',compact('shoes','articleCount'));
    }
    public function accessory(){
        $user = Auth::user();
        // Compter les articles  commandés par l'utilisateur
          $articleCount = Commande::where('user_id', $user->id)
           ->where('status', '!=', 'annulée')
           ->count();
        $accessorys = Article::where('categorie', 'Accessoire')->get();
        return view('user.pages.accessory',compact('accessorys','articleCount'));
    }

    
}
