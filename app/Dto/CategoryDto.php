<?php

declare(strict_types=1);

namespace App\Dto;

class CategoryDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly ?int $postsCount = null,
        public readonly array $posts = [],
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    ) {
    }

    public function withPosts(array $posts): self
    {
        return new self(
            $this->id,
            $this->title,
            $this->description,
            $this->postsCount,
            $posts,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
