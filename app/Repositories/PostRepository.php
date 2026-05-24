<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CategoryDto;
use App\Dto\PostDto;
use PDO;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function getLatestByCategory(int $categoryId, int $limit = 3): array
    {
        $sql = '
            SELECT p.id, p.image, p.title, p.description, p.views_count, p.published_at
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
            ORDER BY p.published_at DESC
            LIMIT :limit
        ';

        $statement = $this->db->prepare($sql);
        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $this->mapPosts($statement->fetchAll());
    }

    public function getByCategory(
        int $categoryId,
        string $sort,
        int $limit,
        int $offset
    ): array {
        $orderBy = $this->getOrderBy($sort);

        $sql = "
            SELECT p.id, p.image, p.title, p.description, p.views_count, p.published_at
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
            ORDER BY {$orderBy}
            LIMIT :limit OFFSET :offset
        ";

        $statement = $this->db->prepare($sql);
        $statement->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->bindValue('offset', $offset, PDO::PARAM_INT);
        $statement->execute();

        return $this->mapPosts($statement->fetchAll());
    }

    public function countByCategory(int $categoryId): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id = :category_id
        ';

        $statement = $this->db->prepare($sql);
        $statement->execute(['category_id' => $categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function findById(int $id): ?PostDto
    {
        $sql = '
            SELECT id, image, title, description, content, views_count, published_at, created_at, updated_at
            FROM posts
            WHERE id = :id
            LIMIT 1
        ';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        $post = $statement->fetch();

        return $post ? $this->mapPost($post) : null;
    }

    public function getCategories(int $postId): array
    {
        $sql = '
            SELECT c.id, c.title, c.description
            FROM categories c
            INNER JOIN category_post cp ON cp.category_id = c.id
            WHERE cp.post_id = :post_id
            ORDER BY c.title ASC
        ';

        $statement = $this->db->prepare($sql);
        $statement->execute(['post_id' => $postId]);

        return array_map(
            static fn (array $row): CategoryDto => new CategoryDto(
                (int) $row['id'],
                $row['title'],
                $row['description']
            ),
            $statement->fetchAll()
        );
    }

    public function incrementViews(int $postId): void
    {
        $sql = '
            UPDATE posts
            SET views_count = views_count + 1
            WHERE id = :id
        ';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $postId]);
    }

    public function getRelated(int $postId, int $limit = 3): array
    {
        $sql = '
            SELECT DISTINCT p.id, p.image, p.title, p.description, p.views_count, p.published_at
            FROM posts p
            INNER JOIN category_post cp ON cp.post_id = p.id
            WHERE cp.category_id IN (
                SELECT category_id
                FROM category_post
                WHERE post_id = :post_id
            )
            AND p.id != :excluded_post_id
            ORDER BY p.published_at DESC
            LIMIT :limit
        ';

        $statement = $this->db->prepare($sql);
        $statement->bindValue('post_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('excluded_post_id', $postId, PDO::PARAM_INT);
        $statement->bindValue('limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $this->mapPosts($statement->fetchAll());
    }

    private function getOrderBy(string $sort): string
    {
        return match ($sort) {
            'views' => 'p.views_count DESC, p.published_at DESC',
            'date' => 'p.published_at DESC',
            default => 'p.published_at DESC',
        };
    }

    private function mapPosts(array $rows): array
    {
        return array_map($this->mapPost(...), $rows);
    }

    private function mapPost(array $row): PostDto
    {
        return new PostDto(
            (int) $row['id'],
            $row['image'],
            $row['title'],
            $row['description'],
            $row['content'] ?? null,
            (int) $row['views_count'],
            $row['published_at'],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }
}
