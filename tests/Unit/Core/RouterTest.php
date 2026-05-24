<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\Router;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\TestController;

class RouterTest extends TestCase
{
    protected function setUp(): void
    {
        TestController::$handledId = null;
        TestController::$handledSlug = null;
        http_response_code(200);
    }

    public function testDispatchesRouteWithIntegerParameter(): void
    {
        $router = new Router();
        $router->get('/post/{id}', [TestController::class, 'show']);

        $router->dispatch('/post/15', 'GET');

        self::assertSame(15, TestController::$handledId);
    }

    public function testDispatchIgnoresQueryString(): void
    {
        $router = new Router();
        $router->get('/category/{slug}', [TestController::class, 'category']);

        $router->dispatch('/category/php?sort=views&page=2', 'GET');

        self::assertSame('php', TestController::$handledSlug);
    }

    public function testDispatchHandlesHeadRequestsAsGetRoutes(): void
    {
        $router = new Router();
        $router->get('/post/{id}', [TestController::class, 'show']);

        $router->dispatch('/post/15', 'HEAD');

        self::assertSame(15, TestController::$handledId);
    }

    public function testDispatchReturnsNotFoundForUnknownRoute(): void
    {
        $router = new Router();

        ob_start();
        $router->dispatch('/missing-page', 'GET');
        $output = ob_get_clean();

        self::assertSame(404, http_response_code());
        self::assertSame('404 Not Found', $output);
    }

    public function testDispatchReturnsNotFoundForInvalidIntegerParameter(): void
    {
        $router = new Router();
        $router->get('/post/{id}', [TestController::class, 'show']);

        ob_start();
        $router->dispatch('/post/not-a-number', 'GET');
        $output = ob_get_clean();

        self::assertSame(404, http_response_code());
        self::assertSame('404 Not Found', $output);
        self::assertNull(TestController::$handledId);
    }
}
