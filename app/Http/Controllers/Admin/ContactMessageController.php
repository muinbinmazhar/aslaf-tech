<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.website.messages.index', ['messages' => ContactMessage::query()->latest()->paginate(20)]);
    }

    public function show(ContactMessage $message): View
    {
        return view('admin.website.messages.show', compact('message'));
    }

    public function toggle(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', 'Message status updated.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
