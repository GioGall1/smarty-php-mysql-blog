<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CategoryDto;
use PDO;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function getAllWithPosts(): array
    {
        $sql = '
            SELECT c.id, c.title, c.description, COUNT(cp.post_id) AS posts_count
            FROM categories c
            INNER JOIN category_post cp ON cp.category_id = c.id
            GROUP BY c.id, c.title, c.description
            HAVING posts_count > 0
            ORDER BY c.title ASC
        ';

        return array_map(
            $this->mapCategory(...),
            $this->db->query($sql)->fetchAll()
        );
    }

    public function findById(int $id): ?CategoryDto
    {
        $sql = '
            SELECT id, title, description, created_at, updated_at
            FROM categories
            WHERE id = :id
            LIMIT 1
        ';

        $statement = $this->db->prepare($sql);
        $statement->execute(['id' => $id]);

        $category = $statement->fetch();

        return $category ? $this->mapCategory($category) : null;
    }

    private function mapCategory(array $row): CategoryDto
    {
        return new CategoryDto(
            (int) $row['id'],
            $row['title'],
            $row['description'],
            isset($row['posts_count']) ? (int) $row['posts_count'] : null,
            [],
            $row['created_at'] ?? null,
            $row['updated_at'] ?? null
        );
    }
}
