@php $espaceLabel = 'Espace Enseignant'; @endphp
@extends('layouts.sidebar')

@section('nav')
<div class="nav-section">
  <div class="nav-label">Principal</div>
  <a href="{{ route('enseignant.dashboard') }}" class="nav-link {{ request()->routeIs('enseignant.dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Tableau de bord
  </a>
</div>
<div class="nav-section">
  <div class="nav-label">P&eacute;dagogie</div>
  <a href="{{ route('enseignant.notes') }}" class="nav-link {{ request()->routeIs('enseignant.notes*') ? 'active' : '' }}">
    <i class="fas fa-edit"></i> Saisir les notes
  </a>
  <a href="{{ route('enseignant.emploi') }}" class="nav-link {{ request()->routeIs('enseignant.emploi') ? 'active' : '' }}">
    <i class="fas fa-calendar-week"></i> Emploi du temps
  </a>
  <a href="{{ route('enseignant.etudiants') }}" class="nav-link {{ request()->routeIs('enseignant.etudiants') ? 'active' : '' }}">
    <i class="fas fa-user-graduate"></i> Mes &eacute;tudiants
  </a>
</div>
@endsection
