<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MyBusinessController extends Controller
{
    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        $user = $request->user();

        // guard: user ini sudah punya business? tolak (nggak boleh punya 2)
        abort_if($user->business_id, 403);

        DB::transaction(function () use ($user, $request) {
            $business = Business::create($request->validated());

            // INGAT: business_id & role TIDAK fillable → set eksplisit, bukan mass-assign
            $user->business_id = $business->id;
            $user->role = 'owner';
            $user->save();
        });

        return to_route('business.edit')->with('success', 'Business created.');
    }

    public function edit(Request $request): Response
    {
        $business = $request->user()->business;   // null kalau belum onboarding

        return Inertia::render('business/Manage', [
            'business' => $business?->load('outlets'),
        ]);
    }

    public function update(UpdateBusinessRequest $request): RedirectResponse
    {
        $request->user()->business->update($request->validated());

        return to_route('business.edit')->with('success', 'Business updated.');
    }

    public function destroy(Request $request, string $id): RedirectResponse
    {
        $outlet = $request->user()->business->outlets()->findOrFail($id);  // scoped
        $outlet->delete();

        return to_route('business.edit')->with('success', 'Outlet deleted successfully.');
    }
}
