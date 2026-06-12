@extends('layouts.public', ['titlePage' => 'Candidature enregistrée'])

@section('content')
<div class="card" style="text-align:center">
  <div class="card-body" style="padding:48px 32px">
    <div style="width:80px;height:80px;border-radius:50%;background:rgba(0,133,63,.12);color:#00853F;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:36px">
      <i class="fas fa-check-circle"></i>
    </div>
    <h1 style="font-size:24px;font-weight:800;margin-bottom:8px">Candidature enregistrée 🎉</h1>
    <p style="color:#6b7280;font-size:14px;max-width:480px;margin:0 auto 24px;line-height:1.6">
      Votre candidature a bien été soumise. Notez précieusement votre numéro de candidature ci-dessous — il vous servira à suivre l'évolution de votre dossier.
    </p>

    <div style="display:inline-block;background:rgba(0,133,63,.06);border:2px dashed #00853F;border-radius:14px;padding:18px 28px;margin-bottom:24px">
      <div style="font-size:11px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:1px">Numéro de candidature</div>
      <div style="font-size:24px;font-weight:800;color:#00853F;font-family:monospace;margin-top:6px">
        {{ $candidat->numero_candidature }}
      </div>
    </div>

    <div class="alert alert-info" style="text-align:left">
      <i class="fas fa-info-circle"></i>
      Vous recevrez bientôt un email de confirmation à <strong>{{ $candidat->email }}</strong>. Conservez ce numéro pour pouvoir suivre votre candidature à tout moment.
    </div>

    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:20px">
      <a href="{{ route('candidat.details', $candidat->numero_candidature) }}" class="btn btn-green">
        <i class="fas fa-eye"></i> Voir ma candidature
      </a>
      <a href="{{ route('candidat.formulaire') }}" class="btn btn-outline">
        <i class="fas fa-plus"></i> Nouvelle candidature
      </a>
    </div>
  </div>
</div>
@endsection
