<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\CategoryService;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\FakeCategoryRepository;
use Tests\Fixtures\FakePostRepository;

class CategoryServiceTest extends TestCase
{
    private FakePostRepository $postRepository;

    private CategoryService $service;

    protected function setUp(): void
    {
        $this->postRepository = new FakePostRepository();
        $this->service = new CategoryService(
            new FakeCategoryRepository(),
            $this->postRepository
        );
    }

    public function testCategoryPageReturnsNullWhenCategoryDoesNotExist(): void
    {
        self::assertNull($this->service->getCategoryPage(999, 'date', 1));
    }

    public function testCategoryPageNormalizesSortAndClampsPage(): void
    {
        $page = $this->service->getCategoryPage(1, 'wrong-sort', 10);

        self::assertNotNull($page);

        $data = $page->toArray();

        self::assertSame('date', $data['sort']);
        self::assertSame(3, $data['page']);
        self::assertSame(3, $data['totalPages']);
        self::assertSame(14, $data['totalPosts']);
        self::assertSame([
            'categoryId' => 1,
            'sort' => 'date',
            'limit' => 6,
            'offset' => 12,
        ], $this->postRepository->lastCategoryQuery);
    }
}
