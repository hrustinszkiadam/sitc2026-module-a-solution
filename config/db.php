<?php
/**
 * Database configuration — MySQL.
 *
 * Connection details live in `.env` at the project root. That file is committed on
 * purpose: the competition runs offline, and the deployed image is started without any
 * environment variables, so the values have to travel with the code.
 *
 *   DB_HOST  db.sitc.skillsit.eu     DB_USER  your username, e.g. c42
 *   DB_PORT  3306                    DB_PASS  your password
 *   DB_NAME  c42_module-a
 *
 * A real environment variable always wins over the file, so `docker compose` or a
 * Kubernetes manifest can still override any value without editing `.env`.
 *
 * NOTE: the database name contains a hyphen, so it must be wrapped in backticks
 * anywhere it appears in raw SQL — `USE `c42_module-a`` — but NOT in the DSN below.
 */

/**
 * Read `.env` into the environment. Existing variables are never overwritten, so an
 * explicitly-set variable takes precedence. Safe to call repeatedly.
 */
function load_env(): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    // In the container the file sits outside the document root so it cannot be
    // downloaded over HTTP; locally it sits next to this project's files.
    foreach ([dirname(__DIR__) . '/.env', '/var/www/.env'] as $file) {
        if (!is_readable($file)) {
            continue;
        }

        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim(trim($value), "\"'");

            if ($key !== '' && getenv($key) === false) {
                putenv("$key=$value");
            }
        }

        return;
    }
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    load_env();

    $host = getenv('DB_HOST') ?: 'db.sitc.skillsit.eu';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: '';
    $user = getenv('DB_USER') ?: '';
    $pass = getenv('DB_PASS') ?: '';

    if ($name === '' || $user === '') {
        throw new RuntimeException('DB_NAME and DB_USER are not set — check .env at the project root.');
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 5,
    ]);

    return $pdo;
}

/** Create the schema and seed a few rows. Safe to call repeatedly. */
function db_init(): void
{
    $pdo = db();

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS tasks (
            id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            done  TINYINT(1)   NOT NULL DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
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
