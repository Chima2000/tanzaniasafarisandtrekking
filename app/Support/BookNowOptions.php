<?php

namespace App\Support;

class BookNowOptions
{
    /**
     * @return list<string>
     */
    public static function interests(): array
    {
        return [
            'Safari',
            'Kilimanjaro climb',
            'Safari + Zanzibar',
            'Climb + Safari',
            'Day trips & cultural',
            'Not sure yet',
        ];
    }

    /**
     * @return list<string>
     */
    public static function budgets(): array
    {
        return [
            '$1,500 – $2,500',
            '$2,500 – $4,000',
            '$4,000 – $5,500',
            '$5,500 – $8,000',
            '$8,000+',
            'Open / advise me',
        ];
    }

    public static function defaultBudget(): string
    {
        return self::budgets()[0];
    }

    /**
     * @return list<int>
     */
    public static function durations(): array
    {
        return [3, 5, 7, 8, 10, 12, 14, 16, 18, 21];
    }

    /**
     * @return list<array{value: string, label: string, blurb: string}>
     */
    public static function accommodations(): array
    {
        return [
            [
                'value' => 'Adventure / camping',
                'label' => 'Adventure',
                'blurb' => 'Tented camps and simple lodges, close to the bush.',
            ],
            [
                'value' => 'Comfort mid-range',
                'label' => 'Comfort',
                'blurb' => 'Reliable mid-range lodges with solid guiding.',
            ],
            [
                'value' => 'Premium / luxury',
                'label' => 'Premium',
                'blurb' => 'Quieter camps, finer dining, more space.',
            ],
            [
                'value' => 'Mix of levels',
                'label' => 'Mixed',
                'blurb' => 'Blend comfort and adventure nights across the route.',
            ],
            [
                'value' => 'Open / advise me',
                'label' => 'Advise me',
                'blurb' => 'We’ll match stays to budget, pace, and parks.',
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function accommodationValues(): array
    {
        return array_column(self::accommodations(), 'value');
    }
}
