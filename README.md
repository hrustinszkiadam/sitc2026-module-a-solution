# Module A — Mini Test Projects

Eight self-contained mini test projects: three design implementation tasks (HTML and CSS
only), three front-end tasks (HTML, CSS and JavaScript) and two back-end tasks (PHP).

Open `index.html` for the overview page that links to every mini test project.

## Structure

```text
/
├── index.html      overview page with a thumbnail and a title for every mini project
├── thumbnails/     the images used by the overview page
├── assets/         the media files and starter code as they were provided
├── A1/             Floating Label                 (L1, HTML + CSS)
├── A2/             Progress Animation             (L1, HTML + CSS)
├── A3/             3D Can Rotation Effect         (L2, HTML + CSS)
├── B1/             World's Tallest Buildings      (L1, HTML + CSS + JS)
├── B2/             Turntable                      (L3, HTML + CSS + JS)
├── B3/             Lines and Dots Animation       (L1, HTML + CSS + JS)
├── C1/             Registration Form Validation   (L1, PHP)
└── C2/             API Request Logger             (L2, PHP)
```

`assets/` holds the provided material only (reference videos, images, audio and the
starter HTML/CSS files). The solutions are in the folders named after the mini project,
each one with its own copy of the media it needs.

## Running

The six front-end mini projects are static, open their `index.html` in Google Chrome.

The two back-end mini projects run on the PHP built-in web server, started from the folder
of the mini project:

```shell
cd C1        # or C2
php -S localhost:8080
```

- **C1** → <http://localhost:8080/index.php> — the form is validated in PHP.
- **C2** → `POST http://localhost:8080/api/log` with `Content-Type: application/json`; the
  requests are written to `C2/log/HH-MM-SS-request.txt`. <http://localhost:8080/> is a
  small test page that sends such a request.

Both folders contain a `README.txt` with the exact URL and examples.

To browse everything from one server instead, start it in the repository root
(`php -S localhost:8080`) and open <http://localhost:8080/>.

## Notes on the implementations

- **A2** uses a CSS scroll timeline (`animation-timeline: scroll(root block)`), so the bar
  follows the scroll position without any JavaScript.
- **A3** repeats the label image along the can and scrolls it by exactly one tile per turn,
  which is what makes the can look as if it were rotating.
- **B1** sorts the data by height, then sets only the height of each image so the aspect
  ratio is kept; the tallest building is 800px.
- **B2** keeps the audio in one `Audio` object and switches its `src` for the next track.
- **C2** is served by a single `index.php`. The PHP built-in server hands it every URL
  that is not a real file, so the endpoint is `/api/log` without a `.php` extension and
  without a router script; it answers on `/C2/api/log` too when the server runs in the
  root.
