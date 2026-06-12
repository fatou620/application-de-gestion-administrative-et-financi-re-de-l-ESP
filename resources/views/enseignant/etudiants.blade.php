@extends('layouts.enseignant', ['titlePage' => 'Mes étudiants'])

@section('page_title', 'Liste des étudiants')
@section('page_sub', 'Étudiants suivis et leur progression')

@section('content')
<div class="card" style="padding:18px;margin-bottom:20px">
  <form method="GET" style="display:flex;gap:10px;align-items:flex-end">
    <div class="iw" style="flex:1"><i class="fas fa-search"></i>
      <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, prénom ou n° étudiant…">
    </div>
    <button class="btn btn-green">Rechercher</button>
  </form>
</div>

<div class="card">
  <table>
    <thead>
      <tr>
        <th>N° étudiant</th><th>Nom complet</th>
        <th>Filière / Niveau</th><th>Notes</th>
        <th>Moyenne</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($etudiants as $e)
        @php
          $moy = $e->moyenne;
          $col = $moy === null ? '#6b7280' : ($moy >= 14 ? '#00853F' : ($moy >= 10 ? '#f97316' : '#E31E24'));
        @endphp
        <tr>
          <td><code style="font-family:monospace;font-size:12px">{{ $e->numero_etudiant }}</code></td>
          <td><strong>{{ $e->utilisateur?->prenom }} {{ $e->utilisateur?->nom }}</strong></td>
          <td>{{ $e->niveau?->filiere?->nom ?? '—' }} / {{ $e->niveau?->libelle ?? '—' }}</td>
          <td>{{ $e->nb_notes }} note(s)</td>
          <td><span style="font-weight:800;font-size:16px;color:{{ $col }}">{{ $moy ?? '—' }}{{ $moy !== null ? '/20' : '' }}</span></td>
        </tr>
      @empty
        <tr><td colspan="5"><div class="empty"><i class="fas fa-user-graduate"></i> Aucun étudiant trouvé</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px">{{ $etudiants->links() }}</div>
@endsection
