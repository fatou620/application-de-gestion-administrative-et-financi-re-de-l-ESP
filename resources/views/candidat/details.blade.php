@extends('layouts.public', ['titlePage' => 'Détails de ma candidature'])

@section('content')
<div class="card">
  <div class="card-head">
    <h2><i class="fas fa-file-alt"></i> Candidature {{ $candidat->numero_candidature }}</h2>
    @php
      $couleurs = [
        'nouveau'   => ['#3b82f6', 'Nouveau dossier'],
        'en_cours'  => ['#f97316', 'En cours d\'examen'],
        'incomplet' => ['#E31E24', 'Dossier incomplet'],
        'valide'    => ['#00853F', 'Candidature acceptée'],
        'rejete'    => ['#E31E24', 'Candidature refusée'],
      ];
      [$couleur, $libelle] = $couleurs[$candidat->statut] ?? ['#6b7280', 'Inconnu'];
    @endphp
    <span style="background:{{ $couleur }}20;color:{{ $couleur }};padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700;text-transform:uppercase">
      {{ $libelle }}
    </span>
  </div>

  <div class="card-body">
    @if ($candidat->statut === 'valide')
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        Votre candidature a été acceptée le {{ $candidat->date_traitement?->format('d/m/Y') }} ! Vous recevrez bientôt les informations d'inscription par email.
      </div>
    @elseif ($candidat->statut === 'rejete' && $candidat->motif_rejet)
      <div class="alert alert-error">
        <i class="fas fa-times-circle"></i>
        Motif du refus : {{ $candidat->motif_rejet }}
      </div>
    @elseif ($candidat->statut === 'incomplet')
      <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        Votre dossier est incomplet. Veuillez compléter les pièces manquantes en soumettant une nouvelle candidature.
      </div>
    @elseif (in_array($candidat->statut, ['nouveau', 'en_cours']))
      <div class="alert alert-info">
        <i class="fas fa-clock"></i>
        Votre dossier est en cours de traitement par notre service administratif. Vous serez notifié de la décision par email.
      </div>
    @endif

    <h3 style="font-size:15px;font-weight:700;margin-top:24px;margin-bottom:14px;color:#00853F">
      <i class="fas fa-user-graduate"></i> Vos informations
    </h3>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px">
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Nom complet</strong><br>{{ $candidat->prenom }} {{ $candidat->nom }}</div>
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Email</strong><br>{{ $candidat->email }}</div>
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Téléphone</strong><br>{{ $candidat->telephone ?? '—' }}</div>
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Date de naissance</strong><br>{{ $candidat->date_naissance?->format('d/m/Y') }}</div>
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Diplôme</strong><br>{{ $candidat->diplome }}</div>
      <div><strong style="font-size:11px;color:#6b7280;text-transform:uppercase">Filière souhaitée</strong><br>{{ $candidat->filiereVoulue->nom ?? '—' }}</div>
    </div>

    <h3 style="font-size:15px;font-weight:700;margin-top:28px;margin-bottom:14px;color:#00853F">
      <i class="fas fa-paperclip"></i> Pièces déposées ({{ count($candidat->dossier) }})
    </h3>
    @if (count($candidat->dossier))
      <table style="width:100%;border-collapse:collapse;background:#fff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden">
        <thead>
          <tr>
            <th style="background:#f8fafc;padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;border-bottom:1px solid #e5e7eb">Type</th>
            <th style="background:#f8fafc;padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;border-bottom:1px solid #e5e7eb">Date</th>
            <th style="background:#f8fafc;padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;border-bottom:1px solid #e5e7eb">Statut</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($candidat->dossier as $piece)
            <tr>
              <td style="padding:12px 14px;border-bottom:1px solid #e5e7eb;font-size:13.5px">{{ $piece->type_piece }}</td>
              <td style="padding:12px 14px;border-bottom:1px solid #e5e7eb;font-size:13px;color:#6b7280">{{ $piece->date_depot->format('d/m/Y H:i') }}</td>
              <td style="padding:12px 14px;border-bottom:1px solid #e5e7eb">
                @php
                  $col = ['en_attente' => '#f97316', 'valide' => '#00853F', 'rejete' => '#E31E24'][$piece->statut] ?? '#6b7280';
                @endphp
                <span style="background:{{ $col }}20;color:{{ $col }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700">
                  {{ ucfirst(str_replace('_',' ', $piece->statut)) }}
                </span>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p style="color:#6b7280;font-style:italic">Aucune pièce déposée.</p>
    @endif
  </div>
</div>
@endsection
