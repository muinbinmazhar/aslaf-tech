<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SaveContactLink;
use App\Actions\OrderContactLinks;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactLinkRequest;
use App\Http\Requests\Admin\ContactOrderRequest;
use App\Models\ContactLink;
use App\Support\ContactTypes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactLinkController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', Rule::in(array_keys(ContactTypes::all()))],
            'visibility' => ['nullable', Rule::in(['visible', 'hidden'])],
        ]);
        $links = ContactLink::query()
            ->when($filters['q'] ?? null, fn ($query, $q) => $query->where(function ($query) use ($q) {
                $query->where('display_name', 'like', '%'.$q.'%')->orWhere('value', 'like', '%'.$q.'%');
            }))
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when($filters['visibility'] ?? null, fn ($query, $status) => $query->where('is_visible', $status === 'visible'))
            ->orderBy('sort_order')->orderBy('id')->paginate(15)->withQueryString();
        $sortable = empty($filters['q']) && empty($filters['type']) && empty($filters['visibility']);

        return view('admin.contact-links.index', compact('links', 'filters', 'sortable'));
    }

    public function create(): View
    {
        return view('admin.contact-links.form', ['contact' => null]);
    }

    public function store(ContactLinkRequest $request, SaveContactLink $action): RedirectResponse
    {
        $action->handle($request->validated());
        return redirect()->route('admin.contact-links.index')->with('success', 'Contact added.');
    }

    public function edit(ContactLink $contactLink): View
    {
        return view('admin.contact-links.form', ['contact' => $contactLink]);
    }

    public function update(ContactLinkRequest $request, ContactLink $contactLink, SaveContactLink $action): RedirectResponse
    {
        $action->handle($request->validated(), $contactLink);
        return redirect()->route('admin.contact-links.index')->with('success', 'Contact updated.');
    }

    public function toggle(ContactLink $contactLink): RedirectResponse
    {
        $contactLink->update(['is_visible' => ! $contactLink->is_visible]);
        return back()->with('success', 'Visibility updated.');
    }

    public function reorder(ContactOrderRequest $request, OrderContactLinks $action): JsonResponse
    {
        $data = $request->validated();
        $action->reorderPage($data['ids'], (int) $data['page']);

        return response()->json(['message' => 'Contact order saved.']);
    }

    public function move(ContactOrderRequest $request, ContactLink $contactLink, OrderContactLinks $action): RedirectResponse
    {
        $moved = $action->move($contactLink, $request->validated()['direction']);

        return back()->with($moved ? 'success' : 'error', $moved ? 'Contact moved.' : 'Contact is already at the edge.');
    }

    public function destroy(ContactLink $contactLink): RedirectResponse
    {
        $contactLink->delete();
        return back()->with('success', 'Contact deleted.');
    }
}
