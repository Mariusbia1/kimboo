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
                Demande de réservation enregistrée !
            </h2>
            <p style="margin:0 0 24px; font-size:15px; color:#555; line-height:1.6;">
                Bonjour <strong>{{ $booking->user->name }}</strong>,<br>
                Votre demande de cours a bien été transmise à <strong>{{ $booking->course->teacherProfile->user->name }}</strong>. Voici le récapitulatif de votre réservation :
            </p>

            <div style="background:#F9FAFB; border-radius:12px; padding:20px; margin-bottom:28px; border-left:4px solid #FCB315;">
                <p style="margin:0 0 6px; font-size:13px; color:#999; text-transform:uppercase; font-weight:600;">Récapitulatif de la commande</p>
                <p style="margin:0 0 8px; font-size:17px; font-weight:700; color:#000;">{{ $booking->course->title }}</p>
                <p style="margin:0 0 4px; font-size:14px; color:#666;">
                    Professeur : <strong>{{ $booking->course->teacherProfile->user->name }}</strong>
                </p>
                <p style="margin:0 0 4px; font-size:14px; color:#666;">
                    Date prévue : <strong>{{ \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y à H:i') }}</strong>
                </p>
                <p style="margin:0 0 4px; font-size:14px; color:#666;">
                    Durée du cours : <strong>{{ $booking->duration_hours }} heure(s)</strong>
                </p>
                <p style="margin:0 0 4px; font-size:14px; color:#666;">
                    Format : <strong>{{ $booking->course->formatted_format ?? 'En ligne & Présentiel' }}</strong>
                </p>
                <p style="margin:0 0 4px; font-size:14px; color:#666;">
                    Statut : <span style="display:inline-block; padding:3px 10px; border-radius:9999px; background:#FEF3C7; color:#B45309; font-weight:600; font-size:12px;">En attente de confirmation</span>
                </p>
                <p style="margin:12px 0 0; font-size:16px; font-weight:800; color:#FCB315;">
                    Montant total : {{ number_format($booking->total_price, 0, ',', ' ') }} FCFA
                </p>
            </div>

            <p style="margin:0 0 24px; font-size:14px; color:#666; line-height:1.5;">
                Le professeur a été notifié et examinera votre demande dans les plus brefs délais. Vous recevrez une notification dès validation.
            </p>

            <a href="{{ url('/eleve/reservations') }}"
               style="display:inline-block; background:#FCB315; color:#000; font-weight:700; font-size:15px; padding:14px 32px; border-radius:9999px; text-decoration:none;">
                Voir mes réservations
            </a>
        </div>

        <div style="background:#F9FAFB; padding:24px 32px; text-align:center; border-top:1px solid #F0F0F0;">
            <p style="margin:0; font-size:13px; color:#999;">© {{ date('Y') }} Kimboo · Plateforme ivoirienne de cours particuliers</p>
        </div>
    </div>
</body>
</html>
