<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\PostService;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\FakePostRepository;

class PostServiceTest extends TestCase
{
    private FakePostRepository $postRepository;

    private PostService $service;

    protected function setUp(): void
    {
        $this->postRepository = new FakePostRepository();
        $this->service = new PostService($this->postRepository);
    }

    public function testPostPageIncrementsViewsAndLoadsRelatedData(): void
    {
        $page = $this->service->getPostPage(1);

        self::assertNotNull($page);

        $data = $page->toArray();

        self::assertSame(1, $this->postRepository->incrementedPostId);
        self::assertSame(10, $data['post']->viewsCount);
        self::assertSame('PHP', $data['categories'][0]->title);
        self::assertCount(3, $data['relatedPosts']);
    }

    public function testPostPageReturnsNullWhenPostDoesNotExist(): void
    {
        self::assertNull($this->service->getPostPage(999));
        self::assertNull($this->postRepository->incrementedPostId);
    }
}
