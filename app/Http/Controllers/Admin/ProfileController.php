<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('admin.profile.edit', [
            'user' => $user,
        ]);
    }

    public function update(UpdateAdminProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->boolean('remove_avatar')) {
            $user->deleteAvatar();
            $user->avatar_path = null;
        }

        if ($request->file('avatar') instanceof UploadedFile) {
            $user->deleteAvatar();
            $user->avatar_path = $this->storeAvatar($request->file('avatar'));
        }

        if (filled($validated['password'] ?? null)) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    private function storeAvatar(UploadedFile $file): string
    {
        $extension = $file->guessExtension() ?: 'jpg';

        return $file->storeAs(
            'admin-avatars',
            Str::uuid()->toString().'.'.$extension,
            'public',
        );
    }
}
