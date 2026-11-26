<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNotificationRecipientRequest;
use App\Http\Requests\Admin\UpdateNotificationRecipientRequest;
use App\Models\NotificationRecipient;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationRecipientController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications.index', [
            'recipients' => NotificationRecipient::query()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function store(StoreNotificationRecipientRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        NotificationRecipient::query()->create([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'],
            'notification_types' => NotificationRecipient::normalizeTypes($validated['notification_types'] ?? []),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => ((int) NotificationRecipient::query()->max('sort_order')) + 1,
        ]);

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notification email added.');
    }

    public function update(UpdateNotificationRecipientRequest $request, NotificationRecipient $recipient): RedirectResponse
    {
        $validated = $request->validated();

        $recipient->fill([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'],
            'notification_types' => NotificationRecipient::normalizeTypes($validated['notification_types'] ?? []),
            'is_active' => $request->boolean('is_active'),
        ])->save();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notification email updated.');
    }

    public function destroy(NotificationRecipient $recipient): RedirectResponse
    {
        $recipient->delete();

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', 'Notification email removed.');
    }
}
