<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BusinessController extends Controller
{
    /**
     * Display a listing of the businesses.
     */
    public function index(Request $request): Response
    {
        $search = $request->query('search', '');

        $businesses = Business::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('owner_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('businesses/Index', [
            'businesses' => $businesses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new business.
     */
    public function create(): Response
    {
        return Inertia::render('businesses/Create');
    }

    /**
     * Store a newly created business in storage.
     */
    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        Business::create($request->validated());

        return to_route('businesses.index')->with('success', 'Business created successfully.');
    }

    /**
     * Show the form for editing the specified business.
     */
    public function edit(Business $business): Response
    {
        return Inertia::render('businesses/Edit', [
            'business' => $business,
        ]);
    }

    /**
     * Update the specified business in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        $business->update($request->validated());

        return to_route('businesses.index')->with('success', 'Business updated successfully.');
    }

    /**
     * Remove the specified business from storage.
     */
    public function destroy(Business $business): RedirectResponse
    {
        $business->delete();

        return to_route('businesses.index')->with('success', 'Business deleted successfully.');
    }
}
