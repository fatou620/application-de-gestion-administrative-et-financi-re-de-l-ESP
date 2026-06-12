@php $espaceLabel = 'Administrateur'; @endphp
@extends('layouts.sidebar')

@section('nav')
<div class="nav-section">
  <div class="nav-label">Principal</div>
  <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="fas fa-home"></i> Tableau de bord
  </a>
</div>
<div class="nav-section">
  <div class="nav-label">Gestion</div>
  <a href="{{ route('admin.utilisateurs') }}" class="nav-link {{ request()->routeIs('admin.utilisateurs*') ? 'active' : '' }}">
    <i class="fas fa-users-cog"></i> Utilisateurs
  </a>
  <a href="{{ route('admin.structure') }}" class="nav-link {{ request()->routeIs('admin.structure*') ? 'active' : '' }}">
    <i class="fas fa-sitemap"></i> Structure académique
  </a>
</div>
@endsection
