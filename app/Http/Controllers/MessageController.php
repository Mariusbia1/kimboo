<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\MessageAlert;
use App\Models\Notification;
use App\Models\User;
use App\Services\MessageModerator;
use Illuminate\Http\Request;

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
            ->map(function($messages) {
                return $messages->first();
            });

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

        // Marquer les messages comme lus
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

        $content  = $request->content;
        $detected = MessageModerator::analyze($content);

        // Séparer les types détectés
        $blockedTypes  = ['phone', 'bank'];
        $warningTypes  = ['link'];

        $blockedMatches = array_filter($detected, fn($d) => in_array($d['type'], $blockedTypes));
        $warningMatches = array_filter($detected, fn($d) => in_array($d['type'], $warningTypes));

        // Bloquer si téléphone ou banque détecté
        if (!empty($blockedMatches)) {
            $typeLabels = [
                'phone' => 'numéro de téléphone',
                'bank'  => 'coordonnées bancaires',
            ];

            $types = array_unique(array_map(fn($d) => $typeLabels[$d['type']] ?? $d['type'], $blockedMatches));
            $label = implode(' et ', $types);

            // Enregistrer le message quand même (masqué) pour que l'admin puisse le voir
            $message = Message::create([
                'sender_id'   => auth()->id(),
                'receiver_id' => $userId,
                'content'     => $content,
                'is_read'     => false,
                'is_blocked'  => true,
            ]);

            // Créer une alerte
            foreach ($blockedMatches as $match) {
                MessageAlert::create([
                    'message_id'      => $message->id,
                    'sender_id'       => auth()->id(),
                    'receiver_id'     => $userId,
                    'alert_type'      => $match['type'],
                    'matched_content' => $match['matched'],
                    'status'          => 'pending',
                ]);
            }

            // Notifier les admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::notifier(
                    userId: $admin->id,
                    type:   'message_alert',
                    title:  'Message suspect détecté',
                    body:   auth()->user()->name . ' a tenté d\'envoyer un ' . $label . ' dans un message.',
                    link:   '/admin/messages/alertes'
                );
            }

            // Notifier l'expéditeur
            Notification::notifier(
                userId: auth()->id(),
                type:   'message_alert',
                title:  'Message non envoyé',
                body:   'Votre message contient un ' . $label . ' non autorisé sur Kimboo. Échangez via la plateforme uniquement.',
                link:   route('messages.show', $userId)
            );

            return back()
                ->withInput()
                ->with('error', 'Votre message contient un ' . $label . ' non autorisé. Veuillez utiliser uniquement la plateforme Kimboo pour vos échanges.');
        }

        // Avertissement si lien détecté (message envoyé mais signalé)
        if (!empty($warningMatches)) {
            $message = Message::create([
                'sender_id'   => auth()->id(),
                'receiver_id' => $userId,
                'content'     => $content,
                'is_read'     => false,
                'is_blocked'  => false,
            ]);

            foreach ($warningMatches as $match) {
                MessageAlert::create([
                    'message_id'      => $message->id,
                    'sender_id'       => auth()->id(),
                    'receiver_id'     => $userId,
                    'alert_type'      => 'link',
                    'matched_content' => $match['matched'],
                    'status'          => 'pending',
                ]);
            }

            // Notifier les admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::notifier(
                    userId: $admin->id,
                    type:   'message_alert',
                    title:  'Lien externe détecté',
                    body:   auth()->user()->name . ' a envoyé un lien externe dans un message. À vérifier.',
                    link:   '/admin/messages/alertes'
                );
            }

            return redirect()->route('messages.show', $userId)
                ->with('warning', 'Votre message a été envoyé mais contient un lien externe qui a été signalé à l\'équipe Kimboo.');
        }

        // Message normal
        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $userId,
            'content'     => $content,
            'is_read'     => false,
            'is_blocked'  => false,
        ]);

        return redirect()->route('messages.show', $userId);
    }
}
