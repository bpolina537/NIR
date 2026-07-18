<?php

declare(strict_types=1);

require_once __DIR__ . '/store-products.php';

function database(): PDO
{
    static $connection = null;

    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: 'db';
    $name = getenv('DB_NAME') ?: 'nir';
    $user = getenv('DB_USER') ?: 'nir_user';
    $password = getenv('DB_PASSWORD') ?: 'nir_password';

    $connection = new PDO(
        "mysql:host={$host};dbname={$name};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    initializeDatabase($connection);

    return $connection;
}

function initializeDatabase(PDO $connection): void
{
    static $initialized = false;
    if ($initialized) return;

    $connection->exec(
        'CREATE TABLE IF NOT EXISTS page_hits (
            page_key VARCHAR(100) PRIMARY KEY,
            hits INT UNSIGNED NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $connection->exec(
        'CREATE TABLE IF NOT EXISTS guestbook_messages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            author VARCHAR(120) NOT NULL,
            message TEXT NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $connection->exec(
        'CREATE TABLE IF NOT EXISTS products (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(180) NOT NULL,
            price DECIMAL(10,2) UNSIGNED NOT NULL,
            quantity INT UNSIGNED NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $count = (int) $connection->query('SELECT COUNT(*) FROM products')->fetchColumn();
    if ($count === 0) {
        $statement = $connection->prepare('INSERT INTO products (id, name, price, quantity) VALUES (?, ?, ?, ?)');
        foreach (storeProducts() as $product) {
            $statement->execute([$product['id'], $product['name'], $product['price'], $product['stock']]);
        }
    }

    $initialized = true;
}
