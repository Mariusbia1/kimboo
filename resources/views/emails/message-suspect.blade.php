<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:'Helvetica Neue',Arial,sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background:#FCB315; padding:32px; text-align:center;">
            <h1 style="margin:0; font-size:28px; font-weight:900; color:#000; letter-spacing:-1px;">kimboo</h1>
        </div>

        <!-- Body -->
        <div style="padding:40px 32px;">
            <h2 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#000;">
                Activité suspecte sur votre compte
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $alert->sender->name }}</strong>,<br>
                Nous avons détecté un message suspect envoyé depuis votre compte.
            </p>

            <!-- Card alerte -->
            <div style="background:#FEF2F2; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #EF4444;">
                <p style="margin:0 0 8px; font-size:13px; color:#EF4444; font-weight:700; text-transform:uppercase;">
                    Type de violation :
                    @if($alert->alert_type === 'bank') Coordonnées bancaires ou carte de paiement
                    @elseif($alert->alert_type === 'link') Lien externe détecté
                    @else Contenu suspect non autorisé
                    @endif
                </p>
                <p style="margin:0; font-size:14px; color:#666; line-height:1.6;">
                    Ce type de contenu n'est pas autorisé sur Kimboo pour protéger les utilisateurs.
                </p>
            </div>

            <div style="background:#FFF8E7; border-radius:12px; padding:16px 20px; margin-bottom:28px;">
                <p style="margin:0; font-size:14px; color:#555; line-height:1.6;">
                    En cas de récidive, votre compte pourra être suspendu. Si vous pensez que c'est une erreur, contactez notre support.
                </p>
            </div>

            <a href="{{ url('/messages') }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 28px; border-radius:12px; text-decoration:none;">
                Voir mes messages
            </a>
        </div>

        <!-- Footer -->
        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">
                © {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers
            </p>
        </div>
    </div>
</body>
</html>
