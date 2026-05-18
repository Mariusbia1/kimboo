@extends('layouts.dashboard')

@section('title', 'Paramètres du site')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white" style="font-family:'Plus Jakarta Sans',sans-serif;">
            Paramètres du site
        </h1>
        <p class="text-gray-400 text-sm mt-1">Gérez les liens des réseaux sociaux affichés dans le footer.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-black" style="background:#FCB315;">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.parametres.update') }}">
        @csrf

        <div class="rounded-2xl p-6 space-y-5" style="background:#1a1a1a; border:1px solid #2a2a2a;">

            <h2 class="text-white font-semibold text-base mb-2">Réseaux sociaux</h2>

            <!-- Facebook -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs" style="background:#1877F2;">f</span>
                        Facebook
                    </span>
                </label>
                <input type="url" name="facebook" value="{{ $settings['facebook'] }}"
                    placeholder="https://facebook.com/kimboo"
                    class="w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none focus:ring-2 transition"
                    style="background:#2a2a2a; border:1px solid #3a3a3a; focus-ring-color:#FCB315;">
            </div>

            <!-- Instagram -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs" style="background:linear-gradient(135deg,#f09433,#dc2743,#bc1888);">ig</span>
                        Instagram
                    </span>
                </label>
                <input type="url" name="instagram" value="{{ $settings['instagram'] }}"
                    placeholder="https://instagram.com/kimboo"
                    class="w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none transition"
                    style="background:#2a2a2a; border:1px solid #3a3a3a;">
            </div>

            <!-- TikTok -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs" style="background:#010101; border:1px solid #333;">tt</span>
                        TikTok
                    </span>
                </label>
                <input type="url" name="tiktok" value="{{ $settings['tiktok'] }}"
                    placeholder="https://tiktok.com/@kimboo"
                    class="w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none transition"
                    style="background:#2a2a2a; border:1px solid #3a3a3a;">
            </div>

            <!-- WhatsApp -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">
                    <span class="inline-flex items-center gap-2">
                        <span class="w-5 h-5 rounded flex items-center justify-center text-white text-xs" style="background:#25D366;">w</span>
                        WhatsApp
                    </span>
                </label>
                <input type="url" name="whatsapp" value="{{ $settings['whatsapp'] }}"
                    placeholder="https://wa.me/2250700000000"
                    class="w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none transition"
                    style="background:#2a2a2a; border:1px solid #3a3a3a;">
            </div>

        </div>

        <div class="mt-6">
            <button type="submit"
                class="px-8 py-3 rounded-xl font-semibold text-black text-sm transition hover:opacity-90"
                style="background:#FCB315;">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
