<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWhatsappContactRequest;
use App\Http\Requests\Admin\UpdateWhatsappContactRequest;
use App\Http\Requests\Admin\UpdateWhatsappSettingRequest;
use App\Models\WhatsappContact;
use App\Models\WhatsappSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WhatsappWidgetController extends Controller
{
    public function edit(): View
    {
        return view('admin.whatsapp.index', [
            'setting' => WhatsappSetting::current(),
        ]);
    }

    public function update(UpdateWhatsappSettingRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $setting = WhatsappSetting::query()->first() ?? new WhatsappSetting;

        $setting->fill([
            'phone_number' => WhatsappSetting::normalizePhone($validated['phone_number'] ?? null),
            'enabled' => $request->boolean('enabled'),
            'message' => $validated['message'] ?? null,
            'popup_title' => $validated['popup_title'] ?? null,
            'popup_description' => $validated['popup_description'] ?? null,
            'popup_note' => $validated['popup_note'] ?? null,
            'corner' => $validated['corner'],
            'offset_x' => $validated['offset_x'],
            'offset_y' => $validated['offset_y'],
            'icon_width' => $validated['icon_width'],
            'icon_height' => $validated['icon_height'],
            'icon_color' => $validated['icon_color'],
            'show_on_all_pages' => $request->boolean('show_on_all_pages'),
            'pages' => array_values(array_intersect(
                array_keys(WhatsappSetting::PAGE_LABELS),
                $validated['pages'] ?? [],
            )),
        ])->save();

        return redirect()
            ->route('admin.whatsapp.edit')
            ->with('success', 'WhatsApp settings saved.');
    }

    public function destroy(): RedirectResponse
    {
        $setting = WhatsappSetting::query()->first();

        $message = 'WhatsApp number removed. The button is hidden.';

        if ($setting instanceof WhatsappSetting) {
            $payload = [
                'phone_number' => null,
                'message' => null,
            ];

            if ($setting->contacts()->exists()) {
                $message = 'WhatsApp number removed. The button still uses the saved profiles.';
            } else {
                $payload['enabled'] = false;
            }

            $setting->fill($payload)->save();
        }

        return redirect()
            ->route('admin.whatsapp.edit')
            ->with('success', $message);
    }

    public function storeContact(StoreWhatsappContactRequest $request): RedirectResponse
    {
        $setting = WhatsappSetting::query()->first() ?? WhatsappSetting::query()->create(WhatsappSetting::defaults());
        $validated = $request->validated();

        $contact = new WhatsappContact([
            'section' => $validated['section'] ?? null,
            'name' => $validated['name'],
            'title' => $validated['title'],
            'phone_number' => WhatsappSetting::normalizePhone($validated['phone_number']),
            'sort_order' => ((int) $setting->contacts()->max('sort_order')) + 1,
        ]);
        $contact->whatsapp_setting_id = $setting->id;

        if ($request->file('photo') instanceof UploadedFile) {
            $contact->photo_path = $this->storeContactPhoto($request->file('photo'));
        }

        $contact->save();

        return redirect()
            ->route('admin.whatsapp.edit')
            ->with('success', 'WhatsApp profile added.');
    }

    public function updateContact(UpdateWhatsappContactRequest $request, WhatsappContact $contact): RedirectResponse
    {
        $validated = $request->validated();
        $contact->fill([
            'section' => $validated['section'] ?? null,
            'name' => $validated['name'],
            'title' => $validated['title'],
            'phone_number' => WhatsappSetting::normalizePhone($validated['phone_number']),
        ]);

        if ($request->boolean('remove_photo')) {
            $contact->deletePhoto();
            $contact->photo_path = null;
        }

        if ($request->file('photo') instanceof UploadedFile) {
            $contact->deletePhoto();
            $contact->photo_path = $this->storeContactPhoto($request->file('photo'));
        }

        $contact->save();

        return redirect()
            ->route('admin.whatsapp.edit')
            ->with('success', 'WhatsApp profile updated.');
    }

    public function destroyContact(WhatsappContact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()
            ->route('admin.whatsapp.edit')
            ->with('success', 'WhatsApp profile removed.');
    }

    private function storeContactPhoto(UploadedFile $file): string
    {
        $extension = $file->guessExtension() ?: 'jpg';

        return $file->storeAs(
            'whatsapp-contacts',
            Str::uuid()->toString().'.'.$extension,
            'public',
        );
    }
}
