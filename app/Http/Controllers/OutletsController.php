<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutletsRequest;
use App\Http\Requests\UpdateOutletsRequest;
use App\Models\Outlets;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OutletsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutletsRequest $request): RedirectResponse
    {
        $request->user()->business->outlets()->create($request->validated());

        return to_route('business.edit')->with('success', 'Outlet created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutletsRequest $request, string $id): RedirectResponse
    {
        $outlet = $request->user()->business->outlets()->findOrFail($id);  // ← scoped!
        $outlet->update($request->validated());

        return to_route('business.edit')->with('success', 'Outlet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $outlet = $request->user()->business->outlets()->findOrFail($id);  // ← scoped!
        $outlet->delete();

        return to_route('business.edit')->with('success', 'Outlet deleted successfully.');
    }
}
