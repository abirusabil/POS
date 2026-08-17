<?php

use App\Models\Business;
use App\Models\Outlets;
use App\Models\User;

beforeEach(function () {
    $this->business = Business::factory()->create();
    $this->user = User::factory()->create([
        'business_id' => $this->business->id,
        'role' => 'owner',
    ]);
    $this->actingAs($this->user);
});

test('a user can create an outlet for their own business', function () {

    // ── ACT: lakukan aksi (kirim POST ke store) ──
    $response = $this->post(route('outlets.store'), [
        'name' => 'New Outlet',
        'address' => '123 Main St',
        'phone' => '555-5555',
    ]);

    // ── ASSERT: cek hasilnya ──
    $response->assertRedirect(route('business.edit'));   // dialihkan ke halaman Manage?
    $this->assertDatabaseHas('outlets', [                 // outlet-nya ada di DB?
        'name' => 'New Outlet',
        'business_id' => $this->business->id,             // business_id keisi otomatis?
    ]);
});

test('the create outlet page shows validation errors if required fields are missing', function () {
    $response = $this->post(route('outlets.store'), [
        'name' => ''
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('a user can update their own outlet', function () {
    // ── ARRANGE: bikin outlet DI business milik user ──
    $outlet = Outlets::factory()->create(['business_id' => $this->business->id]);

    // ── ACT ──
    $response = $this->put(route('outlets.update', $outlet), [
        'name' => 'Updated Outlet',
        'address' => '456 Updated St',
        'phone' => '555-555-1234',
    ]);

    // ── ASSERT ──
    $response->assertRedirect(route('business.edit'));
    $this->assertDatabaseHas('outlets', [
        'id' => $outlet->id,
        'name' => 'Updated Outlet',
    ]);
});



test('the update outlet page shows validation errors if required fields are missing', function () {
    
    $outlet = Outlets::factory()->create(['business_id' => $this->business->id]);

    $response = $this->put(route('outlets.update', $outlet), [
        'name' => '',
    ]);

    $response->assertSessionHasErrors(['name']);
});

test('a user can delete their own outlet', function () {
    $outlet = Outlets::factory()->create(['business_id' => $this->business->id]);

    $response = $this->delete(route('outlets.destroy', $outlet));

    $response->assertRedirect(route('business.edit'));
    $this->assertSoftDeleted('outlets', [
        'id' => $outlet->id,
    ]);
});

test('a user cannot delete an outlet from another business', function () {
    // ARRANGE: outlet milik business ORANG LAIN (bukan $this->business)
    $otherBusiness = Business::factory()->create();
    $otherOutlet = Outlets::factory()->create(['business_id' => $otherBusiness->id]);

    // ACT: user login (dari beforeEach) coba hapus outlet yang bukan miliknya
    $response = $this->delete(route('outlets.destroy', $otherOutlet));

    // ASSERT: ditolak 404, dan outlet-nya masih utuh
    $response->assertNotFound();
    $this->assertNotSoftDeleted('outlets', ['id' => $otherOutlet->id]);
});