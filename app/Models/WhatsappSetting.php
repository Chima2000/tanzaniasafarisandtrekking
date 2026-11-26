<?php

namespace App\Models;

use Closure;
use Database\Factories\WhatsappSettingFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class WhatsappSetting extends Model
{
    /** @use HasFactory<WhatsappSettingFactory> */
    use HasFactory;

    public const BRAND_COLOR = '#25D366';

    /**
     * @var array<string, string>
     */
    public const PAGE_LABELS = [
        'home' => 'Home',
        'packages' => 'Packages',
        'destinations' => 'Destinations',
        'blog' => 'Blog',
        'about' => 'About',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'book-now' => 'Book Now',
        'search' => 'Search',
        'login' => 'Staff login',
        'admin' => 'Admin',
    ];

    /**
     * @var list<string>
     */
    public const CORNERS = [
        'bottom-right',
        'bottom-left',
        'top-right',
        'top-left',
    ];

    /**
     * @var array<string, string>
     */
    public const CORNER_LABELS = [
        'bottom-right' => 'Bottom right',
        'bottom-left' => 'Bottom left',
        'top-right' => 'Top right',
        'top-left' => 'Top left',
    ];

    public const POPUP_TITLE = 'Start a conversation';

    public const POPUP_DESCRIPTION = 'Click a team member below to chat on WhatsApp.';

    public const POPUP_NOTE = 'The team typically replies in a few minutes.';

    public const OFFICE_NAME = 'Tanzania Safaris and Trekking';

    public const OFFICE_TITLE = 'Safari desk';

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'enabled' => true,
        'corner' => 'bottom-right',
        'offset_x' => 24,
        'offset_y' => 24,
        'icon_width' => 56,
        'icon_height' => 56,
        'icon_color' => self::BRAND_COLOR,
        'show_on_all_pages' => true,
    ];

    protected $fillable = [
        'phone_number',
        'enabled',
        'message',
        'popup_title',
        'popup_description',
        'popup_note',
        'corner',
        'offset_x',
        'offset_y',
        'icon_width',
        'icon_height',
        'icon_color',
        'show_on_all_pages',
        'pages',
    ];

    public static function current(): self
    {
        if (! Schema::hasTable('whatsapp_settings')) {
            $setting = new static(static::defaults());
            $setting->setRelation('contacts', new Collection);

            return $setting;
        }

        $setting = static::query()->with('contacts')->first();

        if ($setting instanceof self) {
            return $setting;
        }

        $setting = new static(static::defaults());
        $setting->setRelation('contacts', new Collection);

        return $setting;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'phone_number' => null,
            'enabled' => true,
            'message' => null,
            'popup_title' => null,
            'popup_description' => null,
            'popup_note' => null,
            'corner' => 'bottom-right',
            'offset_x' => 24,
            'offset_y' => 24,
            'icon_width' => 56,
            'icon_height' => 56,
            'icon_color' => self::BRAND_COLOR,
            'show_on_all_pages' => true,
            'pages' => array_keys(self::PAGE_LABELS),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'show_on_all_pages' => 'boolean',
            'offset_x' => 'integer',
            'offset_y' => 'integer',
            'icon_width' => 'integer',
            'icon_height' => 'integer',
            'pages' => 'array',
        ];
    }

    /**
     * @return HasMany<WhatsappContact, $this>
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(WhatsappContact::class)->orderBy('sort_order')->orderBy('id');
    }

    public static function pageKey(?string $routeName = null): string
    {
        $routeName ??= Route::currentRouteName() ?? '';

        if (in_array($routeName, ['admin.login', 'admin.login.store'], true)) {
            return 'login';
        }

        if (str_starts_with($routeName, 'admin.')) {
            return 'admin';
        }

        return match ($routeName) {
            'home' => 'home',
            'packages.index', 'packages.safari', 'packages.trekking', 'packages.day-trips', 'packages.cultural', 'packages.show' => 'packages',
            'destinations.index', 'destinations.show' => 'destinations',
            'blog', 'blog.show' => 'blog',
            'about' => 'about',
            'contact', 'contact.store' => 'contact',
            'faq' => 'faq',
            'book-now', 'book-now.store' => 'book-now',
            'search.index', 'search.suggest' => 'search',
            default => '',
        };
    }

    public function visibleOn(string $page): bool
    {
        if (! $this->enabled || ! $this->hasAudience()) {
            return false;
        }

        if (! array_key_exists($page, self::PAGE_LABELS)) {
            return false;
        }

        if ($this->show_on_all_pages) {
            return true;
        }

        return in_array($page, $this->pages ?? [], true);
    }

    public function hasAudience(): bool
    {
        if ($this->digits() !== '') {
            return true;
        }

        return $this->contacts->contains(
            fn (WhatsappContact $contact): bool => $contact->digits() !== '',
        );
    }

    public function digits(): string
    {
        return preg_replace('/\D+/', '', (string) $this->phone_number) ?? '';
    }

    public function iconColor(): string
    {
        $color = strtolower((string) $this->icon_color);

        return preg_match('/^#[0-9a-f]{6}$/', $color) === 1 ? $color : self::BRAND_COLOR;
    }

    public function popupTitle(): string
    {
        return filled($this->popup_title) ? (string) $this->popup_title : self::POPUP_TITLE;
    }

    public function popupDescription(): string
    {
        return filled($this->popup_description) ? (string) $this->popup_description : self::POPUP_DESCRIPTION;
    }

    public function popupNote(): string
    {
        return filled($this->popup_note) ? (string) $this->popup_note : self::POPUP_NOTE;
    }

    /**
     * @return list<array{name: string, title: string, section: string|null, photo_url: string|null, initials: string, chat_url: string}>
     */
    public function popupEntries(): array
    {
        $message = trim((string) $this->message);
        $contacts = $this->contacts->filter(
            fn (WhatsappContact $contact): bool => $contact->digits() !== '',
        );

        if ($contacts->isNotEmpty()) {
            return $contacts
                ->map(fn (WhatsappContact $contact): array => $contact->popupEntry($message))
                ->values()
                ->all();
        }

        $url = $this->chatUrl();

        if ($url === null) {
            return [];
        }

        return [[
            'name' => self::OFFICE_NAME,
            'title' => self::OFFICE_TITLE,
            'section' => null,
            'photo_url' => null,
            'initials' => 'TS',
            'chat_url' => $url,
        ]];
    }

    public static function rejectShortPhone(mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D+/', '', (string) $value) ?? '';

        if (strlen($digits) < 8 || strlen($digits) > 15) {
            $fail('Enter a WhatsApp number with 8 to 15 digits.');
        }
    }

    public function chatUrl(): ?string
    {
        $digits = $this->digits();

        if ($digits === '') {
            return null;
        }

        $url = 'https://wa.me/'.$digits;
        $message = trim((string) $this->message);

        if ($message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return null;
        }

        $hasPlus = str_starts_with($phone, '+');
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return null;
        }

        return ($hasPlus ? '+' : '').$digits;
    }
}
