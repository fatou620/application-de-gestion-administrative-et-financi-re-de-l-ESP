@extends('layouts.enseignant', ['titlePage' => 'Tableau de bord — Enseignant'])

@section('page_title', 'Tableau de bord — Enseignant')
@section('page_sub', \Carbon\Carbon::now()->translatedFormat('l d F Y'))

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-book"></i></div>
    <div>
      <div class="stat-value">{{ $stats['nb_matieres'] }}</div>
      <div class="stat-label">Matières enseignées</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-user-graduate"></i></div>
    <div>
      <div class="stat-value">{{ $stats['nb_etudiants'] }}</div>
      <div class="stat-label">Étudiants suivis</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange"><i class="fas fa-edit"></i></div>
    <div>
      <div class="stat-value">{{ $stats['nb_notes'] }}</div>
      <div class="stat-label">Notes saisies</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fas fa-calendar-week"></i></div>
    <div>
      <div class="stat-value">{{ $stats['nb_seances'] }}</div>
      <div class="stat-label">Séances cette semaine</div>
    </div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head">
      <h3><i class="fas fa-book-open"></i> Mes matières</h3>
      <a href="{{ route('enseignant.notes') }}" class="see-all">Saisir notes →</a>
    </div>
    <div class="card-body-inner" style="padding:0">
      <table>
        <thead><tr><th>Matière</th><th>Filière</th><th>Coeff.</th><th>Crédits</th><th>Notes saisies</th></tr></thead>
        <tbody>
          @forelse ($matieres as $m)
            <tr>
              <td><strong>{{ $m->nom }}</strong></td>
              <td>{{ $m->filiere?->nom ?? '—' }}</td>
              <td>{{ $m->coefficient }}</td>
              <td>{{ $m->credits }}</td>
              <td>{{ $m->notes_count }}</td>
            </tr>
          @empty
            <tr><td colspan="5"><div class="empty">Aucune matière</div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <div class="card-head"><h3><i class="fas fa-calendar-day"></i> Aujourd'hui — {{ $jourAujourdhui }}</h3></div>
    <div class="card-body-inner">
      @forelse ($coursAujourdhui as $c)
        <div style="display:flex;gap:14px;align-items:center;padding:10px 0;border-bottom:1px solid #e5e7eb">
          <div style="background:rgba(0,133,63,.08);color:#00853F;border-radius:8px;padding:6px 10px;font-size:12px;font-weight:700;text-align:center;min-width:70px">
            {{ substr($c->heure_debut, 0, 5) }}<br>{{ substr($c->heure_fin, 0, 5) }}
          </div>
          <div>
            <div style="font-size:13.5px;font-weight:600">{{ $c->matiere->nom }}</div>
            <div style="font-size:11px;color:#6b7280"><i class="fas fa-map-marker-alt"></i> Salle {{ $c->salle }}</div>
          </div>
        </div>
      @empty
        <div class="empty"><i class="fas fa-coffee"></i> Pas de cours ce jour</div>
      @endforelse
    </div>
  </div>
</div>

<div class="card" style="padding:20px;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,rgba(0,133,63,.05),rgba(253,239,66,.05))">
  <div>
    <h3 style="font-size:15px;font-weight:700;margin-bottom:4px">Actions rapides</h3>
    <p style="font-size:12.5px;color:#6b7280">Accédez directement à vos tâches principales</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <a href="{{ route('enseignant.notes') }}" class="btn btn-green">
      <i class="fas fa-edit"></i> Saisir les notes
    </a>
    <a href="{{ route('enseignant.emploi') }}" class="btn btn-outline">
      <i class="fas fa-calendar-week"></i> Mon emploi
    </a>
  </div>
</div>
@endsection
