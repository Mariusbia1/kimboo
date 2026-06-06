<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageAlert;
use App\Models\Notification;
use App\Models\User;
use App\Services\MessageModerator;
use Illuminate\Http\Request;
use App\Mail\MessageSuspect;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($user) {
                return $message->sender_id === $user->id
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->map(fn($messages) => $messages->first());

        return view('messages.index', compact('conversations'));
    }

    public function show($userId)
    {
        $user    = auth()->user();
        $contact = User::findOrFail($userId);

        $messages = Message::where(function($q) use ($user, $userId) {
            $q->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function($q) use ($user, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $user->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        Message::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'contact'));
    }

    public function send(Request $request, $userId)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $user    = auth()->user();
        $content = $request->content;

        // Vérifier si l'utilisateur est bloqué pour 24h
        if ($user->message_blocked_until && now()->lt($user->message_blocked_until)) {
            $remaining = now()->diffInMinutes($user->message_blocked_until);
            $heures    = floor($remaining / 60);
            $minutes   = $remaining % 60;

            return back()->withInput()->with('error',
                "Votre accès à la messagerie est suspendu pour " .
                ($heures > 0 ? "{$heures}h " : '') . "{$minutes}min suite à plusieurs violations des règles."
            );
        }

        // Réinitialiser le blocage si expiré
        if ($user->message_blocked_until && now()->gte($user->message_blocked_until)) {
            $user->update(['message_attempts' => 0, 'message_blocked_until' => null]);
        }

        $detected       = MessageModerator::analyze($content);
        $blockedTypes   = ['phone', 'bank'];
        $warningTypes   = ['link'];
        $blockedMatches = array_filter($detected, fn($d) => in_array($d['type'], $blockedTypes));
        $warningMatches = array_filter($detected, fn($d) => in_array($d['type'], $warningTypes));

        // Contenu interdit (téléphone ou banque)
        if (!empty($blockedMatches)) {
            $typeLabels = ['phone' => 'numéro de téléphone', 'bank' => 'coordonnées bancaires'];
            $types      = array_unique(array_map(fn($d) => $typeLabels[$d['type']] ?? $d['type'], $blockedMatches));
            $label      = implode(' et ', $types);

            // Incrémenter les tentatives
            $attempts = $user->message_attempts + 1;
            $user->update(['message_attempts' => $attempts]);

            // Enregistrer le message bloqué pour l'admin
            $message = Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => $userId,
                'content'     => $content,
                'is_read'     => false,
                'is_blocked'  => true,
            ]);

            // Créer l'alerte
            $alertCreee = null;
            foreach ($blockedMatches as $match) {
                $alertCreee = MessageAlert::create([
                    'message_id'      => $message->id,
                    'sender_id'       => $user->id,
                    'receiver_id'     => $userId,
                    'alert_type'      => $match['type'],
                    'matched_content' => $match['matched'],
                    'status'          => 'pending',
                ]);
            }

            // Envoi mail — un seul mail suffit
            if ($alertCreee) {
                Mail::to($user->email)->send(new MessageSuspect($alertCreee->load('sender')));
            }



            // 3ème tentative → blocage 24h
            if ($attempts >= 3) {
                $blockedUntil = now()->addHours(24);
                $user->update(['message_blocked_until' => $blockedUntil]);

                // Mail compte suspendu
                Mail::to($user->email)->send(new \App\Mail\CompteSuspendu($user));
                
                // Notifier les admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::notifier(
                        userId: $admin->id,
                        type:   'message_alert',
                        title:  'Compte suspendu — messagerie',
                        body:   $user->name . ' a été suspendu 24h de la messagerie après 3 tentatives d\'envoi de ' . $label . '.',
                        link:   '/admin/messages/alertes'
                    );
                }

                // Notifier l'utilisateur
                Notification::notifier(
                    userId: $user->id,
                    type:   'message_alert',
                    title:  'Messagerie suspendue 24h',
                    body:   'Votre accès à la messagerie a été suspendu 24h suite à 3 tentatives d\'envoi de ' . $label . '.',
                    link:   '#'
                );

                return back()->withInput()->with('error',
                    "Votre accès à la messagerie est suspendu pour 24h suite à 3 violations des règles de la plateforme."
                );
            }

            // 1ère ou 2ème tentative → avertissement
            $restantes = 3 - $attempts;

            // Notifier les admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::notifier(
                    userId: $admin->id,
                    type:   'message_alert',
                    title:  'Message suspect — tentative ' . $attempts,
                    body:   $user->name . ' a tenté d\'envoyer un ' . $label . '. (' . $attempts . '/3 tentatives)',
                    link:   '/admin/messages/alertes'
                );
            }

            return back()->withInput()->with('error',
                "⚠️ Message non envoyé : votre message contient un {$label} non autorisé sur Kimboo. " .
                "Il vous reste {$restantes} tentative(s) avant une suspension de 24h."
            );
        }

        // Lien externe → avertissement mais message envoyé
        if (!empty($warningMatches)) {
            $message = Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => $userId,
                'content'     => $content,
                'is_read'     => false,
                'is_blocked'  => false,
            ]);

            $alertCreee = null;
            foreach ($warningMatches as $match) {
                $alertCreee = MessageAlert::create([
                    'message_id'      => $message->id,
                    'sender_id'       => $user->id,
                    'receiver_id'     => $userId,
                    'alert_type'      => 'link',
                    'matched_content' => $match['matched'],
                    'status'          => 'pending',
                ]);
            }

            // Envoi mail — un seul mail suffit
            if ($alertCreee) {
                Mail::to($user->email)->send(new MessageSuspect($alertCreee->load('sender')));
            }

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::notifier(
                    userId: $admin->id,
                    type:   'message_alert',
                    title:  'Lien externe détecté',
                    body:   $user->name . ' a envoyé un lien externe. À vérifier.',
                    link:   '/admin/messages/alertes'
                );
            }

            return redirect()->route('messages.show', $userId)
                ->with('warning', 'Votre message a été envoyé mais contient un lien externe signalé à l\'équipe Kimboo.');
        }

        // Message normal
        Message::create([
            'sender_id'   => $user->id,
            'receiver_id' => $userId,
            'content'     => $content,
            'is_read'     => false,
            'is_blocked'  => false,
        ]);

        return redirect()->route('messages.show', $userId);
    }
}
