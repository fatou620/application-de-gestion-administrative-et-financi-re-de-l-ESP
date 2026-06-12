@extends('layouts.enseignant', ['titlePage' => 'Emploi du temps — Enseignant'])

@section('page_title', 'Mon emploi du temps')
@section('page_sub', 'Planning hebdomadaire')

@section('content')
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px" class="edt-grid">
  @foreach ($jours as $j)
    <div class="card">
      <div style="padding:14px 18px;font-size:14px;font-weight:700;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;{{ $j === $jourAujourdhui ? 'background:rgba(0,133,63,.08);color:#00853F' : '' }}">
        <span>{{ $j }}</span>
        @if ($j === $jourAujourdhui)
          <span style="background:#00853F;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px">Aujourd'hui</span>
        @endif
      </div>
      @forelse ($emploi[$j] as $s)
        <div style="padding:12px 18px;border-bottom:1px solid #e5e7eb;display:flex;gap:12px;align-items:flex-start">
          <div style="background:rgba(0,133,63,.1);color:#00853F;border-radius:8px;padding:6px 10px;font-size:11px;font-weight:700;min-width:70px;text-align:center">
            {{ substr($s->heure_debut, 0, 5) }}<br>{{ substr($s->heure_fin, 0, 5) }}
          </div>
          <div>
            <div style="font-size:13px;font-weight:600">{{ $s->matiere->nom }}</div>
            <div style="font-size:11px;color:#6b7280"><i class="fas fa-door-open"></i> Salle {{ $s->salle }}</div>
            @if ($s->enseignant)<div style="font-size:11px;color:#6b7280"><i class="fas fa-chalkboard-teacher"></i> {{ $s->enseignant }}</div>@endif
          </div>
        </div>
      @empty
        <div class="empty"><i class="fas fa-coffee"></i> Pas de cours</div>
      @endforelse
    </div>
  @endforeach
</div>

@push('styles')
<style>
  @media(max-width:1000px) { .edt-grid { grid-template-columns:1fr 1fr !important; } }
  @media(max-width:650px)  { .edt-grid { grid-template-columns:1fr !important; } }
</style>
@endpush
@endsection
