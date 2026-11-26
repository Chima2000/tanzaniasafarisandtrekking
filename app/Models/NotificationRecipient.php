<?php

namespace App\Models;

use Database\Factories\NotificationRecipientFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRecipient extends Model
{
    /** @use HasFactory<NotificationRecipientFactory> */
    use HasFactory;

    public const TYPE_BOOKING = 'booking';

    public const TYPE_CONTACT = 'contact';

    /**
     * @var array<string, string>
     */
    public const TYPE_LABELS = [
        self::TYPE_BOOKING => 'Booking requests',
        self::TYPE_CONTACT => 'Contact / enquiry messages',
    ];

    /**
     * @var list<string>
     */
    public const TYPES = [
        self::TYPE_BOOKING,
        self::TYPE_CONTACT,
    ];

    protected $fillable = [
        'name',
        'email',
        'notification_types',
        'is_active',
        'sort_order',
    ];

    protected $attributes = [
        'is_active' => true,
        'sort_order' => 0,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'notification_types' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param  list<string>|null  $types
     * @return list<string>
     */
    public static function normalizeTypes(?array $types): array
    {
        $types = array_values(array_intersect(self::TYPES, $types ?? []));

        sort($types);

        return $types;
    }

    public function receives(string $type): bool
    {
        return in_array($type, $this->notification_types ?? [], true);
    }

    public function displayName(): string
    {
        return filled($this->name) ? (string) $this->name : (string) $this->email;
    }

    /**
     * @return list<string>
     */
    public function typeLabels(): array
    {
        return collect($this->notification_types ?? [])
            ->map(fn (string $type): string => self::TYPE_LABELS[$type] ?? $type)
            ->values()
            ->all();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->whereJsonContains('notification_types', $type);
    }
}
