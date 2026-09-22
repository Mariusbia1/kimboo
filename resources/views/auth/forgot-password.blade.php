<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Mot de passe oublié ? Aucun problème. Indiquez-nous simplement votre adresse email et nous vous enverrons un lien de réinitialisation de mot de passe.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Adresse email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                Retour à la connexion
            </a>
            <x-primary-button>
                {{ __('Envoyer le lien de réinitialisation') }}
            </x-primary-button>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-center">
            <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-black transition" href="{{ route('home') }}">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Retour à l'accueil</span>
            </a>
        </div>
    </form>
</x-guest-layout>
