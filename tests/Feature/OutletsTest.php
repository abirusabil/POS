<?php

use App\Models\Business;
use App\Models\Outlets;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('an admin user can view the outlets index page', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $response = $this->get(route('outlets.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('outlets/Index')
        ->has('outlets.data', 1, fn (Assert $p) => $p
            ->where('id', $outlet->id)
            ->where('name', $outlet->name)
            ->where('address', $outlet->address)
            ->where('phone', $outlet->phone)
            ->where('business_id', $business->id)
            ->etc()
        )
    );
});

test('an admin user can view the create outlets page', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('outlets.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('outlets/Create')
        ->has('businesses', 1, fn (Assert $p) => $p
            ->where('id', $business->id)
            ->where('name', $business->name)
            ->etc()
        )
    );
});

test('an admin user can create a new outlet', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();

    $this->actingAs($user);

    $attributes = [
        'name' => 'New Outlet',
        'address' => '123 Main St',
        'phone' => '555-555-5555',
        'business_id' => $business->id,
    ];

    $response = $this->post(route('outlets.store'), $attributes);

    $response->assertRedirect(route('outlets.index'));
    $this->assertDatabaseHas('outlets', [
        'name' => 'New Outlet',
        'address' => '123 Main St',
        'phone' => '555-555-5555',
        'business_id' => $business->id,
    ]);
});

test('the create outlet page shows validation errors if required fields are missing', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('outlets.store'), [
        'name' => '',
        'business_id' => '',
    ]);

    $response->assertSessionHasErrors(['name', 'business_id']);
});

test('the create outlet page shows validation errors for non-existent business', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('outlets.store'), [
        'name' => 'New Outlet',
        'address' => '123 Main St',
        'phone' => '555-555-5555',
        'business_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertSessionHasErrors('business_id');
});

test('an admin user can view the edit outlets page', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $response = $this->get(route('outlets.edit', $outlet));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('outlets/Edit')
        ->where('outlet.id', $outlet->id)
        ->where('outlet.name', $outlet->name)
        ->where('outlet.address', $outlet->address)
        ->where('outlet.phone', $outlet->phone)
    );
});

test('an admin user can update an outlet', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $attributes = [
        'name' => 'Updated Outlet',
        'address' => '456 Updated St',
        'phone' => '555-555-1234',
        'business_id' => $business->id,
    ];

    $response = $this->put(route('outlets.update', $outlet), $attributes);

    $response->assertRedirect(route('outlets.index'));
    $this->assertDatabaseHas('outlets', [
        'id' => $outlet->id,
        'name' => 'Updated Outlet',
        'address' => '456 Updated St',
        'phone' => '555-555-1234',
        'business_id' => $business->id,
    ]);
});

test('the update outlet page shows validation errors if required fields are missing', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $response = $this->put(route('outlets.update', $outlet), [
        'name' => '',
        'business_id' => '',
    ]);

    $response->assertSessionHasErrors(['name', 'business_id']);
});

test('the update outlet page shows validation errors for non-existent business', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $response = $this->put(route('outlets.update', $outlet), [
        'name' => 'Updated Outlet',
        'address' => '456 Updated St',
        'phone' => '555-555-1234',
        'business_id' => '00000000-0000-0000-0000-000000000000',
    ]);

    $response->assertSessionHasErrors('business_id');
});

test('an admin user can delete an outlet', function () {
    $user = User::factory()->create();
    $business = Business::factory()->create();
    $outlet = Outlets::factory()->create(['business_id' => $business->id]);

    $this->actingAs($user);

    $response = $this->delete(route('outlets.destroy', $outlet));

    $response->assertRedirect(route('outlets.index'));
    $this->assertSoftDeleted('outlets', [
        'id' => $outlet->id,
    ]);
});
