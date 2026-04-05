<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'role' => ['required', 'in:eleve,professeur'],
    'phone' => ['nullable', 'string', 'max:20'],
    'ville' => ['nullable', 'string', 'max:100'],
]);

    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role,
    'phone' => $request->phone,
    'ville' => $request->ville,
]);

    // Si professeur, créer le profil automatiquement
    if ($user->role === 'professeur') {
        $user->teacherProfile()->create([
            'hourly_rate' => 0,
            'bio' => '',
        ]);
    }

    event(new Registered($user));
    Auth::login($user);

    return redirect('/dashboard');
}
}
