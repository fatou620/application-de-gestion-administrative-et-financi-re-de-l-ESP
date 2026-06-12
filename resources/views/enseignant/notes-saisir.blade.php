@extends('layouts.enseignant', ['titlePage' => 'Saisir notes — '.$matiere->nom])

@section('page_title', 'Saisie des notes — '.$matiere->nom)
@section('page_sub', 'Coeff. '.$matiere->coefficient.' · '.$matiere->credits.' crédits · Moyenne pondérée = CC × 40% + Examen × 60%')

@section('content')
<a href="{{ route('enseignant.notes') }}" class="btn btn-outline" style="margin-bottom:20px">
  <i class="fas fa-arrow-left"></i> Retour à la liste des matières
</a>

<form method="POST" action="{{ route('enseignant.notes.save', $matiere) }}">
  @csrf
  <div class="card">
    <div class="card-head">
      <h3><i class="fas fa-edit"></i> Notes des étudiants ({{ count($etudiants) }})</h3>
      <button type="submit" class="btn btn-green">
        <i class="fas fa-save"></i> Enregistrer
      </button>
    </div>
    <table>
      <thead>
        <tr>
          <th>N° étudiant</th><th>Nom complet</th>
          <th style="width:140px">CC /20</th>
          <th style="width:140px">Examen /20</th>
          <th style="width:120px">Moyenne</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($etudiants as $e)
          @php
            $cc = $notes[$e->id]['cc']->valeur ?? null;
            $ex = $notes[$e->id]['examen']->valeur ?? null;
            $moy = null;
            if ($cc !== null && $ex !== null)      $moy = round($cc * 0.4 + $ex * 0.6, 2);
            elseif ($ex !== null)                  $moy = (float) $ex;
            elseif ($cc !== null)                  $moy = (float) $cc;
            $col = $moy === null ? '#6b7280' : ($moy >= 14 ? '#00853F' : ($moy >= 10 ? '#f97316' : '#E31E24'));
          @endphp
          <tr>
            <td><code style="font-family:monospace;font-size:12px">{{ $e->numero_etudiant }}</code></td>
            <td><strong>{{ $e->utilisateur?->prenom }} {{ $e->utilisateur?->nom }}</strong></td>
            <td>
              <input type="number" step="0.25" min="0" max="20"
                     name="notes[{{ $e->id }}][cc]"
                     value="{{ $cc !== null ? number_format($cc, 2, '.', '') : '' }}"
                     placeholder="—"
                     style="width:100%;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:inherit;font-size:14px">
            </td>
            <td>
              <input type="number" step="0.25" min="0" max="20"
                     name="notes[{{ $e->id }}][examen]"
                     value="{{ $ex !== null ? number_format($ex, 2, '.', '') : '' }}"
                     placeholder="—"
                     style="width:100%;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:inherit;font-size:14px">
            </td>
            <td>
              <span style="font-weight:800;font-size:16px;color:{{ $col }}">
                {{ $moy !== null ? number_format($moy, 2) : '—' }}
              </span>
            </td>
          </tr>
        @empty
          <tr><td colspan="5"><div class="empty"><i class="fas fa-user-graduate"></i> Aucun étudiant</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px;text-align:right">
    <button type="submit" class="btn btn-green" style="padding:14px 28px">
      <i class="fas fa-save"></i> Enregistrer toutes les notes
    </button>
  </div>
</form>

<div class="alert alert-info" style="margin-top:20px;font-size:12.5px;background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;padding:12px 16px;border-radius:10px;display:flex;align-items:center;gap:10px">
  <i class="fas fa-info-circle"></i>
  Astuce : laisser un champ vide supprimera la note correspondante si elle existe.
</div>
@endsection
