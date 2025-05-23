<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginVendorRequest;
use App\Http\Requests\VendorRequest;
use App\Models\ResetCodePasswordVendor;
use App\Models\Vendor;
use App\Notifications\SendEmailToVendorAfterRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index(){
        $vendors = Vendor::paginate(6);
        return view('admin.vendeur.index',compact('vendors'));
    }

    public function create(){
        return view('admin.vendeur.create');
    }

    public function store(VendorRequest $request){
        try {
            $vendor = new Vendor();
            $vendor->name = $request->name;
            $vendor->prenom = $request->prenom;
            $vendor->email = $request->email;
            $vendor->password = Hash::make('default');
            $vendor->contact = $request->contact;
            $vendor->dateNaiss = $request->dateNaiss;
            $vendor->commune = $request->commune;
            $vendor->role = $request->role;
            $vendor->save();

            // Envoi de l'e-mail de vérification
                ResetCodePasswordVendor::where('email', $vendor->email)->delete();
                $code = rand(1000, 4000);
                ResetCodePasswordVendor::create([
                    'code' => $code,
                    'email' => $vendor->email,
                ]);

            Notification::route('mail', $vendor->email)
             ->notify(new SendEmailToVendorAfterRegistrationNotification($code, $vendor->email));

            return redirect()->route('vendor.index')->with('succes','Personnel ajouter avec succès');
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function defineAccess($email){
        //Vérification si le sous-admin existe déjà
        $checkSousadminExiste = Vendor::where('email', $email)->first();
    
        if($checkSousadminExiste){
            return view('admin.vendeur.auth.register', compact('email'));
        }else{
            return redirect()->route('user.accueil');
        };
    }

    public function submitDefineAccess(LoginVendorRequest $request){
          
        try {
            
            $vendor = Vendor::where('email', $request->email)->first();
    
            if ($vendor) {
                // Mise à jour du mot de passe
                $vendor->password = Hash::make($request->password);
    
                // Vérifier si une image est uploadée
                if ($request->hasFile('profile_picture')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($vendor->profile_picture) {
                        Storage::delete('public/' . $vendor->profile_picture);
                    }
    
                    // Stocker la nouvelle image
                    $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $vendor->profile_picture = $imagePath;
                }
                $vendor->update();
    
                if($vendor){
                   $existingcode =  ResetCodePasswordVendor::where('email', $vendor->email)->count();
    
                   if($existingcode > 1){
                    ResetCodePasswordVendor::where('email', $vendor->email)->delete();
                   }
                }
    
                return redirect()->route('vendor.login')->with('success', 'Vos accès on été defini avec succès');
            } else {
                return redirect()->route('vendor.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function login(){
        return view('admin.vendeur.auth.login');
    }

    public function handleLogin(Request $request) {
        $request->validate([
            'email' => 'required|exists:vendors',
            'password' => 'required|min:8'
        ],[
            'email.exists' => 'Cet email n\'existe pas',
            'email.required' => 'L\'email est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères'
        ]);
        
        try {
            $credentials = $request->only('email', 'password');
            if (auth()->guard('vendor')->attempt($credentials)) {
                $user = auth()->guard('vendor')->user();
                
                // Vérification du rôle et redirection appropriée
                if ($user->role === 'Personnel') {
                    return redirect()->route('vendor.dashboard');
                } elseif ($user->role === 'Partenaire') {
                    return redirect()->route('fournisseur.dashboard');
                }
                
                // Redirection par défaut si le rôle n'est pas reconnu
                return redirect()->back();
            }
            return back()->withErrors(['email' => 'Mot de passe incorrect']);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            return view('admin.vendeur.edit', compact('vendor'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue');
        }
    }
    
    // Méthode pour traiter la modification
    public function update(Request $request, $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:vendors,email,'.$vendor->id,
                'contact' => 'required|string|max:20',
                'dateNaiss' => 'required|date',
                'commune' => 'required|string|max:255',
                'role' => 'required|in:Personnel,Fournisseur',
            ]);
    
            $vendor->update([
                'name' => $request->name,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'contact' => $request->contact,
                'dateNaiss' => $request->dateNaiss,
                'commune' => $request->commune,
                'role' => $request->role,
            ]);
    
            return redirect()->route('vendor.index')->with('success', 'Personnel mis à jour avec succès');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour');
        }
    }

    public function destroy($id)
{
    try {
        $vendor = Vendor::findOrFail($id);
        
        // Supprimez d'abord le code de réinitialisation associé s'il existe
        ResetCodePasswordVendor::where('email', $vendor->email)->delete();
        
        // Ensuite supprimez le vendeur
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Personnel supprimé avec succès');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Erreur lors de la suppression: '.$e->getMessage());
    }
}
}
