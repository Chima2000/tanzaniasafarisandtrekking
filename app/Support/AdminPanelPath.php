<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class AdminPanelPath
{
    public const STORAGE_FILE = 'admin-panel-path.txt';

    /**
     * Public first-path segments that must never be used as the admin URI.
     *
     * @return list<string>
     */
    public static function reserved(): array
    {
        return [
            'admin',
            'administrator',
            'api',
            'auth',
            'aventour',
            'backend',
            'blog',
            'book-now',
            'build',
            'cms',
            'contact',
            'controlpanel',
            'css',
            'dashboard',
            'destinations',
            'faq',
            'favicon.ico',
            'js',
            'login',
            'logo',
            'manage',
            'packages',
            'panel',
            'panel-assets',
            'photos',
            'robots.txt',
            'safari-packages',
            'search',
            'sitemap.xml',
            'storage',
            'up',
            'user',
            'vendor',
            'wp-admin',
            'wp-login.php',
            'about',
            'cultural-experiences',
            'day-trips',
            'kilimanjaro-treks',
        ];
    }

    public function current(): string
    {
        $stored = $this->read();

        if ($stored !== null) {
            return $stored;
        }

        $fromConfig = trim((string) config('app.admin_path', 'admin'), '/');

        return $fromConfig !== '' ? $fromConfig : 'admin';
    }

    public function loginUrl(): string
    {
        return url($this->current().'/login');
    }

    public function update(string $path): string
    {
        $normalized = $this->normalize($path);
        $this->assertAllowed($normalized);

        File::ensureDirectoryExists(dirname($this->storagePath()));
        File::put($this->storagePath(), $normalized.PHP_EOL);

        return $normalized;
    }

    public function normalize(string $path): string
    {
        $path = strtolower(trim($path));
        $path = trim($path, '/');

        return $path;
    }

    public function isAllowed(string $path): bool
    {
        $path = $this->normalize($path);

        if ($path === '' || str_contains($path, '/') || str_contains($path, '\\')) {
            return false;
        }

        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $path)) {
            return false;
        }

        if (strlen($path) < 6 || strlen($path) > 64) {
            return false;
        }

        return ! in_array($path, self::reserved(), true);
    }

    protected function assertAllowed(string $path): void
    {
        if ($this->isAllowed($path)) {
            return;
        }

        throw ValidationException::withMessages([
            'admin_panel_path' => 'Choose a different login path. Use 6–64 lowercase letters, numbers, and hyphens only, and avoid common admin names.',
        ]);
    }

    protected function read(): ?string
    {
        if (! File::exists($this->storagePath())) {
            return null;
        }

        $value = $this->normalize((string) File::get($this->storagePath()));

        return $this->isAllowed($value) ? $value : null;
    }

    protected function storagePath(): string
    {
        return storage_path('app/'.self::STORAGE_FILE);
    }
}
