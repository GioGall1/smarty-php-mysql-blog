<?php

declare(strict_types=1);

namespace App\Dto;

class PostPageDto implements PageDto
{
    public function __construct(
        public readonly PostDto $post,
        public readonly array $categories,
        public readonly array $relatedPosts
    ) {
    }

    public function toArray(): array
    {
        return [
            'pageTitle' => $this->post->title,
            'post' => $this->post,
            'categories' => $this->categories,
            'relatedPosts' => $this->relatedPosts,
        ];
    }
}
