# Vanilla — WSC2026

A starting point for competitors who want to build **without a framework**, in plain PHP,
plain JavaScript, or both (WorldSkills 2026 Web Technologies, TP17).

Two entry points, one small task-list app, so you can see each stack working before you
delete the demo and write your own:

- **`index.php`** — rendered on the server. No JavaScript involved.
- **`index.html` + `scripts.js`** — rendered in the browser, fetching `api/tasks.php`.

Both read the same MySQL database through **`config/db.php`**.

## Run it

```bash
# edit .env with your credentials first
docker compose up --build
```

- **http://localhost** → the PHP page
- **http://localhost/index.html** → the JavaScript page

Stop with `docker compose down`.

## How to use it

1. Building with plain PHP? Start from **`index.php`**.
2. Building with plain JavaScript? Start from **`index.html`** and **`scripts.js`**.
3. Either way, put database configuration in **`config/db.php`**.

Delete whichever half you don't need. There is no build step, no package manager, and no
dependencies — edit a file, reload the page.

## Files

| File | What it is |
|------|------------|
| `index.php` | Vanilla PHP entry point — server-rendered page |
| `index.html` | Vanilla JavaScript entry point — static markup |
| `scripts.js` | Fetches and renders the task list, handles the add form |
| `api/tasks.php` | JSON API: `GET` lists tasks, `POST` adds one |
| `config/db.php` | PDO connection + schema/seed helpers |
| `styles.css` | Shared styling for both pages |
| `.env` | Your MySQL credentials — edit this before running |

## Database

**MySQL**, hosted at `db.sitc.skillsit.eu:3306`. Each competitor has their own username,
password and database. Edit **`.env`** at the project root:

| Variable | Value |
|----------|-------|
| `DB_HOST` | `db.sitc.skillsit.eu` |
| `DB_PORT` | `3306` |
| `DB_USER` | your username, e.g. `c42` |
| `DB_PASS` | your password |
| `DB_NAME` | your database, e.g. `c42_module-a` |

`config/db.php` loads `.env` and opens the PDO connection. The schema is created and
seeded on container start by `docker-entrypoint.sh`; if the server is unreachable, Apache
still starts and the pages show the connection error.

**`.env` is committed on purpose.** The competition runs offline and the CI pipelines
deploy the bare image to Kubernetes/pulldeck without setting any environment variables, so
the credentials have to travel with the code. Two consequences worth knowing:

- The Dockerfile moves `.env` to `/var/www/.env`, **outside the document root**, and Apache
  denies all dotfiles — so `http://your-host/.env` returns 403, not your password.
- A real environment variable still overrides the file, so compose or a k8s manifest can
  change any value without a rebuild.

> The database name contains a **hyphen**, so it must be wrapped in backticks anywhere it
> appears in raw SQL (`` USE `c42_module-a` ``). It does *not* need quoting in the DSN.

## Stack

- PHP 8.3 (Apache, `pdo_mysql`)
- MySQL (remote, competition-provided)
- No framework, no build step, no dependencies
