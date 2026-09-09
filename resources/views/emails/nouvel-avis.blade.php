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
                Vous avez reçu un nouvel avis !
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour, un élève vient de laisser un avis sur votre profil.
            </p>

            <!-- Card avis -->
            <div style="background:#F9FAFB; border-radius:12px; padding:20px; margin-bottom:28px;">
                <!-- Étoiles -->
                <div style="margin-bottom:12px;">
                    @for($i = 1; $i <= 5; $i++)
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="{{ $i <= $review->rating ? '#FCB315' : '#E5E7EB' }}" style="display:inline-block; vertical-align:middle;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    <span style="font-size:15px; font-weight:700; color:#000; margin-left:6px;">{{ $review->rating }}/5</span>
                </div>

                @if($review->comment)
                <p style="margin:0 0 12px; font-size:15px; color:#333; line-height:1.6; font-style:italic;">
                    "{{ $review->comment }}"
                </p>
                @endif

                <p style="margin:0; font-size:13px; color:#999;">
                    Par <strong style="color:#000;">{{ $review->user->name }}</strong> ·
                    {{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}
                </p>
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
