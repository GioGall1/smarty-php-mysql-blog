<?php

declare(strict_types=1);

namespace App\Dto;

class PostDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $image,
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $content,
        public readonly int $viewsCount,
        public readonly string $publishedAt,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null
    ) {
    }

    public function withIncrementedViews(): self
    {
        return new self(
            $this->id,
            $this->image,
            $this->title,
            $this->description,
            $this->content,
            $this->viewsCount + 1,
            $this->publishedAt,
            $this->createdAt,
            $this->updatedAt
        );
    }
}
