<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Dto\CategoryDto;
use App\Dto\PostDto;
use App\Repositories\PostRepositoryInterface;

class FakePostRepository implements PostRepositoryInterface
{
    public ?int $incrementedPostId = null;

    /**
     * @var array<string, int|string>|null
     */
    public ?array $lastCategoryQuery = null;

    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        return array_slice([
            $this->makePost($categoryId * 10 + 1, 'Latest post A'),
            $this->makePost($categoryId * 10 + 2, 'Latest post B'),
            $this->makePost($categoryId * 10 + 3, 'Latest post C'),
            $this->makePost($categoryId * 10 + 4, 'Latest post D'),
        ], 0, $limit);
    }

    public function getByCategory(
        int $categoryId,
        string $sort,
        int $limit,
        int $offset
    ): array {
        $this->lastCategoryQuery = [
            'categoryId' => $categoryId,
            'sort' => $sort,
            'limit' => $limit,
            'offset' => $offset,
        ];

        return [
            $this->makePost(101, 'Category post'),
        ];
    }

    public function countByCategory(int $categoryId): int
    {
        return $categoryId === 1 ? 14 : 0;
    }

    public function findById(int $id): ?PostDto
    {
        if ($id !== 1) {
            return null;
        }

        return $this->makePost(
            1,
            'Post title',
            'Post content',
            '2026-05-20 10:00:00',
            '2026-05-20 10:00:00'
        );
    }

    public function getCategories(int $postId): array
    {
        return [
            new CategoryDto(1, 'PHP', 'PHP articles'),
        ];
    }

    public function incrementViews(int $postId): void
    {
        $this->incrementedPostId = $postId;
    }

    public function getRelated(int $postId, int $limit = 3): array
    {
        return array_slice([
            $this->makePost(2, 'Related post A'),
            $this->makePost(3, 'Related post B'),
            $this->makePost(4, 'Related post C'),
            $this->makePost(5, 'Related post D'),
        ], 0, $limit);
    }

    private function makePost(
        int $id,
        string $title,
        ?string $content = null,
        ?string $createdAt = null,
        ?string $updatedAt = null
    ): PostDto
    {
        return new PostDto(
            $id,
            '/assets/images/post-placeholder.svg',
            $title,
            'Post description',
            $content,
            9,
            '2026-05-20 10:00:00',
            $createdAt,
            $updatedAt
        );
    }
}
