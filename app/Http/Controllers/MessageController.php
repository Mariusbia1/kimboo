<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Récupérer toutes les conversations
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
        $user = auth()->user();
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

    $content = $request->content;

    // Patterns à bloquer
    $patterns = [
        // Numéros de téléphone
        '/(\+?\d[\s\-\.]?){7,15}/',
        // Numéros de carte bancaire
        '/\b\d{4}[\s\-]?\d{4}[\s\-]?\d{4}[\s\-]?\d{4}\b/',
        // IBAN
        '/[A-Z]{2}\d{2}[A-Z0-9]{4}\d{7}([A-Z0-9]?){0,16}/',
        // QR code mentions
        '/qr\s*code/i',
        '/scanner/i',
        // Liens suspects
        '/(?:https?:\/\/)?(?:wa\.me|whatsapp|telegram|t\.me)/i',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $content)) {
            return back()
                ->withInput()
                ->with('error', 'Votre message contient des informations non autorisées (numéro de téléphone, données bancaires, QR code). Veuillez utiliser la plateforme Kimboo pour vos échanges.');
        }
    }

    Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $userId,
        'content' => $content,
        'is_read' => false,
    ]);

    return redirect()->route('messages.show', $userId);
}
}
