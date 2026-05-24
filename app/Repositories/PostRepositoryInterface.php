<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CategoryDto;
use App\Dto\PostDto;

interface PostRepositoryInterface
{
    /**
     * @return PostDto[]
     */
    public function getLatestByCategory(int $categoryId, int $limit = 3): array;

    /**
     * @return PostDto[]
     */
    public function getByCategory(
        int $categoryId,
        string $sort,
        int $limit,
        int $offset
    ): array;

    public function countByCategory(int $categoryId): int;

    public function findById(int $id): ?PostDto;

    /**
     * @return CategoryDto[]
     */
    public function getCategories(int $postId): array;

    public function incrementViews(int $postId): void;

    /**
     * @return PostDto[]
     */
    public function getRelated(int $postId, int $limit = 3): array;
}
