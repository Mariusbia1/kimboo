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
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

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

        // Récupérer le compte Assistance Kimboo
        $adminUser = User::where('role', 'admin')->first();

        // Récupérer le lien WhatsApp configuré
        $rawWhatsapp = \App\Models\SiteSetting::get('whatsapp');
        $rawPhone    = \App\Models\SiteSetting::get('contact_whatsapp') ?: \App\Models\SiteSetting::get('contact_phone');
        $whatsappLink = $this->buildWhatsappLink($rawWhatsapp, $rawPhone);

        return view('messages.index', compact('conversations', 'adminUser', 'whatsappLink'));
    }

    public function assistance()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::firstOrCreate(
                ['email' => 'contact@kimboo.net'],
                [
                    'name'     => 'Assistance Kimboo',
                    'password' => bcrypt('AssistanceKimboo' . rand(1000, 9999)),
                    'role'     => 'admin',
                    'ville'    => 'Abidjan',
                    'phone'    => '0700000000',
                ]
            );
        }

        if ($user->id === $admin->id) {
            return redirect()->route('admin.messages.conversations');
        }

        return redirect()->route('messages.show', $admin->id);
    }

    public function show($userId)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

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

        // Vérifier si l'assistance n'a pas répondu depuis plus de 24h
        $isAssistance = ($contact->role === 'admin');
        $unansweredAfter24h = false;

        if ($isAssistance) {
            $lastUserMessage = $messages->where('sender_id', $user->id)->last();
            $lastAssistanceMessage = $messages->where('sender_id', $contact->id)->last();

            if ($lastUserMessage) {
                $userMsgTime = \Carbon\Carbon::parse($lastUserMessage->created_at);
                $hasReplied = $lastAssistanceMessage && \Carbon\Carbon::parse($lastAssistanceMessage->created_at)->gt($userMsgTime);
                if (!$hasReplied && $userMsgTime->lte(now()->subHours(24))) {
                    $unansweredAfter24h = true;
                }
            }
        }

        // Récupérer le lien WhatsApp officiel
        $rawWhatsapp = \App\Models\SiteSetting::get('whatsapp');
        $rawPhone    = \App\Models\SiteSetting::get('contact_whatsapp') ?: \App\Models\SiteSetting::get('contact_phone');
        $whatsappLink = $this->buildWhatsappLink($rawWhatsapp, $rawPhone);

        return view('messages.show', compact('messages', 'contact', 'whatsappLink', 'unansweredAfter24h', 'isAssistance'));
    }

    private function buildWhatsappLink(?string $rawWhatsapp, ?string $rawPhone): string
    {
        if (!empty($rawWhatsapp) && (str_starts_with($rawWhatsapp, 'http://') || str_starts_with($rawWhatsapp, 'https://'))) {
            return $rawWhatsapp;
        }

        $phone = !empty($rawPhone) ? $rawPhone : '+225 07 00 00 00 00';
        $digits = preg_replace('/\D+/', '', $phone);
        if (strlen($digits) >= 8) {
            if (!str_starts_with($digits, '225') && strlen($digits) === 10) {
                $digits = '225' . $digits;
            }
            return 'https://wa.me/' . $digits . '?text=' . urlencode('Bonjour Assistance Kimboo, j\'ai besoin d\'une assistance rapide concernant la plateforme Kimboo.');
        }

        return 'https://wa.me/2250700000000?text=' . urlencode('Bonjour Assistance Kimboo, j\'ai besoin d\'une assistance rapide concernant la plateforme Kimboo.');
    }

    public function send(Request $request, $userId)
    {
        $request->validate([
            'content'    => ['nullable', 'string', 'max:5000', 'required_without:attachment'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,gif,txt,zip'],
        ], [
            'content.required_without' => 'Veuillez saisir un message ou sélectionner un fichier à envoyer.',
            'attachment.max'           => 'Le fichier joint ne doit pas dépasser 10 Mo.',
            'attachment.mimes'         => 'Format de fichier non supporté. Formats acceptés : PDF, Word, Excel, images, texte, zip.',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $content = $request->input('content');

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

        // Traitement de la pièce jointe (sauvegarde en base de données et dans storage)
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentType = null;
        $attachmentSize = null;

        if ($request->hasFile('attachment')) {
            $file           = $request->file('attachment');
            $attachmentPath = $file->store('messages/attachments', 'public');
            $attachmentName = $file->getClientOriginalName();
            $attachmentType = $file->getClientMimeType();
            $attachmentSize = $file->getSize();
        }

        $detected       = !empty($content) ? MessageModerator::analyze($content) : [];
        $blockedTypes   = ['bank'];
        $warningTypes   = ['link'];
        $blockedMatches = array_filter($detected, fn($d) => in_array($d['type'], $blockedTypes));
        $warningMatches = array_filter($detected, fn($d) => in_array($d['type'], $warningTypes));

        // Contenu strictement interdit (coordonnées ou cartes bancaires)
        if (!empty($blockedMatches)) {
            $typeLabels = ['bank' => 'coordonnées bancaires ou carte de paiement'];
            $types      = array_unique(array_map(fn($d) => $typeLabels[$d['type']] ?? $d['type'], $blockedMatches));
            $label      = implode(' et ', $types);

            // Incrémenter les tentatives
            $attempts = $user->message_attempts + 1;
            $user->update(['message_attempts' => $attempts]);

            // Enregistrer le message bloqué pour l'admin
            $message = Message::create([
                'sender_id'       => $user->id,
                'receiver_id'     => $userId,
                'content'         => $content,
                'attachment'      => $attachmentPath,
                'attachment_name' => $attachmentName,
                'attachment_type' => $attachmentType,
                'attachment_size' => $attachmentSize,
                'is_read'         => false,
                'is_blocked'      => true,
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
                try {
                    Mail::to($user->email)->send(new MessageSuspect($alertCreee->load('sender')));
                } catch (\Throwable $e) {
                    Log::warning('Échec envoi mail message suspect', ['error' => $e->getMessage()]);
                }
            }

            // 3ème tentative → blocage 24h
            if ($attempts >= 3) {
                $blockedUntil = now()->addHours(24);
                $user->update(['message_blocked_until' => $blockedUntil]);

                // Mail compte suspendu
                try {
                    Mail::to($user->email)->send(new \App\Mail\CompteSuspendu($user));
                } catch (\Throwable $e) {
                    Log::warning('Échec envoi mail compte suspendu', ['error' => $e->getMessage()]);
                }
                
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
                "Message non envoyé : Le partage de {$label} est strictement interdit sur Kimboo pour des raisons de sécurité. " .
                "Il vous reste {$restantes} tentative(s) avant une suspension de 24h."
            );
        }

        // Lien externe → avertissement mais message envoyé
        if (!empty($warningMatches)) {
            $message = Message::create([
                'sender_id'       => $user->id,
                'receiver_id'     => $userId,
                'content'         => $content,
                'attachment'      => $attachmentPath,
                'attachment_name' => $attachmentName,
                'attachment_type' => $attachmentType,
                'attachment_size' => $attachmentSize,
                'is_read'         => false,
                'is_blocked'      => false,
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
                try {
                    Mail::to($user->email)->send(new MessageSuspect($alertCreee->load('sender')));
                } catch (\Throwable $e) {
                    Log::warning('Échec envoi mail message suspect', ['error' => $e->getMessage()]);
                }
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

            // Envoi email & notification au destinataire
            try {
                $message->load(['sender', 'receiver']);
                if ($message->receiver && $message->receiver->email) {
                    Mail::to($message->receiver->email)->send(new \App\Mail\NouveauMessageRecu($message));
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Échec envoi mail nouveau message', ['exception' => $e]);
            }

            Notification::notifier(
                userId: $userId,
                type:   'new_message',
                title:  'Nouveau message de ' . $user->name,
                body:   \Illuminate\Support\Str::limit($content ?? 'Pièce jointe envoyée', 80),
                link:   route('messages.show', $user->id)
            );

            return redirect()->route('messages.show', $userId)
                ->with('warning', 'Votre message a été envoyé mais contient un lien externe signalé à l\'équipe Kimboo.');
        }

        // Message normal (avec ou sans pièce jointe)
        $message = Message::create([
            'sender_id'       => $user->id,
            'receiver_id'     => $userId,
            'content'         => $content,
            'attachment'      => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_type' => $attachmentType,
            'attachment_size' => $attachmentSize,
            'is_read'         => false,
            'is_blocked'      => false,
        ]);

        // Envoi email & notification au destinataire
        try {
            $message->load(['sender', 'receiver']);
            if ($message->receiver && $message->receiver->email) {
                Mail::to($message->receiver->email)->send(new \App\Mail\NouveauMessageRecu($message));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Échec envoi mail nouveau message', ['exception' => $e]);
        }

        Notification::notifier(
            userId: $userId,
            type:   'new_message',
            title:  'Nouveau message de ' . $user->name,
            body:   \Illuminate\Support\Str::limit($content ?? 'Nouvelle pièce jointe reçue', 80),
            link:   route('messages.show', $user->id)
        );

        return redirect()->route('messages.show', $userId);
    }
}
