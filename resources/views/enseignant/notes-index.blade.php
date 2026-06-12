@extends('layouts.enseignant', ['titlePage' => 'Saisir les notes'])

@section('page_title', 'Saisie des notes')
@section('page_sub', 'Sélectionnez une matière pour saisir les notes')

@section('content')
<div class="card">
  <table>
    <thead>
      <tr>
        <th>Matière</th><th>Filière</th><th>Coeff. / Crédits</th>
        <th>CC saisis</th><th>Examens saisis</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($matieres as $m)
        @php
          $pctCc = $totalEtudiants > 0 ? round($m->nb_cc / $totalEtudiants * 100) : 0;
          $pctEx = $totalEtudiants > 0 ? round($m->nb_examen / $totalEtudiants * 100) : 0;
        @endphp
        <tr>
          <td><strong>{{ $m->nom }}</strong></td>
          <td>{{ $m->filiere?->nom ?? '—' }}</td>
          <td>{{ $m->coefficient }} / {{ $m->credits }}</td>
          <td>
            {{ $m->nb_cc }} / {{ $totalEtudiants }}
            <div style="height:5px;background:#e5e7eb;border-radius:3px;margin-top:4px;width:120px">
              <div style="height:100%;width:{{ $pctCc }}%;background:#00853F;border-radius:3px"></div>
            </div>
          </td>
          <td>
            {{ $m->nb_examen }} / {{ $totalEtudiants }}
            <div style="height:5px;background:#e5e7eb;border-radius:3px;margin-top:4px;width:120px">
              <div style="height:100%;width:{{ $pctEx }}%;background:#3b82f6;border-radius:3px"></div>
            </div>
          </td>
          <td>
            <a href="{{ route('enseignant.notes.edit', $m) }}" class="btn btn-green">
              <i class="fas fa-edit"></i> Saisir
            </a>
          </td>
        </tr>
      @empty
        <tr><td colspan="6"><div class="empty"><i class="fas fa-book"></i> Aucune matière</div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
