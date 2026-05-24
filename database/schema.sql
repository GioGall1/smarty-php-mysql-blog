SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS category_post;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content TEXT NOT NULL,
    views_count INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_posts_published_at (published_at),
    INDEX idx_posts_views_count (views_count)
);

CREATE TABLE category_post (
    category_id INT UNSIGNED NOT NULL,
    post_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (category_id, post_id),
    INDEX idx_category_post_post_id (post_id),
    CONSTRAINT fk_category_post_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_category_post_post
        FOREIGN KEY (post_id) REFERENCES posts(id)
        ON DELETE CASCADE
);
