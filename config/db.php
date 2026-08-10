<?php
/**
 * Database configuration.
 *
 * A self-contained SQLite file — there is no database server to run or configure.
 * The file lives OUTSIDE the web root so it can never be downloaded over HTTP.
 *
 * Swap the DSN below if your project needs MySQL instead:
 *   $dsn = 'mysql:host=localhost;dbname=app;charset=utf8mb4';
 *   $pdo = new PDO($dsn, 'user', 'password', $options);
 */

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $path = getenv('DB_PATH') ?: '/var/www/data/app.db';

    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

/** Create the schema and seed a few rows. Safe to call repeatedly. */
function db_init(): void
{
    $pdo = db();

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS tasks (
            id    INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT    NOT NULL,
            done  INTEGER NOT NULL DEFAULT 0
        )'
    );

    if ((int) $pdo->query('SELECT COUNT(*) FROM tasks')->fetchColumn() === 0) {
        $insert = $pdo->prepare('INSERT INTO tasks (title, done) VALUES (?, ?)');
        $insert->execute(['Write plain PHP', 1]);
        $insert->execute(['Write plain JavaScript', 1]);
        $insert->execute(['Skip the framework', 0]);
    }
}

/** @return array<int, array{id:int, title:string, done:bool}> */
function all_tasks(): array
{
    $rows = db()->query('SELECT id, title, done FROM tasks ORDER BY id')->fetchAll();

    return array_map(static fn (array $r): array => [
        'id'    => (int) $r['id'],
        'title' => $r['title'],
        'done'  => (bool) $r['done'],
    ], $rows);
}

/** @return array{id:int, title:string, done:bool} */
function add_task(string $title): array
{
    $pdo = db();
    $pdo->prepare('INSERT INTO tasks (title) VALUES (?)')->execute([$title]);

    return ['id' => (int) $pdo->lastInsertId(), 'title' => $title, 'done' => false];
}
