<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(SiteSettings $settings): View
    {
        return view('admin.settings.edit', [
            'settings' => $settings->all(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, SiteSettings $settings): RedirectResponse
    {
        $settings->putMany($request->validated());

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Website settings saved successfully.');
    }
}
