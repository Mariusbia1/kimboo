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
            <div style="width:56px; height:56px; background:#F0FFF4; border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:24px;">
                <span style="font-size:28px;">✅</span>
            </div>

            <h2 style="margin:0 0 8px; font-size:22px; font-weight:800; color:#000;">
                Votre cours a été approuvé !
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $course->teacherProfile->user->name }}</strong>,<br>
                Bonne nouvelle ! Votre cours a été validé par notre équipe et est maintenant visible par les élèves.
            </p>

            <!-- Card cours -->
            <div style="background:#F9FAFB; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #FCB315;">
                <p style="margin:0 0 6px; font-size:13px; color:#999; text-transform:uppercase; font-weight:600;">Cours approuvé</p>
                <p style="margin:0 0 4px; font-size:17px; font-weight:700; color:#000;">{{ $course->title }}</p>
                <p style="margin:0; font-size:14px; color:#666;">{{ $course->category }} · {{ $course->level }}</p>
                <p style="margin:8px 0 0; font-size:15px; font-weight:700; color:#FCB315;">{{ number_format($course->price_per_hour, 0, ',', ' ') }} FCFA / H</p>
            </div>

            <a href="{{ url('/professeur/dashboard') }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 28px; border-radius:12px; text-decoration:none;">
                Voir mon tableau de bord
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
