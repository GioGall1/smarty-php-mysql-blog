<?php

declare(strict_types=1);

use App\Core\Database;

require_once __DIR__ . '/../vendor/autoload.php';

$db = Database::getConnection();

$db->exec('SET FOREIGN_KEY_CHECKS=0');
$db->exec('TRUNCATE TABLE category_post');
$db->exec('TRUNCATE TABLE posts');
$db->exec('TRUNCATE TABLE categories');
$db->exec('SET FOREIGN_KEY_CHECKS=1');

$categories = [
    [
        'title' => 'PHP',
        'description' => 'Articles about PHP, backend development and clean application structure.',
    ],
    [
        'title' => 'JavaScript',
        'description' => 'Frontend and JavaScript articles for modern web development.',
    ],
    [
        'title' => 'MySQL',
        'description' => 'Database design, SQL queries, indexes and data optimization.',
    ],
    [
        'title' => 'Web Development',
        'description' => 'General articles about building practical web applications.',
    ],
];

$categoryIds = [];

$categoryStatement = $db->prepare('
    INSERT INTO categories (title, description)
    VALUES (:title, :description)
');

foreach ($categories as $category) {
    $categoryStatement->execute([
        'title' => $category['title'],
        'description' => $category['description'],
    ]);

    $categoryIds[$category['title']] = (int) $db->lastInsertId();
}

$postStatement = $db->prepare('
    INSERT INTO posts (image, title, description, content, views_count, published_at)
    VALUES (:image, :title, :description, :content, :views_count, :published_at)
');

$relationStatement = $db->prepare('
    INSERT INTO category_post (category_id, post_id)
    VALUES (:category_id, :post_id)
');

$posts = [
    [
        'title' => 'Getting Started with Pure PHP',
        'categories' => ['PHP', 'Web Development'],
    ],
    [
        'title' => 'Simple Routing Without a Framework',
        'categories' => ['PHP'],
    ],
    [
        'title' => 'Using PDO for Database Access',
        'categories' => ['PHP', 'MySQL'],
    ],
    [
        'title' => 'Building Clean PHP Controllers',
        'categories' => ['PHP', 'Web Development'],
    ],
    [
        'title' => 'Understanding Many-to-Many Relations',
        'categories' => ['MySQL'],
    ],
    [
        'title' => 'Indexes and Query Performance',
        'categories' => ['MySQL'],
    ],
    [
        'title' => 'JavaScript Basics for PHP Developers',
        'categories' => ['JavaScript', 'Web Development'],
    ],
    [
        'title' => 'Improving User Experience with Small UI Details',
        'categories' => ['JavaScript', 'Web Development'],
    ],
    [
        'title' => 'How to Structure a Small Web Project',
        'categories' => ['Web Development'],
    ],
    [
        'title' => 'Pagination in Web Applications',
        'categories' => ['PHP', 'MySQL', 'Web Development'],
    ],
    [
        'title' => 'Sorting Data Safely',
        'categories' => ['PHP', 'MySQL'],
    ],
    [
        'title' => 'Smarty Templates in a PHP Project',
        'categories' => ['PHP', 'Web Development'],
    ],
];

foreach ($posts as $index => $post) {
    $publishedAt = (new DateTimeImmutable())
        ->modify(sprintf('-%d days', $index))
        ->format('Y-m-d H:i:s');

    $postStatement->execute([
        'image' => '/assets/images/post-placeholder.svg',
        'title' => $post['title'],
        'description' => 'Short description for "' . $post['title'] . '".',
        'content' => str_repeat(
            'This is demo article content for "' . $post['title'] . '". ',
            12
        ),
        'views_count' => 50 + ($index * 17),
        'published_at' => $publishedAt,
    ]);

    $postId = (int) $db->lastInsertId();

    foreach ($post['categories'] as $categoryTitle) {
        $relationStatement->execute([
            'category_id' => $categoryIds[$categoryTitle],
            'post_id' => $postId,
        ]);
    }
}

echo "Database seeded successfully.\n";
