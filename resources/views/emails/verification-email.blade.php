<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre adresse email · Kimboo</title>
</head>
<body style="margin:0; padding:0; background:#f5f5f5; font-family:'Helvetica Neue',Arial,sans-serif;">
    <div style="max-width:600px; margin:40px auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

        <!-- Header -->
        <div style="background:#FCB315; padding:32px; text-align:center;">
            <h1 style="margin:0; font-size:28px; font-weight:900; color:#000000; letter-spacing:-1px;">kimboo</h1>
        </div>

        <!-- Body -->
        <div style="padding:40px 32px;">
            <div style="width:56px; height:56px; background:#FEF3C7; border-radius:14px; text-align:center; line-height:56px; margin-bottom:24px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle; display:inline-block;">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>

            <h2 style="margin:0 0 12px; font-size:22px; font-weight:800; color:#000000;">
                Vérifiez votre adresse email
            </h2>

            <p style="margin:0 0 20px; font-size:15px; color:#555555; line-height:1.6;">
                Bonjour <strong>{{ $user->name }}</strong>,<br>
                Merci de vous être inscrit sur Kimboo ! Pour finaliser la création de votre compte et accéder à toutes nos fonctionnalités, veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous.
            </p>

            <div style="margin:28px 0; text-align:center;">
                <a href="{{ $verificationUrl }}"
                   style="display:inline-block; background:#FCB315; color:#000000; font-weight:700; font-size:15px; padding:14px 32px; border-radius:9999px; text-decoration:none;">
                    Vérifier mon adresse email
                </a>
            </div>

            <div style="background:#F9FAFB; border-radius:12px; padding:18px 20px; margin-bottom:24px; border-left:4px solid #FCB315;">
                <p style="margin:0 0 6px; font-size:13px; font-weight:700; color:#333333;">Informations utiles</p>
                <p style="margin:0 0 6px; font-size:13px; color:#666666; line-height:1.5;">
                    Ce lien de vérification expirera dans <strong>{{ $expireMinutes }} minutes</strong>.
                </p>
                <p style="margin:0; font-size:13px; color:#666666; line-height:1.5;">
                    Si vous n'avez pas créé de compte sur Kimboo, vous pouvez ignorer cet email en toute sécurité.
                </p>
            </div>

            <p style="margin:0; font-size:12px; color:#999999; line-height:1.5; word-break:break-all;">
                Si vous ne parvenez pas à cliquer sur le bouton ci-dessus, copiez et collez l'URL suivante dans votre navigateur web :<br>
                <a href="{{ $verificationUrl }}" style="color:#D97706; text-decoration:underline;">{{ $verificationUrl }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999999;">
                © {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers
            </p>
        </div>
    </div>
</body>
</html>
