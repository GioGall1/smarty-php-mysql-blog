<?php

declare(strict_types=1);

namespace App\Dto;

class HomePageDto implements PageDto
{
    public function __construct(
        public readonly string $pageTitle,
        public readonly array $categories
    ) {
    }

    public function toArray(): array
    {
        return [
            'pageTitle' => $this->pageTitle,
            'categories' => $this->categories,
        ];
    }
}
