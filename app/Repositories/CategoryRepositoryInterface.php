<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CategoryDto;

interface CategoryRepositoryInterface
{
    /**
     * @return CategoryDto[]
     */
    public function getAllWithPosts(): array;

    public function findById(int $id): ?CategoryDto;
}
