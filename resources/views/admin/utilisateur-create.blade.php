@extends('layouts.admin', ['titlePage' => 'Nouvel utilisateur'])

@section('page_title', 'Créer un utilisateur')
@section('page_sub', 'Définissez le rôle et les identifiants du nouveau compte')

@section('content')
<a href="{{ route('admin.utilisateurs') }}" class="btn btn-outline" style="margin-bottom:20px">
  <i class="fas fa-arrow-left"></i> Retour à la liste
</a>

<div class="card" style="max-width:680px">
  <div class="card-head"><h3><i class="fas fa-user-plus"></i> Nouveau compte</h3></div>
  <div style="padding:24px">
    <form method="POST" action="{{ route('admin.utilisateurs.store') }}">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Nom *</label>
          <div class="iw"><i class="fas fa-user"></i>
            <input type="text" name="nom" required value="{{ old('nom') }}" placeholder="DIOP">
          </div>
        </div>
        <div class="form-group">
          <label>Prénom *</label>
          <div class="iw"><i class="fas fa-user"></i>
            <input type="text" name="prenom" required value="{{ old('prenom') }}" placeholder="Amadou">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Email *</label>
        <div class="iw"><i class="fas fa-envelope"></i>
          <input type="email" name="email" required value="{{ old('email') }}" placeholder="utilisateur@esp.sn">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div class="form-group">
          <label>Rôle *</label>
          <div class="iw"><i class="fas fa-shield-alt"></i>
            <select name="role_id" required>
              <option value="">— Choisir —</option>
              @foreach ($roles as $r)
                <option value="{{ $r->id }}" {{ (int) old('role_id') === $r->id ? 'selected' : '' }}>
                  {{ ucfirst(str_replace('_',' ', $r->nom)) }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <div class="iw"><i class="fas fa-phone"></i>
            <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="77 000 00 00">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Mot de passe initial * (min. 6 caractères)</label>
        <div class="iw"><i class="fas fa-lock"></i>
          <input type="text" name="password" required minlength="6" placeholder="password">
        </div>
      </div>

      <button type="submit" class="btn btn-green" style="padding:12px 24px;width:100%;justify-content:center">
        <i class="fas fa-user-plus"></i> Créer l'utilisateur
      </button>
    </form>
  </div>
</div>
@endsection
