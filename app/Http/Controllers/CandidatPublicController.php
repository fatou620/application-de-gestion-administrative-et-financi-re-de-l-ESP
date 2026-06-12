<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use App\Models\DossierCandidature;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CandidatPublicController extends Controller
{
    public function showFormulaire()
    {
        $filieres = Filiere::with('departement')->orderBy('nom')->get();
        return view('candidat.formulaire', compact('filieres'));
    }

    public function submitCandidature(Request $request)
    {
        $data = $request->validate([
            'nom'              => ['required', 'string', 'max:80'],
            'prenom'           => ['required', 'string', 'max:80'],
            'email'            => ['required', 'email', 'max:150'],
            'telephone'        => ['nullable', 'string', 'max:30'],
            'date_naissance'   => ['required', 'date', 'before:today'],
            'lieu_naissance'   => ['nullable', 'string', 'max:120'],
            'diplome'          => ['required', 'string', 'max:60'],
            'filiere_voulue_id'=> ['required', 'exists:filieres,id'],
            'pieces'           => ['nullable', 'array'],
            'pieces.*'         => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'libelles'         => ['nullable', 'array'],
            'libelles.*'       => ['string', 'max:100'],
        ]);

        $numero = 'CAND-' . now()->format('Y') . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(substr($data['nom'], 0, 3));

        $candidat = DB::transaction(function () use ($data, $numero, $request) {
            $c = Candidat::create([
                'numero_candidature' => $numero,
                'nom'                => strtoupper($data['nom']),
                'prenom'             => ucfirst($data['prenom']),
                'email'              => $data['email'],
                'telephone'          => $data['telephone'] ?? null,
                'date_naissance'     => $data['date_naissance'],
                'lieu_naissance'     => $data['lieu_naissance'] ?? null,
                'diplome'            => $data['diplome'],
                'filiere_voulue_id'  => $data['filiere_voulue_id'],
                'statut'             => 'nouveau',
            ]);

            if ($request->hasFile('pieces')) {
                foreach ($request->file('pieces') as $i => $file) {
                    $libelle = $data['libelles'][$i] ?? 'Pièce #' . ($i + 1);
                    $path = $file->store('candidatures', 'public');
                    DossierCandidature::create([
                        'candidat_id' => $c->id,
                        'type_piece'  => $libelle,
                        'url_fichier' => $path,
                        'statut'      => 'en_attente',
                        'date_depot'  => now(),
                    ]);
                }
            }

            return $c;
        });

        return redirect()->route('candidat.confirmation', $candidat->numero_candidature);
    }

    public function confirmation(string $numero)
    {
        $candidat = Candidat::where('numero_candidature', $numero)->firstOrFail();
        return view('candidat.confirmation', compact('candidat'));
    }

    public function showSuivi()
    {
        return view('candidat.suivi');
    }

    public function rechercheSuivi(Request $request)
    {
        $data = $request->validate([
            'numero' => ['required', 'string', 'max:50'],
            'email'  => ['required', 'email'],
        ]);

        $candidat = Candidat::where('numero_candidature', $data['numero'])
            ->where('email', $data['email'])
            ->first();

        if (!$candidat) {
            return back()
                ->withInput()
                ->with('error', 'Aucune candidature trouvée avec ce numéro et cet email.');
        }

        return redirect()->route('candidat.details', $candidat->numero_candidature)
            ->with('verified', true);
    }

    public function showDetails(Request $request, string $numero)
    {
        // Petite sécurité : on accepte de montrer les détails uniquement si on vient
        // de la page de confirmation (immédiat après soumission) ou du suivi.
        $candidat = Candidat::where('numero_candidature', $numero)
            ->with(['filiereVoulue.departement', 'dossier'])
            ->firstOrFail();

        return view('candidat.details', compact('candidat'));
    }
}
