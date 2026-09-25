<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.website.services.index', ['services' => Service::query()->orderBy('sort_order')->orderByDesc('id')->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.website.services.form', ['service' => null]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('admin.website.services.form', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('success', 'Service deleted.');
    }

    public function toggle(Service $service): RedirectResponse
    {
        $service->update(['is_published' => ! $service->is_published]);

        return back()->with('success', 'Service status updated.');
    }
}
