<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Notification;
use App\Models\MessageAlert;
use App\Models\Message;
use App\Models\SiteSetting;
use App\Mail\CoursApprouve;
use App\Mail\CoursRefuse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function editProfil()
    {
        $user = auth()->user();
        return view('admin.profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'L\'adresse email n\'est pas valide.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.min'       => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil administrateur mis à jour avec succès.');
    }
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProfesseurs = User::where('role', 'professeur')->count();
        $totalEleves = User::where('role', 'eleve')->count();
        $totalReservations = Booking::count();
        $totalCours = Course::count();
        $professeurs = TeacherProfile::with(['user', 'courses'])->latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProfesseurs', 'totalEleves',
            'totalReservations', 'totalCours', 'professeurs'
        ));
    }

    public function users(Request $request)
    {
        $status = $request->query('status', 'all');
        $role   = $request->query('role', 'all');
        $search = $request->query('q', '');

        $query = User::with(['teacherProfile.courses', 'bookings'])
            ->withCount(['bookings', 'sentMessages']);

        if ($status === 'suspended') {
            $query->where('is_suspended', true);
        } elseif ($status === 'active') {
            $query->where('is_suspended', false);
        }

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%");
            });
        }

        $totalUsersCount     = User::count();
        $totalActiveCount    = User::where('is_suspended', false)->count();
        $totalSuspendedCount = User::where('is_suspended', true)->count();

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users', compact(
            'users', 'status', 'role', 'search',
            'totalUsersCount', 'totalActiveCount', 'totalSuspendedCount'
        ));
    }

    public function certifierProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_verified' => true]);
        return back()->with('success', 'Professeur certifié avec succès !');
    }

    public function decertifierProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_verified' => false]);
        return back()->with('success', 'Certification retirée.');
    }

    public function featureProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_featured' => true]);
        return back()->with('success', 'Professeur mis en avant sur la page d\'accueil !');
    }

    public function unfeatureProfesseur($id)
    {
        $profile = TeacherProfile::findOrFail($id);
        $profile->update(['is_featured' => false]);
        return back()->with('success', 'Mise en avant retirée.');
    }

    public function suspendUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Impossible de suspendre un compte administrateur.');
        }

        $reason = $request->input('reason', $request->input('suspension_reason'));
        $user->suspend($reason);

        // Envoi email de notification
        try {
            if ($user->email) {
                Mail::to($user->email)->send(new \App\Mail\CompteSuspendu($user, $reason));
            }
        } catch (\Throwable $e) {
            Log::warning('Échec envoi mail suspension utilisateur', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', "Le compte de {$user->name} a été suspendu.");
    }

    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->reactivate();

        // Envoi email de notification
        try {
            if ($user->email) {
                Mail::to($user->email)->send(new \App\Mail\CompteReactive($user));
            }
        } catch (\Throwable $e) {
            Log::warning('Échec envoi mail réactivation utilisateur', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        return back()->with('success', "La suspension du compte de {$user->name} a été levée. Le compte est de nouveau actif.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

public function cours()
{
    $coursPending = Course::with('teacherProfile.user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    $coursApproved = Course::with('teacherProfile.user')
        ->where('status', 'approved')
        ->latest()
        ->get();

    $coursRejected = Course::with('teacherProfile.user')
        ->where('status', 'rejected')
        ->latest()
        ->get();

    return view('admin.cours', compact('coursPending', 'coursApproved', 'coursRejected'));
}

public function showCours($id)
{
    $cours = Course::with('teacherProfile.user')->findOrFail($id);
    return view('admin.cours-show', compact('cours'));
}

public function approuverCours($id)
{
    $cours = Course::with('teacherProfile.user')->findOrFail($id);

    if ($cours->status === 'approved') {
        return redirect()->route('admin.cours')
            ->with('info', 'Ce cours est déjà approuvé.')
            ->with('active_tab', 'approved');
    }

    $cours->update([
        'status'           => 'approved',
        'is_active'        => true,
        'rejection_reason' => null,
    ]);

    Notification::notifier(
        userId: $cours->teacherProfile->user->id,
        type:   'course_approved',
        title:  'Cours validé',
        body:   'Votre cours "' . $cours->title . '" a été approuvé et est maintenant visible par les élèves.',
        link:   route('professeur.dashboard')
    );

    // Envoi mail si l'utilisateur n'est pas connecté
    $user = $cours->teacherProfile->user;
    try {
        Mail::to($user->email)->send(new CoursApprouve($cours));
    } catch (\Throwable $e) {
        Log::error('Échec de l\'envoi du mail de cours approuvé', [
            'exception' => $e,
            'course_id' => $cours->id,
            'user_id' => $user->id,
        ]);
    }

    return redirect()->route('admin.cours')
        ->with('success', 'Cours approuvé et publié avec succès.')
        ->with('active_tab', 'approved');

}

public function refuserCours(Request $request, $id)
{
    $request->validate([
        'reason' => ['nullable', 'string', 'max:1000'],
    ]);

    $cours = Course::with('teacherProfile.user')->findOrFail($id);

    $cours->update([
        'status'           => 'rejected',
        'is_active'        => false,
        'rejection_reason' => $request->reason,
    ]);

    Notification::notifier(
        userId: $cours->teacherProfile->user->id,
        type:   'course_rejected',
        title:  'Cours refusé',
        body:   'Votre cours "' . $cours->title . '" a été refusé.' . ($request->reason ? ' Raison : ' . $request->reason : ''),
        link:   route('professeur.dashboard')
    );

    // Envoi mail
    $user = $cours->teacherProfile->user;
    try {
        Mail::to($user->email)->send(new CoursRefuse($cours));
    } catch (\Throwable $e) {
        Log::error('Échec de l\'envoi du mail de cours refusé', [
            'exception' => $e,
            'course_id' => $cours->id,
            'user_id' => $user->id,
        ]);
    }

    return redirect()->route('admin.cours')
        ->with('success', 'Cours refusé.')
        ->with('active_tab', 'rejected');

}

public function supprimerCours($id)
{
    $cours = Course::findOrFail($id);
    $cours->delete();
    return back()->with('success', 'Cours supprimé définitivement.');
}
public function messagesAlertes()
{
    $alertes = MessageAlert::with(['message', 'sender', 'receiver'])
        ->orderByDesc('created_at')
        ->paginate(10);

    // Alertes système
    $comptesSuspects = \App\Models\User::where('message_attempts', '>=', 2)
        ->paginate(5, ['*'], 'suspects_page');

    $coursEnAttente48h = \App\Models\Course::with('teacherProfile.user')
        ->where('status', 'pending')
        ->where('created_at', '<=', now()->subHours(48))
        ->paginate(5, ['*'], 'cours_page');

    $profsNonVerifies = \App\Models\TeacherProfile::with('user')
        ->where('is_verified', false)
        ->where('created_at', '<=', now()->subDays(7))
        ->paginate(5, ['*'], 'profs_page');

    $reservationsAnnulees = \App\Models\User::withCount(['bookings as annulations' => function($q) {
            $q->where('status', 'annulé')->where('updated_at', '>=', now()->subDays(7));
        }])
        ->having('annulations', '>=', 3)
        ->paginate(5, ['*'], 'annulations_page');

    $profsInactifs = \App\Models\TeacherProfile::with('user')
        ->whereDoesntHave('courses', function($q) {
            $q->where('updated_at', '>=', now()->subDays(30));
        })
        ->paginate(5, ['*'], 'inactifs_page');

    return view('admin.messages-alertes', compact(
        'alertes',
        'comptesSuspects',
        'coursEnAttente48h',
        'profsNonVerifies',
        'reservationsAnnulees',
        'profsInactifs'
    ));
}

public function messagesConversations(Request $request)
{
    $search = trim((string)$request->query('q', ''));
    $filter = (string)$request->query('filter', 'all');

    $allMessages = Message::with(['sender.teacherProfile', 'receiver.teacherProfile'])
        ->orderByDesc('created_at')
        ->get();

    $grouped = $allMessages->groupBy(function($message) {
        $ids = [(int)$message->sender_id, (int)$message->receiver_id];
        sort($ids);
        return implode('-', $ids);
    });

    $conversations = $grouped->map(function($threadMessages, $key) {
        $lastMessage = $threadMessages->first();
        $user1 = $lastMessage->sender;
        $user2 = $lastMessage->receiver;

        if (!$user1 || !$user2) {
            return null;
        }

        $u1Id = $user1->id;
        $u2Id = $user2->id;

        $totalCount = $threadMessages->count();
        $blockedCount = $threadMessages->where('is_blocked', true)->count();
        $unreadCount = $threadMessages->where('is_read', false)->count();

        $pendingAlertsCount = MessageAlert::where(function($q) use ($u1Id, $u2Id) {
            $q->where('sender_id', $u1Id)->where('receiver_id', $u2Id);
        })->orWhere(function($q) use ($u1Id, $u2Id) {
            $q->where('sender_id', $u2Id)->where('receiver_id', $u1Id);
        })->where('status', 'pending')->count();

        $isAssistance = ($user1->role === 'admin' || $user2->role === 'admin');

        return (object) [
            'key' => $key,
            'user1' => $user1,
            'user2' => $user2,
            'lastMessage' => $lastMessage,
            'totalMessages' => $totalCount,
            'blockedCount' => $blockedCount,
            'unreadCount' => $unreadCount,
            'pendingAlertsCount' => $pendingAlertsCount,
            'hasAlert' => $pendingAlertsCount > 0,
            'hasBlocked' => $blockedCount > 0,
            'isAssistance' => $isAssistance,
        ];
    })->filter();

    // Calcul des KPI globaux
    $totalConversations = $conversations->count();
    $totalAssistanceCount = $conversations->filter(fn($c) => $c->isAssistance)->count();
    $totalUsersCount = $conversations->filter(fn($c) => !$c->isAssistance)->count();
    $totalMessagesCount = Message::count();
    $totalPendingAlerts = MessageAlert::where('status', 'pending')->count();
    $totalBlockedMessages = Message::where('is_blocked', true)->count();

    // Filtrage par statut
    if ($filter === 'assistance') {
        $conversations = $conversations->filter(fn($c) => $c->isAssistance);
    } elseif ($filter === 'users') {
        $conversations = $conversations->filter(fn($c) => !$c->isAssistance);
    } elseif ($filter === 'alerts') {
        $conversations = $conversations->filter(fn($c) => $c->hasAlert);
    } elseif ($filter === 'blocked') {
        $conversations = $conversations->filter(fn($c) => $c->hasBlocked);
    }

    // Filtrage par recherche
    if (!empty($search)) {
        $searchLower = mb_strtolower($search);
        $conversations = $conversations->filter(function($c) use ($searchLower) {
            $u1Name = mb_strtolower($c->user1->name ?? '');
            $u1Email = mb_strtolower($c->user1->email ?? '');
            $u2Name = mb_strtolower($c->user2->name ?? '');
            $u2Email = mb_strtolower($c->user2->email ?? '');
            $content = mb_strtolower($c->lastMessage->content ?? '');

            return str_contains($u1Name, $searchLower)
                || str_contains($u1Email, $searchLower)
                || str_contains($u2Name, $searchLower)
                || str_contains($u2Email, $searchLower)
                || str_contains($content, $searchLower);
        });
    }

    // Utilisateurs disponibles pour initier un nouveau message en tant qu'Assistance
    $availableUsers = User::where('role', '!=', 'admin')
        ->orderBy('name', 'asc')
        ->get(['id', 'name', 'email', 'role', 'avatar', 'ville']);

    $totalTeachersCount = $availableUsers->where('role', 'professeur')->count();
    $totalStudentsCount = $availableUsers->where('role', 'eleve')->count();
    $totalMembersCount  = $availableUsers->count();

    return view('admin.messages-conversations', compact(
        'conversations',
        'search',
        'filter',
        'totalConversations',
        'totalAssistanceCount',
        'totalUsersCount',
        'totalMessagesCount',
        'totalPendingAlerts',
        'totalBlockedMessages',
        'totalTeachersCount',
        'totalStudentsCount',
        'totalMembersCount',
        'availableUsers'
    ));
}

public function broadcastMessage(Request $request)
{
    $request->validate([
        'target_audience' => 'required|in:all_teachers,all_students,all_users,custom',
        'user_ids'        => 'nullable|array',
        'user_ids.*'      => 'exists:users,id',
        'content'         => 'required_without:attachment|nullable|string|max:5000',
        'attachment'      => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,gif,txt,zip',
    ]);

    $admin = auth()->user();

    // 1. Déterminer les destinataires
    $query = User::where('id', '!=', $admin->id);

    if ($request->target_audience === 'all_teachers') {
        $query->where('role', 'professeur');
    } elseif ($request->target_audience === 'all_students') {
        $query->where('role', 'eleve');
    } elseif ($request->target_audience === 'custom') {
        $userIds = $request->input('user_ids', []);
        if (empty($userIds)) {
            return back()->withInput()->with('error', 'Veuillez sélectionner au moins un destinataire pour l\'envoi ciblé.');
        }
        $query->whereIn('id', $userIds);
    }

    $recipients = $query->get();

    if ($recipients->isEmpty()) {
        return back()->withInput()->with('error', 'Aucun destinataire trouvé pour cette sélection.');
    }

    // 2. Traitement de la pièce jointe (enregistrée une seule fois sur le disque public)
    $attachmentPath = null;
    $attachmentName = null;
    $attachmentType = null;
    $attachmentSize = null;

    if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
        $file = $request->file('attachment');
        $attachmentName = $file->getClientOriginalName();
        $attachmentType = $file->getMimeType();
        $attachmentSize = $file->getSize();
        $attachmentPath = $file->store('messages/attachments', 'public');
    }

    $content = $request->input('content');
    $count = 0;

    // 3. Diffusion du message à chaque destinataire sélectionné
    foreach ($recipients as $recipient) {
        $msg = Message::create([
            'sender_id'       => $admin->id,
            'receiver_id'     => $recipient->id,
            'content'         => $content,
            'attachment'      => $attachmentPath,
            'attachment_name' => $attachmentName,
            'attachment_type' => $attachmentType,
            'attachment_size' => $attachmentSize,
            'is_read'         => false,
            'is_blocked'      => false,
        ]);

        // Notification in-app
        Notification::notifier(
            userId: $recipient->id,
            type:   'new_message',
            title:  'Message officiel de l\'Assistance Kimboo',
            body:   \Illuminate\Support\Str::limit($content ?? 'Nouvelle pièce jointe de l\'assistance', 80),
            link:   route('messages.show', $admin->id)
        );

        // Envoi email sécurisé avec tolérance aux pannes réseau/quota
        try {
            if ($recipient->email) {
                Mail::to($recipient->email)->send(new \App\Mail\NouveauMessageRecu($msg));
            }
        } catch (\Throwable $e) {
            Log::warning('Échec envoi email broadcast message', ['recipient' => $recipient->email, 'error' => $e->getMessage()]);
        }

        $count++;
    }

    $audienceLabel = match ($request->target_audience) {
        'all_teachers' => 'tous les professeurs (' . $count . ')',
        'all_students' => 'tous les élèves (' . $count . ')',
        'all_users'    => 'tous les membres (' . $count . ')',
        'custom'       => $count . ' membre(s) sélectionné(s)',
        default        => $count . ' destinataire(s)',
    };

    return redirect()->route('admin.messages.conversations')
        ->with('success', "Message d'assistance diffusé avec succès à {$audienceLabel}.");
}

public function voirConversation($userId1, $userId2)
{
    $user1 = User::with(['teacherProfile.courses', 'bookings'])->findOrFail($userId1);
    $user2 = User::with(['teacherProfile.courses', 'bookings'])->findOrFail($userId2);

    $messages = Message::where(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
    })->orWhere(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
    })
    ->with(['sender', 'alerts'])
    ->orderBy('created_at', 'asc')
    ->get();

    $alerts = MessageAlert::where(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
    })->orWhere(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
    })->get();

    return view('admin.messages-voir', compact('messages', 'user1', 'user2', 'alerts'));
}

public function alerteReviewed($id)
{
    MessageAlert::findOrFail($id)->update(['status' => 'reviewed']);
    return back()->with('success', 'Alerte marquée comme traitée.');
}

public function alerteIgnored($id)
{
    MessageAlert::findOrFail($id)->update(['status' => 'ignored']);
    return back()->with('success', 'Alerte ignorée.');
}


public function parametres(Request $request)
{
    $settings = SiteSetting::allSettings();
    $activeTab = $request->query('tab', 'general');

    return view('admin.parametres', compact('settings', 'activeTab'));
}

public function updateParametres(Request $request)
{
    $activeTab = $request->input('active_tab', $request->input('tab', 'general'));

    // Sauvegarder les paramètres envoyés
    $inputs = $request->except(['_token', 'active_tab', 'tab']);

    // Si on est sur l'onglet accueil et que la case banner_active n'est pas cochée
    if ($activeTab === 'homepage') {
        $inputs['banner_active'] = $request->has('banner_active') ? '1' : '0';
    }

    foreach ($inputs as $key => $value) {
        SiteSetting::set($key, is_string($value) ? trim($value) : $value);
    }

    return redirect()->route('admin.parametres', ['tab' => $activeTab])
        ->with('success', 'Paramètres mis à jour avec succès.');
}

}
