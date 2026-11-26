<?php

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets an admin create an faq that appears on the public faq page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.faqs.store'), [
            'question' => 'Do you arrange airport transfers?',
            'answer' => 'Yes, airport transfers can be arranged on request.',
            'sort_order' => 1,
            'is_published' => true,
        ])
        ->assertRedirect();

    expect(Faq::query()->where('question', 'Do you arrange airport transfers?')->exists())->toBeTrue();

    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('Do you arrange airport transfers?', false)
        ->assertSee('airport transfers can be arranged', false);
});
