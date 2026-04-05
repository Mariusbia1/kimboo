@extends('layouts.dashboard')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', 'Gérez tous les utilisateurs de la plateforme')

@section('content')

@if(session('success'))
<div class="mb-6 px-4 py-3 rounded-xl text-sm font-medium text-green-700 bg-green-100">
    ✓ {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl p-6" style="box-shadow:0 4px 12px rgba(0,0,0,0.06);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Utilisateur</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Rôle</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Ville</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Téléphone</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Inscrit le</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-black shrink-0" style="background:#FCB315;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-black">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        @if($user->role === 'admin')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Admin</span>
                        @elseif($user->role === 'professeur')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Professeur</span>
                        @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Élève</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $user->ville ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-4">
                        @if($user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.delete-user', $user->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Confirmer la suppression ?')"
                                class="text-xs px-3 py-1.5 rounded-lg text-white font-medium bg-red-500">
                                Supprimer
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
