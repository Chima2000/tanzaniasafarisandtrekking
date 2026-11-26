<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(): View
    {
        return view('admin.contacts.index', [
            'contacts' => ContactSubmission::query()->latest()->paginate(15),
        ]);
    }

    public function show(ContactSubmission $contact): View
    {
        return view('admin.contacts.show', [
            'contact' => $contact,
        ]);
    }

    public function update(Request $request, ContactSubmission $contact): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,reviewed,contacted,closed'],
        ]);

        $contact->update($validated);

        return redirect()
            ->route('admin.contacts.show', $contact)
            ->with('success', 'Contact status updated.');
    }
}
