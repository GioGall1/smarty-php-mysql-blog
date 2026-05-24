<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Dto\CategoryPageDto;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;

class CategoryService
{
    private const POSTS_PER_PAGE = 6;

    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private PostRepositoryInterface $postRepository
    ) {
    }

    public static function create(): self
    {
        $db = Database::getConnection();

        return new self(
            new CategoryRepository($db),
            new PostRepository($db)
        );
    }

    public function getCategoryPage(int $categoryId, ?string $sort, ?int $page): ?CategoryPageDto
    {
        $category = $this->categoryRepository->findById($categoryId);

        if ($category === null) {
            return null;
        }

        $sort = $this->normalizeSort($sort);
        $page = $this->normalizePage($page);
        $totalPosts = $this->postRepository->countByCategory($categoryId);
        $totalPages = max(1, (int) ceil($totalPosts / self::POSTS_PER_PAGE));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $posts = $this->postRepository->getByCategory(
            $categoryId,
            $sort,
            self::POSTS_PER_PAGE,
            ($page - 1) * self::POSTS_PER_PAGE
        );

        return new CategoryPageDto($category, $posts, $sort, $page, $totalPages, $totalPosts);
    }

    private function normalizeSort(?string $sort): string
    {
        return in_array($sort, ['date', 'views'], true) ? $sort : 'date';
    }

    private function normalizePage(?int $page): int
    {
        return $page !== null && $page > 0 ? $page : 1;
    }
}
