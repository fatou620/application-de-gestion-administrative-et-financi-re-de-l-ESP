@extends('layouts.admin', ['titlePage' => 'Structure académique'])

@section('page_title', 'Structure académique')
@section('page_sub', 'Départements, filières, niveaux et matières')

@section('content')
<div style="display:flex;gap:4px;margin-bottom:24px;background:#fff;border-radius:12px;padding:6px;border:1px solid #e5e7eb;width:fit-content;flex-wrap:wrap">
  <button class="btn btn-green" onclick="showTab('dept')" id="t-dept"><i class="fas fa-building"></i> Départements ({{ count($departements) }})</button>
  <button class="btn btn-outline" onclick="showTab('fil')" id="t-fil"><i class="fas fa-stream"></i> Filières ({{ count($filieres) }})</button>
  <button class="btn btn-outline" onclick="showTab('niv')" id="t-niv"><i class="fas fa-layer-group"></i> Niveaux ({{ count($niveaux) }})</button>
  <button class="btn btn-outline" onclick="showTab('mat')" id="t-mat"><i class="fas fa-book"></i> Matières ({{ count($matieres) }})</button>
</div>

{{-- ===== DÉPARTEMENTS ===== --}}
<div id="panel-dept">
  <div class="card" style="padding:20px;margin-bottom:20px">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:14px"><i class="fas fa-plus-circle" style="color:#00853F"></i> Ajouter un département</h3>
    <form method="POST" action="{{ route('admin.structure.departement.store') }}" style="display:grid;grid-template-columns:1fr 200px auto;gap:12px;align-items:end">
      @csrf
      <div class="form-group" style="margin:0"><label>Nom</label>
        <div class="iw"><i class="fas fa-building"></i><input type="text" name="nom" required placeholder="Génie Informatique"></div>
      </div>
      <div class="form-group" style="margin:0"><label>Code</label>
        <div class="iw"><i class="fas fa-tag"></i><input type="text" name="code" required maxlength="20" placeholder="GI" style="text-transform:uppercase"></div>
      </div>
      <button class="btn btn-green" style="padding:11px 22px"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
  </div>

  <div class="card">
    <table>
      <thead><tr><th>Code</th><th>Nom</th><th>Filières</th><th>Action</th></tr></thead>
      <tbody>
        @forelse ($departements as $d)
          <tr>
            <td><code>{{ $d->code }}</code></td>
            <td><strong>{{ $d->nom }}</strong></td>
            <td>{{ $d->filieres_count }}</td>
            <td>
              <form method="POST" action="{{ route('admin.structure.departement.destroy', $d) }}" onsubmit="return confirm('Supprimer ce département ?')">
                @csrf @method('DELETE')
                <button class="btn btn-red" style="padding:6px 12px;font-size:12px"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4"><div class="empty">Aucun département</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ===== FILIÈRES ===== --}}
<div id="panel-fil" style="display:none">
  <div class="card" style="padding:20px;margin-bottom:20px">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:14px"><i class="fas fa-plus-circle" style="color:#00853F"></i> Ajouter une filière</h3>
    <form method="POST" action="{{ route('admin.structure.filiere.store') }}" style="display:grid;grid-template-columns:1fr 1fr 160px auto;gap:12px;align-items:end">
      @csrf
      <div class="form-group" style="margin:0"><label>Département</label>
        <div class="iw"><i class="fas fa-building"></i>
          <select name="departement_id" required>
            <option value="">— Choisir —</option>
            @foreach ($departements as $d)<option value="{{ $d->id }}">{{ $d->nom }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="form-group" style="margin:0"><label>Nom</label>
        <div class="iw"><i class="fas fa-stream"></i><input type="text" name="nom" required placeholder="Génie Logiciel"></div>
      </div>
      <div class="form-group" style="margin:0"><label>Code</label>
        <div class="iw"><i class="fas fa-tag"></i><input type="text" name="code" required maxlength="30" placeholder="GL"></div>
      </div>
      <button class="btn btn-green" style="padding:11px 22px"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
  </div>

  <div class="card">
    <table>
      <thead><tr><th>Code</th><th>Nom</th><th>Département</th><th>Niveaux</th><th>Action</th></tr></thead>
      <tbody>
        @forelse ($filieres as $f)
          <tr>
            <td><code>{{ $f->code }}</code></td>
            <td><strong>{{ $f->nom }}</strong></td>
            <td>{{ $f->departement?->nom }}</td>
            <td>{{ $f->niveaux_count }}</td>
            <td>
              <form method="POST" action="{{ route('admin.structure.filiere.destroy', $f) }}" onsubmit="return confirm('Supprimer cette filière ?')">
                @csrf @method('DELETE')
                <button class="btn btn-red" style="padding:6px 12px;font-size:12px"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5"><div class="empty">Aucune filière</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ===== NIVEAUX ===== --}}
<div id="panel-niv" style="display:none">
  <div class="card" style="padding:20px;margin-bottom:20px">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:14px"><i class="fas fa-plus-circle" style="color:#00853F"></i> Ajouter un niveau</h3>
    <form method="POST" action="{{ route('admin.structure.niveau.store') }}" style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end">
      @csrf
      <div class="form-group" style="margin:0"><label>Filière</label>
        <div class="iw"><i class="fas fa-stream"></i>
          <select name="filiere_id" required>
            <option value="">— Choisir —</option>
            @foreach ($filieres as $f)<option value="{{ $f->id }}">{{ $f->nom }} ({{ $f->departement?->nom }})</option>@endforeach
          </select>
        </div>
      </div>
      <div class="form-group" style="margin:0"><label>Libellé (ex: L1, M2)</label>
        <div class="iw"><i class="fas fa-layer-group"></i><input type="text" name="libelle" required placeholder="L3"></div>
      </div>
      <button class="btn btn-green" style="padding:11px 22px"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
  </div>

  <div class="card">
    <table>
      <thead><tr><th>Libellé</th><th>Filière</th><th>Département</th><th>Action</th></tr></thead>
      <tbody>
        @forelse ($niveaux as $n)
          <tr>
            <td><strong>{{ $n->libelle }}</strong></td>
            <td>{{ $n->filiere?->nom ?? '—' }}</td>
            <td>{{ $n->filiere?->departement?->nom ?? '—' }}</td>
            <td>
              <form method="POST" action="{{ route('admin.structure.niveau.destroy', $n) }}" onsubmit="return confirm('Supprimer ce niveau ?')">
                @csrf @method('DELETE')
                <button class="btn btn-red" style="padding:6px 12px;font-size:12px"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4"><div class="empty">Aucun niveau</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ===== MATIÈRES ===== --}}
<div id="panel-mat" style="display:none">
  <div class="card" style="padding:20px;margin-bottom:20px">
    <h3 style="font-size:15px;font-weight:700;margin-bottom:14px"><i class="fas fa-plus-circle" style="color:#00853F"></i> Ajouter une matière</h3>
    <form method="POST" action="{{ route('admin.structure.matiere.store') }}" style="display:grid;grid-template-columns:2fr 1fr 100px 100px auto;gap:12px;align-items:end">
      @csrf
      <div class="form-group" style="margin:0"><label>Filière</label>
        <div class="iw"><i class="fas fa-stream"></i>
          <select name="filiere_id" required>
            <option value="">— Choisir —</option>
            @foreach ($filieres as $f)<option value="{{ $f->id }}">{{ $f->nom }}</option>@endforeach
          </select>
        </div>
      </div>
      <div class="form-group" style="margin:0"><label>Nom</label>
        <div class="iw"><i class="fas fa-book"></i><input type="text" name="nom" required placeholder="Sécurité Web"></div>
      </div>
      <div class="form-group" style="margin:0"><label>Crédits</label>
        <div class="iw"><i class="fas fa-coins"></i><input type="number" name="credits" required min="1" max="30" value="3"></div>
      </div>
      <div class="form-group" style="margin:0"><label>Coeff.</label>
        <div class="iw"><i class="fas fa-balance-scale"></i><input type="number" step="0.5" name="coefficient" required min="0.5" max="10" value="2.0"></div>
      </div>
      <button class="btn btn-green" style="padding:11px 22px"><i class="fas fa-plus"></i> Ajouter</button>
    </form>
  </div>

  <div class="card">
    <table>
      <thead><tr><th>Nom</th><th>Filière</th><th>Crédits</th><th>Coeff.</th><th>Action</th></tr></thead>
      <tbody>
        @forelse ($matieres as $m)
          <tr>
            <td><strong>{{ $m->nom }}</strong></td>
            <td>{{ $m->filiere?->nom ?? '—' }}</td>
            <td>{{ $m->credits }}</td>
            <td>{{ $m->coefficient }}</td>
            <td>
              <form method="POST" action="{{ route('admin.structure.matiere.destroy', $m) }}" onsubmit="return confirm('Supprimer cette matière ?')">
                @csrf @method('DELETE')
                <button class="btn btn-red" style="padding:6px 12px;font-size:12px"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5"><div class="empty">Aucune matière</div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script>
function showTab(name) {
  ['dept','fil','niv','mat'].forEach(t => {
    document.getElementById('panel-'+t).style.display = t === name ? '' : 'none';
    document.getElementById('t-'+t).className = 'btn ' + (t === name ? 'btn-green' : 'btn-outline');
  });
}
</script>
@endpush
@endsection
