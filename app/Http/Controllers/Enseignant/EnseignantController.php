<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\SeanceCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnseignantController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'nb_matieres'   => Matiere::count(),
            'nb_etudiants'  => Etudiant::count(),
            'nb_notes'      => Note::count(),
            'nb_seances'    => SeanceCours::count(),
        ];

        $matieres = Matiere::with('filiere')
            ->withCount('notes')
            ->orderBy('nom')->get();

        $joursFr = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $jourAujourdhui = $joursFr[now()->dayOfWeek];
        $coursAujourdhui = SeanceCours::with('matiere')
            ->where('jour_semaine', $jourAujourdhui)
            ->orderBy('heure_debut')->get();

        return view('enseignant.dashboard', compact(
            'stats', 'matieres', 'coursAujourdhui', 'jourAujourdhui',
        ));
    }

    public function indexNotes()
    {
        $matieres = Matiere::with('filiere')
            ->withCount(['notes as nb_cc' => fn ($q) => $q->where('type_eval', 'cc')])
            ->withCount(['notes as nb_examen' => fn ($q) => $q->where('type_eval', 'examen')])
            ->orderBy('nom')->get();

        $totalEtudiants = Etudiant::count();

        return view('enseignant.notes-index', compact('matieres', 'totalEtudiants'));
    }

    public function editNotes(Matiere $matiere)
    {
        $etudiants = Etudiant::with('utilisateur')->orderBy('numero_etudiant')->get();

        // Récupère les notes existantes pour cette matière, indexées par etudiant_id + type_eval
        $notes = Note::where('matiere_id', $matiere->id)->get()
            ->groupBy('etudiant_id')
            ->map(fn ($g) => $g->keyBy('type_eval'));

        return view('enseignant.notes-saisir', compact('matiere', 'etudiants', 'notes'));
    }

    public function saveNotes(Request $request, Matiere $matiere)
    {
        $data = $request->validate([
            'notes'              => ['required', 'array'],
            'notes.*.cc'         => ['nullable', 'numeric', 'between:0,20'],
            'notes.*.examen'     => ['nullable', 'numeric', 'between:0,20'],
        ]);

        DB::transaction(function () use ($data, $matiere) {
            foreach ($data['notes'] as $etudiantId => $valeurs) {
                foreach (['cc', 'examen'] as $type) {
                    $val = $valeurs[$type] ?? null;
                    if ($val === null || $val === '') {
                        // Si vide, on supprime la note existante
                        Note::where('etudiant_id', $etudiantId)
                            ->where('matiere_id', $matiere->id)
                            ->where('type_eval', $type)
                            ->delete();
                    } else {
                        Note::updateOrCreate(
                            [
                                'etudiant_id' => $etudiantId,
                                'matiere_id'  => $matiere->id,
                                'type_eval'   => $type,
                            ],
                            ['valeur' => $val],
                        );
                    }
                }
            }
        });

        return redirect()->route('enseignant.notes.edit', $matiere)
            ->with('success', 'Notes enregistrées pour ' . $matiere->nom);
    }

    public function emploi()
    {
        $seances = SeanceCours::with('matiere')
            ->orderByRaw("FIELD(jour_semaine,'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi')")
            ->orderBy('heure_debut')->get();

        $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $emploi = array_fill_keys($jours, []);
        foreach ($seances as $s) {
            $emploi[$s->jour_semaine][] = $s;
        }

        $joursFr = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        $jourAujourdhui = $joursFr[now()->dayOfWeek];

        return view('enseignant.emploi', compact('jours', 'emploi', 'jourAujourdhui'));
    }

    public function etudiants(Request $request)
    {
        $q = Etudiant::with(['utilisateur', 'niveau.filiere'])
            ->select('etudiants.*')
            ->selectSub(
                Note::selectRaw('COUNT(*)')
                    ->whereColumn('etudiant_id', 'etudiants.id'),
                'nb_notes',
            )
            ->selectSub(
                Note::selectRaw('ROUND(AVG(valeur),2)')
                    ->whereColumn('etudiant_id', 'etudiants.id'),
                'moyenne',
            );

        if ($s = $request->query('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('numero_etudiant', 'like', "%$s%")
                  ->orWhereHas('utilisateur', function ($u) use ($s) {
                      $u->where('nom', 'like', "%$s%")->orWhere('prenom', 'like', "%$s%");
                  });
            });
        }

        $etudiants = $q->paginate(20)->withQueryString();

        return view('enseignant.etudiants', compact('etudiants'));
    }
}
