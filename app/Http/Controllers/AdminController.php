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




class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProfesseurs = User::where('role', 'professeur')->count();
        $totalEleves = User::where('role', 'eleve')->count();
        $totalReservations = Booking::count();
        $totalCours = Course::count();
        $professeurs = TeacherProfile::with('user')->latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProfesseurs', 'totalEleves',
            'totalReservations', 'totalCours', 'professeurs'
        ));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users', compact('users'));
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

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

public function cours()
{
    $coursPending  = Course::with('teacherProfile.user')
        ->where('status', 'pending')
        ->latest()->get();

    $coursApproved = Course::with('teacherProfile.user')
        ->where('status', 'approved')
        ->latest()->limit(10)->get();

    $coursRejected = Course::with('teacherProfile.user')
        ->where('status', 'rejected')
        ->latest()->limit(10)->get();

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
    $cours->update(['status' => 'approved', 'is_active' => true]);

    // Notifier le prof
    Notification::notifier(
        userId: $cours->teacherProfile->user->id,
        type:   'course_approved',
        title:  'Cours validé',
        body:   'Votre cours "' . $cours->title . '" a été approuvé et est maintenant visible par les élèves.',
        link:   route('professeur.create-cours')
    );

    return back()->with('success', 'Cours approuvé et publié avec succès.');
}

public function refuserCours(Request $request, $id)
{
    $cours = Course::with('teacherProfile.user')->findOrFail($id);
    $cours->update([
        'status'           => 'rejected',
        'is_active'        => false,
        'rejection_reason' => $request->reason,
    ]);

    // Notifier le prof
    Notification::notifier(
        userId: $cours->teacherProfile->user->id,
        type:   'course_rejected',
        title:  'Cours refusé',
        body:   'Votre cours "' . $cours->title . '" a été refusé.' . ($request->reason ? ' Raison : ' . $request->reason : ''),
        link:   route('professeur.create-cours')
    );

    return back()->with('success', 'Cours refusé.');
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

public function messagesConversations()
{
    $conversations = Message::with(['sender', 'receiver'])
        ->orderByDesc('created_at')
        ->get()
        ->groupBy(function($message) {
            $ids = [$message->sender_id, $message->receiver_id];
            sort($ids);
            return implode('-', $ids);
        })
        ->map(fn($messages) => $messages->first());

    return view('admin.messages-conversations', compact('conversations'));
}

public function voirConversation($userId1, $userId2)
{
    $user1 = User::findOrFail($userId1);
    $user2 = User::findOrFail($userId2);

    $messages = Message::where(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
    })->orWhere(function($q) use ($userId1, $userId2) {
        $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
    })
    ->with(['sender'])
    ->orderBy('created_at', 'asc')
    ->get();

    return view('admin.messages-voir', compact('messages', 'user1', 'user2'));
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


public function parametres()
{
    $settings = [
        'facebook'  => SiteSetting::get('facebook'),
        'instagram' => SiteSetting::get('instagram'),
        'tiktok'    => SiteSetting::get('tiktok'),
        'whatsapp'  => SiteSetting::get('whatsapp'),
    ];

    return view('admin.parametres', compact('settings'));
}

public function updateParametres(Request $request)
{
    foreach (['facebook', 'instagram', 'tiktok', 'whatsapp'] as $key) {
        SiteSetting::set($key, $request->input($key) ?? '');
    }

    return back()->with('success', 'Paramètres mis à jour avec succès.');
}

}
