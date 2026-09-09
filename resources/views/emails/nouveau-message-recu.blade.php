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
                Nouveau message reçu !
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $message->receiver?->name }}</strong>,<br>
                Vous venez de recevoir un nouveau message de <strong>{{ $message->sender?->name }}</strong> sur Kimboo.
            </p>

            <div style="background:#F9FAFB; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #FCB315;">
                <p style="margin:0 0 6px; font-size:13px; color:#999; text-transform:uppercase; font-weight:600;">Extrait du message</p>
                <p style="margin:0; font-size:15px; color:#333; line-height:1.6; font-style:italic;">
                    « {{ \Illuminate\Support\Str::limit($message->content, 250) }} »
                </p>
            </div>

            <a href="{{ url('/messages/' . $message->sender_id) }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 32px; border-radius:9999px; text-decoration:none;">
                Répondre au message
            </a>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
