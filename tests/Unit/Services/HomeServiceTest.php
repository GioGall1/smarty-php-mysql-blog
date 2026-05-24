<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\HomeService;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\FakeCategoryRepository;
use Tests\Fixtures\FakePostRepository;

class HomeServiceTest extends TestCase
{
    public function testHomePageContainsCategoriesWithLatestPosts(): void
    {
        $service = new HomeService(
            new FakeCategoryRepository(),
            new FakePostRepository()
        );

        $page = $service->getHomePage();
        $data = $page->toArray();

        self::assertSame('PHP MySQL Smarty Blog', $data['pageTitle']);
        self::assertCount(2, $data['categories']);
        self::assertCount(3, $data['categories'][0]->posts);
        self::assertSame('Latest post A', $data['categories'][0]->posts[0]->title);
    }
}
