<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'email', 'password', 'is_admin', 'avatar_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function hasCustomAvatar(): bool
    {
        return filled($this->avatar_path)
            && Storage::disk('public')->exists($this->avatar_path);
    }

    public function avatarUrl(): string
    {
        if ($this->hasCustomAvatar()) {
            return Storage::disk('public')->url($this->avatar_path);
        }

        return admin_asset('images/users/avatar-1.jpg');
    }

    public function deleteAvatar(): void
    {
        if (! filled($this->avatar_path)) {
            return;
        }

        if (Storage::disk('public')->exists($this->avatar_path)) {
            Storage::disk('public')->delete($this->avatar_path);
        }
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            $user->deleteAvatar();
        });
    }
}
