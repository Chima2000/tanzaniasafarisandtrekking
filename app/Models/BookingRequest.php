<?php

namespace App\Models;

use Database\Factories\BookingRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    /** @use HasFactory<BookingRequestFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'package_slug',
        'package_name',
        'interest',
        'budget',
        'duration_days',
        'destinations',
        'adults',
        'children',
        'accommodation',
        'travel_date',
        'notes',
        'payload',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'destinations' => 'array',
            'payload' => 'array',
            'travel_date' => 'date',
            'duration_days' => 'integer',
            'adults' => 'integer',
            'children' => 'integer',
        ];
    }
}
