<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:'Helvetica Neue',Arial,sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <div style="background:#FCB315; padding:32px; text-align:center;">
            <h1 style="margin:0; font-size:28px; font-weight:900; color:#000; letter-spacing:-1px;">kimboo</h1>
        </div>

        <div style="padding:40px 32px;">
            <div style="display:inline-block; background:#ECFDF5; border-radius:50%; padding:12px; margin-bottom:16px;">
                <span style="font-size:24px;">✓</span>
            </div>
            <h2 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#059669;">
                Votre compte Kimboo est de nouveau actif !
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $user->name }}</strong>,<br>
                La suspension de votre compte a été levée par l'administration. Vous pouvez dès maintenant vous reconnecter et accéder à l'ensemble de vos services.
            </p>

            <div style="background:#ECFDF5; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #10B981;">
                <p style="margin:0; font-size:14px; color:#065F46; line-height:1.6;">
                    Tous vos cours, réservations et messages sont à nouveau disponibles.
                </p>
            </div>

            <a href="{{ url('/login') }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 28px; border-radius:12px; text-decoration:none;">
                Se connecter à mon compte
            </a>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
