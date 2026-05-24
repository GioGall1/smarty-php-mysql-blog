<?php

declare(strict_types=1);

namespace App\Dto;

class CategoryPageDto implements PageDto
{
    public function __construct(
        public readonly CategoryDto $category,
        public readonly array $posts,
        public readonly string $sort,
        public readonly int $page,
        public readonly int $totalPages,
        public readonly int $totalPosts
    ) {
    }

    public function toArray(): array
    {
        return [
            'pageTitle' => $this->category->title,
            'category' => $this->category,
            'posts' => $this->posts,
            'sort' => $this->sort,
            'page' => $this->page,
            'totalPages' => $this->totalPages,
            'totalPosts' => $this->totalPosts,
        ];
    }
}
