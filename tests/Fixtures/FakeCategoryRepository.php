<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use App\Dto\CategoryDto;
use App\Repositories\CategoryRepositoryInterface;

class FakeCategoryRepository implements CategoryRepositoryInterface
{
    public array $categories;

    public function __construct()
    {
        $this->categories = [
            new CategoryDto(1, 'PHP', 'PHP articles', 14),
            new CategoryDto(2, 'MySQL', 'Database articles', 3),
        ];
    }

    public function getAllWithPosts(): array
    {
        return $this->categories;
    }

    public function findById(int $id): ?CategoryDto
    {
        foreach ($this->categories as $category) {
            if ($category->id === $id) {
                return $category;
            }
        }

        return null;
    }
}
