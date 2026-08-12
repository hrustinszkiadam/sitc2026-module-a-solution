# Vanilla PHP + JavaScript — WSC2026 minimal app

```bash
# edit .env with your MySQL credentials first
docker compose up --build
```

- **http://localhost** — the vanilla PHP page (`index.php`, server-rendered)
- **http://localhost/index.html** — the vanilla JavaScript page (`scripts.js` + fetch)

JSON API: `GET api/tasks.php`, `POST api/tasks.php` (`{ "title": "..." }`).

Apache serves both pages plus the JS and CSS from one document root. `DirectoryIndex` is
set to `index.php index.html` so `/` lands on the PHP page; each page links to the other.

Tasks live in **MySQL** on `db.sitc.skillsit.eu:3306`. Credentials — `DB_HOST`, `DB_PORT`,
`DB_NAME`, `DB_USER`, `DB_PASS` — come from `.env`, which is committed and baked into the
image because the deploy sets no environment variables. The Dockerfile moves it to
`/var/www/.env`, outside the document root, and Apache denies dotfiles, so it can't be
fetched over HTTP. A real environment variable overrides the file.

The entrypoint creates and seeds the schema before Apache starts; if the server is
unreachable it starts anyway and the pages report the error.

Pinned: PHP 8.3 (Apache, `pdo_mysql`). No package manager, no build step, no dependencies.
