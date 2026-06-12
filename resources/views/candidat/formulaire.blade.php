@extends('layouts.public', ['titlePage' => 'Candidater à l\'ESP'])

@section('content')
<div class="hero">
  <h1><i class="fas fa-edit" style="color:#00853F"></i> Candidater à l'ESP</h1>
  <p>Soumettez votre dossier en ligne en quelques minutes. Vous recevrez un numéro de candidature pour suivre l'évolution de votre dossier.</p>
</div>

<div class="card">
  <div class="card-head"><h2><i class="fas fa-user-graduate"></i> Formulaire de candidature</h2></div>
  <div class="card-body">
    <form method="POST" action="{{ route('candidat.submit') }}" enctype="multipart/form-data" id="cand-form">
      @csrf

      <h3 style="font-size:14px;font-weight:700;margin-bottom:12px;color:#00853F">
        <i class="fas fa-id-badge"></i> Informations personnelles
      </h3>

      <div class="row-2">
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

      <div class="row-2">
        <div class="form-group">
          <label>Email *</label>
          <div class="iw"><i class="fas fa-envelope"></i>
            <input type="email" name="email" required value="{{ old('email') }}" placeholder="amadou@email.com">
          </div>
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <div class="iw"><i class="fas fa-phone"></i>
            <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="77 000 00 00">
          </div>
        </div>
      </div>

      <div class="row-2">
        <div class="form-group">
          <label>Date de naissance *</label>
          <div class="iw"><i class="fas fa-calendar"></i>
            <input type="date" name="date_naissance" required value="{{ old('date_naissance') }}">
          </div>
        </div>
        <div class="form-group">
          <label>Lieu de naissance</label>
          <div class="iw"><i class="fas fa-map-marker-alt"></i>
            <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" placeholder="Dakar">
          </div>
        </div>
      </div>

      <h3 style="font-size:14px;font-weight:700;margin-bottom:12px;margin-top:24px;color:#00853F">
        <i class="fas fa-graduation-cap"></i> Cursus académique
      </h3>

      <div class="row-2">
        <div class="form-group">
          <label>Diplôme actuel *</label>
          <div class="iw"><i class="fas fa-certificate"></i>
            <select name="diplome" required>
              <option value="">— Choisir —</option>
              @foreach (['Bac', 'Bac+1', 'Bac+2', 'Bac+3', 'Licence', 'Master', 'Autre'] as $d)
                <option value="{{ $d }}" {{ old('diplome') === $d ? 'selected' : '' }}>{{ $d }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Filière souhaitée *</label>
          <div class="iw"><i class="fas fa-stream"></i>
            <select name="filiere_voulue_id" required>
              <option value="">— Choisir —</option>
              @foreach ($filieres as $f)
                <option value="{{ $f->id }}" {{ (int) old('filiere_voulue_id') === $f->id ? 'selected' : '' }}>
                  {{ $f->nom }} ({{ $f->departement->nom ?? '—' }})
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <h3 style="font-size:14px;font-weight:700;margin-bottom:12px;margin-top:24px;color:#00853F">
        <i class="fas fa-paperclip"></i> Pièces du dossier
      </h3>
      <p style="font-size:12.5px;color:#6b7280;margin-bottom:12px">
        Formats acceptés : PDF, JPG, PNG · 5 Mo max par fichier. Pièces conseillées : CNI, diplôme, relevé de notes…
      </p>

      <div id="pieces-container">
        <div class="row-2 piece-row" style="margin-bottom:12px">
          <div class="iw"><i class="fas fa-tag"></i>
            <input type="text" name="libelles[]" placeholder="Ex : Carte Nationale d'Identité" maxlength="100">
          </div>
          <div class="iw" style="padding-left:0">
            <input type="file" name="pieces[]" accept=".pdf,.jpg,.jpeg,.png" style="padding-left:14px">
          </div>
        </div>
      </div>

      <button type="button" class="btn btn-outline" onclick="addPiece()" style="margin-bottom:24px">
        <i class="fas fa-plus"></i> Ajouter une pièce
      </button>

      <hr style="border:none;border-top:1px solid #e5e7eb;margin:20px 0">

      <button type="submit" class="btn btn-green" style="width:100%;justify-content:center">
        <i class="fas fa-paper-plane"></i> Soumettre ma candidature
      </button>
    </form>
  </div>
</div>

@push('scripts')
<script>
function addPiece() {
  const tpl = document.querySelector('.piece-row').cloneNode(true);
  tpl.querySelectorAll('input').forEach(i => i.value = '');
  document.getElementById('pieces-container').appendChild(tpl);
}
</script>
@endpush
@endsection
