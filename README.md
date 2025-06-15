# Eheca

Eheca is a small-footprint web application skeleton for rapid micro-development tasks and portfolio sites.  
Built with **Slim 4** and **Blade** templating, it ships with modern styling via **Tailwind CSS** and interactivity from **Alpine.js**.  
Out of the box you get a contact form wired to Brevo for email delivery (SMS optional), sensible middleware, and a clean structure for adding new pages and APIs.

---

## ✨ Features

* **Slim 4** micro-framework — fast, PSR-7/15 compliant routing & middleware
* **Blade** as the view engine for expressive, reusable templates
* **Tailwind CSS 3** & **Alpine.js 3** for responsive, minimal-JS UI
* Contact form with **Brevo** (Sendinblue) email notifications and optional SMS hook
* CSRF protection & simple IP-based rate-limiting middleware
* Pre-built pages: Home, Services, Examples, FAQ, Contact
* `/favicon.ico` helper route to keep logs clean
* `/api/status` JSON health-check endpoint for monitoring
* NPM/Tailwind & esbuild pipeline for instant asset builds
* Ready for Docker / CI-CD (GitHub Actions sample forthcoming)

---

## 📂 Project Structure

```
├── app/                  # PHP domain logic (Controllers, Middleware, Services)
├── public/               # Web root (index.php, compiled assets)
├── resources/            # Blade views, Tailwind CSS, Alpine JS
├── routes/               # Route definitions (web.php)
├── storage/              # Logs, cache (kept out of Git)
├── vendor/               # Composer dependencies (ignored)
└── ...
```

---

## 🚀 Quick Start (Development)

1. **Clone & install dependencies**

   ```bash
   git clone git@github.com:YOUR-USER/eheca.git
   cd eheca
   cp .env.example .env  # add your keys

   # PHP deps
   composer install

   # Front-end deps & dev build
   npm ci
   npm run development   # or `npm run watch` during active dev
   ```

2. **Serve locally**

   ```bash
   php -S 0.0.0.0:8000 -t public
   ```
   Visit <http://localhost:8000>.

---

## 🛠 Environment Variables

| Key                | Purpose                               |
|--------------------|---------------------------------------|
| `APP_ENV`          | `local` / `production`                |
| `APP_DEBUG`        | `true` or `false`                     |
| `BREVO_API_KEY`    | Brevo transactional email key         |
| `MAIL_FROM_NAME`   | Sender name for email notifications   |
| `MAIL_FROM_ADDRESS`| Sender email (verified in Brevo)      |
| `ADMIN_EMAIL`      | Default recipient for contact form    |
| _(DB_* vars)_      | Database creds (planned future step)  |

See `.env.example` for the full list.

---

## 🏗 Build Commands

| Command                | What it does                          |
|------------------------|---------------------------------------|
| `npm run development`  | One-off dev build (non-minified)      |
| `npm run watch`        | Rebuild on file changes               |
| `npm run production`   | Minified CSS/JS in `public/`          |

Back-end classes follow PSR-4 autoloading as defined in `composer.json`.

---

## 📦 Production Deployment

```bash
# Install only prod PHP deps & optimise autoloader
composer install --no-dev --optimize-autoloader --apcu-autoloader

# Build minified assets
npm ci
npm run production

# Configure web server root -> public/
# Ensure APP_ENV=production & APP_DEBUG=false
```

See `docs/deployment.md` (to-be-created) for Nginx sample.

---

## 🧪 Tests

Testing scaffolding is not yet added. Recommended stack: **PHPUnit** for unit tests and **Pest** for expressive syntax.

---

## 🤝 Contributing

1. Fork & create a feature branch.
2. Follow PSR-12 coding style (`vendor/bin/php-cs-fixer`).
3. Write tests for new behaviour.
4. Commit using Conventional Commits.
5. Open a pull request.

---

## 📝 License

This project is released under the MIT License. See `LICENSE` for details.
