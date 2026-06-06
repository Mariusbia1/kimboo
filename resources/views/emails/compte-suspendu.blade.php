<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:'Helvetica Neue',Arial,sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <div style="background:#FCB315; padding:32px; text-align:center;">
            <h1 style="margin:0; font-size:28px; font-weight:900; color:#000; letter-spacing:-1px;">kimboo</h1>
        </div>

        <div style="padding:40px 32px;">
            <h2 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#000;">
                Accès messagerie suspendu 24h
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $user->name }}</strong>,<br>
                Suite à plusieurs violations des règles de notre plateforme, votre accès à la messagerie a été suspendu pour <strong>24 heures</strong>.
            </p>

            <div style="background:#FEF2F2; border-radius:12px; padding:20px; margin-bottom:20px; border-left:4px solid #EF4444;">
                <p style="margin:0 0 8px; font-size:14px; font-weight:700; color:#EF4444;">Raison de la suspension :</p>
                <p style="margin:0; font-size:14px; color:#666; line-height:1.6;">
                    Tentatives répétées d'envoi de contenus non autorisés (numéros de téléphone ou coordonnées bancaires) via la messagerie Kimboo.
                </p>
            </div>

            <div style="background:#FFF8E7; border-radius:12px; padding:16px 20px; margin-bottom:28px;">
                <p style="margin:0; font-size:14px; color:#555; line-height:1.6;">
                    Les échanges de coordonnées personnelles en dehors de la plateforme sont interdits pour protéger tous les utilisateurs. En cas de récidive, votre compte pourra être définitivement suspendu.
                </p>
            </div>

            <p style="margin:0 0 24px; font-size:14px; color:#555;">
                Si vous pensez que c'est une erreur, contactez notre support.
            </p>

            <a href="{{ url('/messages') }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 28px; border-radius:12px; text-decoration:none;">
                Retour à la plateforme
            </a>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
