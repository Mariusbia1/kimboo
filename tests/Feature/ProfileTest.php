<?php

use App\Models\User;

test('profile page is displayed for eleve', function () {
    $user = User::factory()->create(['role' => 'eleve']);

    $response = $this
        ->actingAs($user)
        ->get('/eleve/profil');

    $response->assertOk();
});

test('profile information can be updated for eleve', function () {
    $user = User::factory()->create(['role' => 'eleve']);

    $response = $this
        ->actingAs($user)
        ->post('/eleve/profil', [
            'name' => 'Test User Updated',
            'phone' => '0700000000',
            'ville' => 'Abidjan',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/eleve/profil');

    $user->refresh();

    $this->assertSame('Test User Updated', $user->name);
    $this->assertSame('Abidjan', $user->ville);
});

test('user can delete their account', function () {
    $user = User::factory()->create(['role' => 'eleve', 'password' => bcrypt('password')]);

    $response = $this
        ->actingAs($user)
        ->delete('/eleve/profil', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create(['role' => 'eleve', 'password' => bcrypt('password')]);

    $response = $this
        ->actingAs($user)
        ->from('/eleve/profil')
        ->delete('/eleve/profil', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/eleve/profil');

    $this->assertNotNull($user->fresh());
});
