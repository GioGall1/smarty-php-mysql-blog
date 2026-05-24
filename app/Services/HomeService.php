<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Dto\HomePageDto;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;

class HomeService
{
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

    public function getHomePage(): HomePageDto
    {
        $categories = $this->categoryRepository->getAllWithPosts();

        foreach ($categories as $index => $category) {
            $categories[$index] = $category->withPosts(
                $this->postRepository->getLatestByCategory($category->id)
            );
        }

        return new HomePageDto('PHP MySQL Smarty Blog', $categories);
    }
}
