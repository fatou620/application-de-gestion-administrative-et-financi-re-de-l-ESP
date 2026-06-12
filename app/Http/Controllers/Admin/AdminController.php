<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Candidat;
use App\Models\Departement;
use App\Models\DocumentNumerique;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Note;
use App\Models\Paiement;
use App\Models\Role;
use App\Models\SeanceCours;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ───────── Dashboard ─────────
    public function dashboard()
    {
        $stats = [
            'utilisateurs' => Utilisateur::count(),
            'etudiants'    => Etudiant::count(),
            'candidats'    => Candidat::count(),
            'matieres'     => Matiere::count(),
            'paiements'    => Paiement::count(),
            'notes'        => Note::count(),
            'documents'    => DocumentNumerique::count(),
            'annonces'     => Annonce::count(),
            'seances'      => SeanceCours::count(),
            'total_encaisse' => (float) Paiement::where('statut', 'valide')->sum('montant'),
        ];

        $parRole = Role::withCount('utilisateurs')->orderBy('nom')->get();

        $recentUsers = Utilisateur::with('role')->orderByDesc('id')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'parRole', 'recentUsers'));
    }

    // ───────── Utilisateurs ─────────
    public function utilisateurs(Request $request)
    {
        $q = Utilisateur::with('role');
        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('nom', 'like', "%$s%")
                  ->orWhere('prenom', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%");
            });
        }
        if ($r = $request->query('role')) {
            $q->whereHas('role', fn ($x) => $x->where('nom', $r));
        }
        $users = $q->orderByDesc('id')->paginate(20)->withQueryString();
        $roles = Role::orderBy('nom')->get();
        return view('admin.utilisateurs', compact('users', 'roles'));
    }

    public function createUtilisateur()
    {
        $roles = Role::orderBy('nom')->get();
        return view('admin.utilisateur-create', compact('roles'));
    }

    public function storeUtilisateur(Request $request)
    {
        $data = $request->validate([
            'nom'      => ['required', 'string', 'max:80'],
            'prenom'   => ['required', 'string', 'max:80'],
            'email'    => ['required', 'email', 'max:150', 'unique:utilisateurs,email'],
            'role_id'  => ['required', 'exists:roles,id'],
            'password' => ['required', 'string', 'min:6'],
            'telephone'=> ['nullable', 'string', 'max:30'],
        ]);

        Utilisateur::create([
            'role_id'      => $data['role_id'],
            'nom'          => strtoupper($data['nom']),
            'prenom'       => ucfirst($data['prenom']),
            'email'        => $data['email'],
            'mot_de_passe' => Hash::make($data['password']),
            'telephone'    => $data['telephone'] ?? null,
            'statut'       => 'actif',
        ]);

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur créé.');
    }

    public function toggleUtilisateur(Utilisateur $utilisateur)
    {
        if ($utilisateur->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        $utilisateur->statut = $utilisateur->statut === 'actif' ? 'inactif' : 'actif';
        $utilisateur->save();
        return back()->with('success', 'Statut mis à jour : '.$utilisateur->statut);
    }

    public function changerRoleUtilisateur(Request $request, Utilisateur $utilisateur)
    {
        $data = $request->validate(['role_id' => ['required', 'exists:roles,id']]);
        if ($utilisateur->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas changer votre propre rôle.');
        }
        $utilisateur->role_id = $data['role_id'];
        $utilisateur->save();
        return back()->with('success', 'Rôle mis à jour.');
    }

    public function destroyUtilisateur(Utilisateur $utilisateur)
    {
        if ($utilisateur->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        try {
            $utilisateur->delete();
            return back()->with('success', 'Utilisateur supprimé.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Impossible de supprimer : cet utilisateur est lié à d\'autres données.');
        }
    }

    // ───────── Structure académique ─────────
    public function structure()
    {
        $departements = Departement::withCount('filieres')->orderBy('nom')->get();
        $filieres     = Filiere::with('departement')->withCount(['niveaux'])->orderBy('nom')->get();
        $niveaux      = Niveau::with('filiere.departement')->orderBy('libelle')->get();
        $matieres     = Matiere::with('filiere.departement')->orderBy('nom')->get();
        return view('admin.structure', compact('departements', 'filieres', 'niveaux', 'matieres'));
    }

    public function storeDepartement(Request $request)
    {
        $data = $request->validate([
            'nom'  => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:20', 'unique:departements,code'],
        ]);
        Departement::create($data);
        return back()->with('success', 'Département ajouté.');
    }

    public function destroyDepartement(Departement $departement)
    {
        try { $departement->delete(); return back()->with('success', 'Département supprimé.'); }
        catch (\Throwable $e) { return back()->with('error', 'Impossible : des filières y sont rattachées.'); }
    }

    public function storeFiliere(Request $request)
    {
        $data = $request->validate([
            'departement_id' => ['required', 'exists:departements,id'],
            'nom'            => ['required', 'string', 'max:150'],
            'code'           => ['required', 'string', 'max:30', 'unique:filieres,code'],
        ]);
        Filiere::create($data);
        return back()->with('success', 'Filière ajoutée.');
    }

    public function destroyFiliere(Filiere $filiere)
    {
        try { $filiere->delete(); return back()->with('success', 'Filière supprimée.'); }
        catch (\Throwable $e) { return back()->with('error', 'Impossible : des niveaux ou matières y sont rattachés.'); }
    }

    public function storeNiveau(Request $request)
    {
        $data = $request->validate([
            'filiere_id' => ['required', 'exists:filieres,id'],
            'libelle'    => ['required', 'string', 'max:50'],
        ]);
        Niveau::create($data);
        return back()->with('success', 'Niveau ajouté.');
    }

    public function destroyNiveau(Niveau $niveau)
    {
        try { $niveau->delete(); return back()->with('success', 'Niveau supprimé.'); }
        catch (\Throwable $e) { return back()->with('error', 'Impossible : des étudiants y sont rattachés.'); }
    }

    public function storeMatiere(Request $request)
    {
        $data = $request->validate([
            'filiere_id'  => ['required', 'exists:filieres,id'],
            'nom'         => ['required', 'string', 'max:150'],
            'credits'     => ['required', 'integer', 'min:1', 'max:30'],
            'coefficient' => ['required', 'numeric', 'min:0.5', 'max:10'],
        ]);
        Matiere::create($data);
        return back()->with('success', 'Matière ajoutée.');
    }

    public function destroyMatiere(Matiere $matiere)
    {
        try { $matiere->delete(); return back()->with('success', 'Matière supprimée.'); }
        catch (\Throwable $e) { return back()->with('error', 'Impossible : des notes ou séances y sont rattachées.'); }
    }
}
