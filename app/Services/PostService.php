<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Dto\PostPageDto;
use App\Repositories\PostRepository;
use App\Repositories\PostRepositoryInterface;

class PostService
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {
    }

    public static function create(): self
    {
        return new self(
            new PostRepository(Database::getConnection())
        );
    }

    public function getPostPage(int $postId): ?PostPageDto
    {
        $post = $this->postRepository->findById($postId);

        if ($post === null) {
            return null;
        }

        $this->postRepository->incrementViews($postId);

        return new PostPageDto(
            $post->withIncrementedViews(),
            $this->postRepository->getCategories($postId),
            $this->postRepository->getRelated($postId)
        );
    }
}
