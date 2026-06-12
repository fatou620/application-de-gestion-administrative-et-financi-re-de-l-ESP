@extends('layouts.admin', ['titlePage' => 'Utilisateurs — Admin'])

@section('page_title', 'Gestion des utilisateurs')
@section('page_sub', 'Créer, modifier le rôle ou désactiver des comptes')

@section('content')
<div class="card" style="padding:18px;margin-bottom:20px;display:flex;gap:14px;align-items:center;flex-wrap:wrap">
  <form method="GET" style="display:flex;gap:8px;flex:1;min-width:240px">
    <div class="iw" style="flex:1"><i class="fas fa-search"></i>
      <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, prénom ou email…">
    </div>
    <select name="role" style="padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-family:inherit">
      <option value="">— Tous rôles —</option>
      @foreach ($roles as $r)
        <option value="{{ $r->nom }}" {{ request('role') === $r->nom ? 'selected' : '' }}>
          {{ ucfirst(str_replace('_',' ', $r->nom)) }}
        </option>
      @endforeach
    </select>
    <button class="btn btn-green">Filtrer</button>
  </form>
  <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-green">
    <i class="fas fa-user-plus"></i> Nouvel utilisateur
  </a>
</div>

<div class="card">
  <table>
    <thead>
      <tr><th>ID</th><th>Nom complet</th><th>Email</th><th>Rôle</th><th>Statut</th><th>Actions</th></tr>
    </thead>
    <tbody>
      @forelse ($users as $u)
        <tr>
          <td>#{{ $u->id }}</td>
          <td><strong>{{ $u->prenom }} {{ $u->nom }}</strong></td>
          <td>{{ $u->email }}</td>
          <td>
            <form method="POST" action="{{ route('admin.utilisateurs.role', $u) }}" style="display:flex;gap:6px;align-items:center">
              @csrf
              <select name="role_id" style="padding:6px 10px;border:1px solid #e5e7eb;border-radius:8px;font-size:12px">
                @foreach ($roles as $r)
                  <option value="{{ $r->id }}" {{ $r->id === $u->role_id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ', $r->nom)) }}
                  </option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-outline" style="padding:6px 10px;font-size:11px">OK</button>
            </form>
          </td>
          <td>
            <span class="badge-status badge-{{ $u->statut === 'actif' ? 'valide' : 'rejete' }}">
              {{ ucfirst($u->statut) }}
            </span>
          </td>
          <td style="display:flex;gap:6px;flex-wrap:wrap">
            <form method="POST" action="{{ route('admin.utilisateurs.toggle', $u) }}">
              @csrf
              <button class="btn btn-outline" style="padding:7px 10px;font-size:12px">
                <i class="fas fa-power-off"></i> {{ $u->statut === 'actif' ? 'Désactiver' : 'Activer' }}
              </button>
            </form>
            <form method="POST" action="{{ route('admin.utilisateurs.destroy', $u) }}"
                  onsubmit="return confirm('Supprimer définitivement {{ $u->prenom }} {{ $u->nom }} ?')">
              @csrf @method('DELETE')
              <button class="btn btn-red" style="padding:7px 10px;font-size:12px">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6"><div class="empty"><i class="fas fa-users"></i> Aucun utilisateur</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:20px">{{ $users->links() }}</div>
@endsection
