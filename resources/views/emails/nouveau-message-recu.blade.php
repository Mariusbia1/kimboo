<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
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
                Bonjour <strong>{{ $receiver?->name ?? $chatMessage->receiver?->name ?? 'Membre Kimboo' }}</strong>,<br>
                Vous venez de recevoir un nouveau message de <strong>{{ $sender?->name ?? $chatMessage->sender?->name ?? 'un utilisateur' }}</strong> sur Kimboo.
            </p>

            <div style="background:#F9FAFB; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #FCB315;">
                <p style="margin:0 0 8px; font-size:12px; color:#888; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Extrait du message</p>
                <div style="font-size:15px; color:#222; line-height:1.6; font-style:italic;">
                    @php
                        $msgContent = trim($chatMessage->content ?? '');
                    @endphp
                    @if(!empty($msgContent))
                        « {!! nl2br(e(\Illuminate\Support\Str::limit($msgContent, 300))) !!} »
                    @elseif($chatMessage->hasAttachment())
                        <span style="font-style:normal; color:#555;">
                            📎 <em>Pièce jointe envoyée : {{ $chatMessage->attachment_name ?? 'Fichier joint' }}</em>
                        </span>
                    @else
                        <span style="font-style:normal; color:#777;">
                            <em>[Nouveau message]</em>
                        </span>
                    @endif
                </div>

                @if(!empty($msgContent) && $chatMessage->hasAttachment())
                    <p style="margin:12px 0 0; font-size:13px; color:#666; border-top:1px dashed #E5E7EB; padding-top:8px;">
                        📎 <strong>Pièce jointe :</strong> {{ $chatMessage->attachment_name ?? 'Fichier joint' }}
                    </p>
                @endif
            </div>

            <div style="text-align:center; margin:32px 0 8px;">
                <a href="{{ url('/messages/' . ($sender?->id ?? $chatMessage->sender_id)) }}"
                   style="display:inline-block; background:#FCB315; color:#000; font-weight:800; font-size:15px; padding:14px 36px; border-radius:9999px; text-decoration:none; box-shadow:0 4px 12px rgba(252,179,21,0.35);">
                    Répondre au message
                </a>
            </div>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
