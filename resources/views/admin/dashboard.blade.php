@extends('layouts.admin', ['titlePage' => 'Tableau de bord — Admin'])

@section('page_title', 'Tableau de bord — Administrateur')
@section('page_sub', 'Vue d\'ensemble de la plateforme')

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue"><i class="fas fa-users"></i></div>
    <div><div class="stat-value">{{ $stats['utilisateurs'] }}</div><div class="stat-label">Utilisateurs</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="fas fa-user-graduate"></i></div>
    <div><div class="stat-value">{{ $stats['etudiants'] }}</div><div class="stat-label">Étudiants</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="fas fa-user-plus"></i></div>
    <div><div class="stat-value">{{ $stats['candidats'] }}</div><div class="stat-label">Candidats</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon orange"><i class="fas fa-coins"></i></div>
    <div><div class="stat-value">{{ number_format($stats['total_encaisse'], 0, ',', ' ') }}</div><div class="stat-label">FCFA encaissés</div></div>
  </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(5,1fr)">
  <div class="stat-card" style="padding:14px"><div class="stat-icon green" style="width:36px;height:36px;font-size:15px"><i class="fas fa-book"></i></div><div><div class="stat-value" style="font-size:20px">{{ $stats['matieres'] }}</div><div class="stat-label">Matières</div></div></div>
  <div class="stat-card" style="padding:14px"><div class="stat-icon blue" style="width:36px;height:36px;font-size:15px"><i class="fas fa-star"></i></div><div><div class="stat-value" style="font-size:20px">{{ $stats['notes'] }}</div><div class="stat-label">Notes</div></div></div>
  <div class="stat-card" style="padding:14px"><div class="stat-icon orange" style="width:36px;height:36px;font-size:15px"><i class="fas fa-receipt"></i></div><div><div class="stat-value" style="font-size:20px">{{ $stats['paiements'] }}</div><div class="stat-label">Paiements</div></div></div>
  <div class="stat-card" style="padding:14px"><div class="stat-icon purple" style="width:36px;height:36px;font-size:15px"><i class="fas fa-file-alt"></i></div><div><div class="stat-value" style="font-size:20px">{{ $stats['documents'] }}</div><div class="stat-label">Documents</div></div></div>
  <div class="stat-card" style="padding:14px"><div class="stat-icon red" style="width:36px;height:36px;font-size:15px"><i class="fas fa-bullhorn"></i></div><div><div class="stat-value" style="font-size:20px">{{ $stats['annonces'] }}</div><div class="stat-label">Annonces</div></div></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-head"><h3><i class="fas fa-shield-alt"></i> Répartition par rôle</h3></div>
    <div class="card-body-inner">
      @foreach ($parRole as $r)
        @php
          $pct = $stats['utilisateurs'] > 0 ? round($r->utilisateurs_count / $stats['utilisateurs'] * 100, 1) : 0;
          $cols = ['etudiant' => '#3b82f6', 'enseignant' => '#8b5cf6', 'agent_administratif' => '#00853F', 'responsable_financier' => '#f97316', 'admin' => '#E31E24'];
          $col = $cols[$r->nom] ?? '#6b7280';
        @endphp
        <div style="margin-bottom:14px">
          <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;margin-bottom:6px">
            <span>{{ ucfirst(str_replace('_',' ', $r->nom)) }}</span>
            <span>{{ $r->utilisateurs_count }} ({{ $pct }}%)</span>
          </div>
          <div style="height:8px;background:#e5e7eb;border-radius:4px;overflow:hidden">
            <div style="height:100%;width:{{ $pct }}%;background:{{ $col }}"></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <h3><i class="fas fa-user-clock"></i> Derniers utilisateurs créés</h3>
      <a href="{{ route('admin.utilisateurs') }}" class="see-all">Tout voir →</a>
    </div>
    <div class="card-body-inner" style="padding:0">
      <table>
        <thead><tr><th>Nom complet</th><th>Email</th><th>Rôle</th></tr></thead>
        <tbody>
          @forelse ($recentUsers as $u)
            <tr>
              <td><strong>{{ $u->prenom }} {{ $u->nom }}</strong></td>
              <td>{{ $u->email }}</td>
              <td><span class="badge-status badge-valide" style="background:#eff6ff;color:#3b82f6">{{ ucfirst(str_replace('_',' ', $u->role?->nom ?? '—')) }}</span></td>
            </tr>
          @empty
            <tr><td colspan="3"><div class="empty">Aucun utilisateur</div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card" style="padding:20px;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,rgba(0,133,63,.05),rgba(253,239,66,.05))">
  <div>
    <h3 style="font-size:15px;font-weight:700;margin-bottom:4px">Actions rapides</h3>
    <p style="font-size:12.5px;color:#6b7280">Gérez les utilisateurs et la structure</p>
  </div>
  <div style="display:flex;gap:10px;flex-wrap:wrap">
    <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-green">
      <i class="fas fa-user-plus"></i> Créer un utilisateur
    </a>
    <a href="{{ route('admin.structure') }}" class="btn btn-outline">
      <i class="fas fa-sitemap"></i> Gérer la structure
    </a>
  </div>
</div>
@endsection
