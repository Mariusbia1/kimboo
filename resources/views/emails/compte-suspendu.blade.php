<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:'Helvetica Neue',Arial,sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <div style="background:#FCB315; padding:32px; text-align:center;">
            <h1 style="margin:0; font-size:28px; font-weight:900; color:#000; letter-spacing:-1px;">kimboo</h1>
        </div>

        <div style="padding:40px 32px;">
            <h2 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#EF4444;">
                Votre compte Kimboo a été suspendu
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $user->name }}</strong>,<br>
                Nous vous informons que votre compte utilisateur sur la plateforme Kimboo a été temporairement ou définitivement suspendu par l'administration.
            </p>

            <div style="background:#FEF2F2; border-radius:12px; padding:20px; margin-bottom:20px; border-left:4px solid #EF4444;">
                <p style="margin:0 0 8px; font-size:14px; font-weight:700; color:#EF4444;">Motif de la suspension :</p>
                <p style="margin:0; font-size:14px; color:#666; line-height:1.6;">
                    {{ $reason ?? $user->suspension_reason ?? 'Non-respect des conditions générales d\'utilisation et règles de sécurité de la plateforme Kimboo.' }}
                </p>
            </div>

            <div style="background:#FFF8E7; border-radius:12px; padding:16px 20px; margin-bottom:28px;">
                <p style="margin:0; font-size:14px; color:#555; line-height:1.6;">
                    Pendant toute la durée de la suspension, l'accès à votre espace utilisateur, la réservation de cours et l'envoi de messages sont désactivés.
                </p>
            </div>

            <p style="margin:0 0 24px; font-size:14px; color:#555; line-height:1.6;">
                Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez demander la réactivation de votre compte, contactez notre équipe d'assistance à <a href="mailto:support@kimboo.net" style="color:#D97706; font-weight:bold;">support@kimboo.net</a>.
            </p>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
