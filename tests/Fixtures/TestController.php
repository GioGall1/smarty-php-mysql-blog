<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Core\Controller;

class TestController extends Controller
{
    public static ?int $handledId = null;

    public static ?string $handledSlug = null;

    public function show(int $id): void
    {
        self::$handledId = $id;
    }

    public function category(string $slug): void
    {
        self::$handledSlug = $slug;
    }
}
