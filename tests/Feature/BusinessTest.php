<?php

use App\Models\Business;
use App\Models\User;

test('guests are redirected to the login page when accessing businesses', function () {
    $response = $this->get(route('businesses.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can view business list', function () {
    $user = User::factory()->create();
    $business = Business::create([
        'name' => 'Toko Berkah',
        'owner_name' => 'Budi',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('businesses.index'));
    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('businesses/Index')
        ->has('businesses.data', 1)
    );
});

test('authenticated users can render create business page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('businesses.create'));
    $response->assertOk();
});

test('authenticated users can create a new business', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('businesses.store'), [
        'name' => 'Warung Maju',
        'owner_name' => 'Siti',
    ]);

    $response->assertRedirect(route('businesses.index'));
    $this->assertDatabaseHas('businesses', [
        'name' => 'Warung Maju',
        'owner_name' => 'Siti',
    ]);
});

test('authenticated users can render edit business page', function () {
    $user = User::factory()->create();
    $business = Business::create([
        'name' => 'Toko A',
        'owner_name' => 'Owner A',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('businesses.edit', $business));
    $response->assertOk();
});

test('authenticated users can update a business', function () {
    $user = User::factory()->create();
    $business = Business::create([
        'name' => 'Toko Lama',
        'owner_name' => 'Owner Lama',
    ]);

    $this->actingAs($user);

    $response = $this->put(route('businesses.update', $business), [
        'name' => 'Toko Baru',
        'owner_name' => 'Owner Baru',
    ]);

    $response->assertRedirect(route('businesses.index'));
    $this->assertDatabaseHas('businesses', [
        'id' => $business->id,
        'name' => 'Toko Baru',
        'owner_name' => 'Owner Baru',
    ]);
});

test('authenticated users can delete a business', function () {
    $user = User::factory()->create();
    $business = Business::create([
        'name' => 'Toko Hapus',
        'owner_name' => 'Owner Hapus',
    ]);

    $this->actingAs($user);

    $response = $this->delete(route('businesses.destroy', $business));

    $response->assertRedirect(route('businesses.index'));
    $this->assertSoftDeleted('businesses', [
        'id' => $business->id,
    ]);
});
