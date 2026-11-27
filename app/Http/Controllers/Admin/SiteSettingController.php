<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminPanelPathRequest;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Support\AdminPanelPath;
use App\Support\SiteSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(SiteSettings $settings, AdminPanelPath $adminPanelPath): View
    {
        return view('admin.settings.edit', [
            'settings' => $settings->all(),
            'adminPanelPath' => $adminPanelPath->current(),
            'adminLoginUrl' => $adminPanelPath->loginUrl(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, SiteSettings $settings): RedirectResponse
    {
        $settings->putMany($request->settingsPayload());

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Website settings saved successfully.');
    }

    public function updateAdminPath(UpdateAdminPanelPathRequest $request, AdminPanelPath $adminPanelPath): RedirectResponse
    {
        $previous = $adminPanelPath->current();
        $path = $adminPanelPath->update((string) $request->validated('admin_panel_path'));

        Artisan::call('route:clear');
        Artisan::call('config:clear');

        $settingsUrl = url('/'.$path.'/settings');
        $loginUrl = url('/'.$path.'/login');

        $message = $previous === $path
            ? 'Admin login path is unchanged.'
            : 'Admin login path updated. New login URL: '.$loginUrl;

        return redirect()
            ->to($settingsUrl)
            ->with('success', $message)
            ->with('admin_login_url', $loginUrl);
    }
}
