@extends('layouts.public', ['titlePage' => 'Suivre ma candidature'])

@section('content')
<div class="hero">
  <h1><i class="fas fa-search" style="color:#00853F"></i> Suivre ma candidature</h1>
  <p>Renseignez votre numéro de candidature et votre email pour consulter l'état de votre dossier.</p>
</div>

<div class="card" style="max-width:520px;margin:0 auto">
  <div class="card-body" style="padding:32px">
    <form method="POST" action="{{ route('candidat.suivi.recherche') }}">
      @csrf
      <div class="form-group">
        <label>Numéro de candidature</label>
        <div class="iw"><i class="fas fa-id-card"></i>
          <input type="text" name="numero" required value="{{ old('numero') }}" placeholder="CAND-2026-XXXX-XXX" style="font-family:monospace">
        </div>
      </div>
      <div class="form-group">
        <label>Email utilisé lors de la candidature</label>
        <div class="iw"><i class="fas fa-envelope"></i>
          <input type="email" name="email" required value="{{ old('email') }}" placeholder="votre@email.com">
        </div>
      </div>
      <button type="submit" class="btn btn-green" style="width:100%;justify-content:center">
        <i class="fas fa-search"></i> Consulter mon dossier
      </button>
    </form>
  </div>
</div>
@endsection
