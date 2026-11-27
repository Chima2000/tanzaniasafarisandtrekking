<?php

namespace Tests;

use App\Support\AdminPanelPath;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        File::delete(storage_path('app/'.AdminPanelPath::STORAGE_FILE));
    }
}
