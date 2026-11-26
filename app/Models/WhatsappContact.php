<?php

namespace App\Models;

use Database\Factories\WhatsappContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WhatsappContact extends Model
{
    /** @use HasFactory<WhatsappContactFactory> */
    use HasFactory;

    protected $fillable = [
        'whatsapp_setting_id',
        'section',
        'name',
        'title',
        'phone_number',
        'photo_path',
        'sort_order',
    ];

    /**
     * @return BelongsTo<WhatsappSetting, $this>
     */
    public function setting(): BelongsTo
    {
        return $this->belongsTo(WhatsappSetting::class, 'whatsapp_setting_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (WhatsappContact $contact): void {
            $contact->deletePhoto();
        });
    }

    public function digits(): string
    {
        return preg_replace('/\D+/', '', (string) $this->phone_number) ?? '';
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/u', trim($this->name)) ?: [];
        $letters = '';

        foreach (array_slice($parts, 0, 2) as $part) {
            $letters .= mb_substr((string) $part, 0, 1);
        }

        return mb_strtoupper($letters !== '' ? $letters : 'W');
    }

    public function photoUrl(): ?string
    {
        if (! filled($this->photo_path) || ! Storage::disk('public')->exists($this->photo_path)) {
            return null;
        }

        return Storage::disk('public')->url($this->photo_path);
    }

    public function chatUrl(?string $message = null): string
    {
        $url = 'https://wa.me/'.$this->digits();
        $message = trim((string) $message);

        if ($message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    /**
     * @return array{name: string, title: string, section: string|null, photo_url: string|null, initials: string, chat_url: string}
     */
    public function popupEntry(?string $message = null): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'section' => filled($this->section) ? $this->section : null,
            'photo_url' => $this->photoUrl(),
            'initials' => $this->initials(),
            'chat_url' => $this->chatUrl($message),
        ];
    }

    public function deletePhoto(): void
    {
        if (! filled($this->photo_path)) {
            return;
        }

        if (Storage::disk('public')->exists($this->photo_path)) {
            Storage::disk('public')->delete($this->photo_path);
        }
    }
}
