# Jacario — Production Deployment Guide for Hostinger

This guide provides step-by-step instructions for deploying the **Jacario** Laravel application on **Hostinger Web Hosting, Cloud Hosting, and VPS** environments.

---

## 1. System Requirements & Architecture Overview

Jacario is a full-stack Laravel 13 e-commerce application powered by PHP, MySQL/MariaDB, Blade templating, Tailwind CSS, Alpine.js, and Vite asset bundling.

### Architecture Summary
* **Backend Framework:** Laravel 13.x (PHP `^8.3` required, PHP 8.3 or 8.4 recommended)
* **Frontend Pipeline:** Vite 8.x + Tailwind CSS v4 + Alpine.js
* **Asset Output Directory:** `public/build/` (Contains `manifest.json` and bundled CSS/JS)
* **Web Document Root:** `public/` (Entry point: `public/index.php`)
* **Database:** MySQL 8.0+ or MariaDB 10.3+

### Hostinger PHP Configuration
In Hostinger **hPanel** → **Websites** → **Manage** → **Advanced** → **PHP Configuration**:
* **PHP Version:** Set to **PHP 8.3** or **PHP 8.4**
* **Required PHP Extensions** (enabled by default on Hostinger):
  * `pdo`, `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`, `gd`, `zip`
* **PHP Settings Recommendations:**
  * `memory_limit = 256M` (or 512M)
  * `upload_max_filesize = 50M`
  * `post_max_size = 50M`
  * `max_execution_time = 300`

### Node.js Requirement
* **Node.js:** v18.x, v20.x, or v22.x LTS (compatible with Vite 8)
* **npm:** v10.x+

---

## 2. Resolving the Vite "Expected a dist directory" Mismatch

### Why the Error Occurred
When connecting a Git repository to Hostinger via certain automated deployment tools or static site builders, Hostinger defaults to expecting a standalone single-page application (SPA) output folder named `dist/` and runs `npm run build`.

However, Jacario is **not a static Vite SPA**; it is a complete Laravel application:
1. Laravel uses `@vite(['resources/css/app.css', 'resources/js/app.js'])` in Blade templates.
2. The official `laravel-vite-plugin` outputs bundled assets to `public/build/` and writes `public/build/manifest.json`.
3. Changing Vite's output directory to `dist` would break Laravel's `@vite` integration (triggering `Vite manifest not found at public/build/manifest.json`) and would fail to execute any PHP routes, database queries, or server-side controllers.

### The Correct Configuration
* **Do NOT change `vite.config.js` to output to `dist`**.
* In Hostinger's build settings (if using an automated Git builder asking for "Output Directory"):
  * Set the output directory to `public` or `public/build` (or leave empty if deploying as a standard PHP application).
* Deploy the entire repository as a **PHP / Laravel Web Application**, not a static website.

---

## 3. Hostinger Deployment Methods

Choose the deployment method that fits your Hostinger plan:
* **Method A (Recommended):** Hostinger SSH + Git Deployment
* **Method B:** Hostinger hPanel Git Tool
* **Method C:** Local Asset Build & Hostinger File Manager Upload

---

### Method A: SSH + Git Deployment (Recommended)

SSH is enabled on Hostinger Business, Cloud, and VPS hosting plans.

#### Step 1: Enable SSH Access on Hostinger
1. In Hostinger **hPanel**, navigate to **Advanced** → **SSH Access**.
2. Click **Enable** and copy the SSH command, port, username, and IP.
3. Connect from your local terminal:
   ```bash
   ssh -p [PORT] [USERNAME]@[HOST_IP]
   ```

#### Step 2: Clone Repository Outside `public_html`
Keep your application code, `.env`, and vendor dependencies **outside** the publicly accessible `public_html` folder for maximum security.

```bash
# Navigate to your home directory
cd ~

# Clone Jacario into a dedicated folder (e.g., jacario)
git clone https://github.com/intoxiagmb-crypto/Jacario.git jacario

# Enter the project directory
cd jacario
```

#### Step 3: Install Composer Dependencies
Run Composer in production mode:
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

#### Step 4: Build Frontend Assets
Compile production CSS and JavaScript with Vite:
```bash
npm ci
npm run build
```
*(Verify that `public/build/manifest.json` is generated).*

#### Step 5: Configure Document Root & Storage Link
Link Hostinger's web root (`public_html`) directly to Laravel's `public/` directory:

```bash
# Back up or remove the empty default public_html
cd ~
rm -rf public_html

# Create a symbolic link pointing public_html to jacario/public
ln -s /home/[USERNAME]/jacario/public /home/[USERNAME]/public_html

# Create the Laravel storage symlink
cd ~/jacario
php artisan storage:link
```

*(Note: Replace `[USERNAME]` with your actual Hostinger account username, e.g., `u123456789`).*

#### Step 6: Set Directory Permissions
```bash
chmod -R 775 ~/jacario/storage ~/jacario/bootstrap/cache
```

---

### Method B: Deploying Directly via Hostinger Git in hPanel

If deploying directly into `public_html` via hPanel's Git manager:

1. In **hPanel** → **Advanced** → **Git**:
   * **Repository:** `https://github.com/intoxiagmb-crypto/Jacario.git`
   * **Branch:** `main`
   * **Install Directory:** `/public_html` (or a dedicated folder)
   * Click **Create**.
2. **Built-in Security Safeguard:**
   Jacario includes a production root `.htaccess` in the repository that automatically:
   * Rewrites all web visitors directly into `public/`.
   * Forbids access (HTTP 403) to sensitive root files: `.env`, `composer.json`, `package.json`, `artisan`, `jacario.sql`, and internal directories (`app/`, `config/`, `storage/`, `vendor/`).
3. Connect via SSH to run:
   ```bash
   cd ~/public_html
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   ```

---

### Method C: Local Pre-Build & ZIP Upload (No SSH Required)

If your Hostinger plan does not have SSH/Node.js access:

#### Step 1: Run Locally on Your Computer
```bash
# 1. Install production Composer dependencies
composer install --no-dev --optimize-autoloader

# 2. Build Vite assets locally
npm install
npm run build

# 3. Create a deployment archive (excluding .git and local .env)
zip -r jacario-deploy.zip . -x ".git/*" ".env" "node_modules/*" "tests/*"
```

#### Step 2: Upload via Hostinger File Manager
1. Open **hPanel** → **Files** → **File Manager**.
2. Create a folder named `jacario` in your home root `/home/[USERNAME]/jacario/`.
3. Upload `jacario-deploy.zip` into `jacario/` and click **Extract**.
4. In File Manager, create a symlink or move the contents of `jacario/public/*` into `public_html`.
5. Upload your production `.env` into `/home/[USERNAME]/jacario/.env`.

---

## 4. Hostinger Database Setup

### Step 1: Create Database & User in hPanel
1. Navigate to **hPanel** → **Databases** → **Management**.
2. Fill in:
   * **MySQL Database Name:** e.g., `u123456789_jacario`
   * **MySQL Username:** e.g., `u123456789_jacario_user`
   * **Password:** Generate a strong, random password.
3. Click **Create**. Note down the exact database name, username, and password.

### Step 2: Initialize Database Data
You have two options to initialize the production database:

#### Option A: Import `jacario.sql` via phpMyAdmin (Recommended for existing seed data)
1. In hPanel → **Databases**, click **Enter phpMyAdmin** next to your database.
2. Click the **Import** tab.
3. Upload `jacario.sql` from the repository root and click **Go**.

#### Option B: Run Migrations via Artisan (SSH)
```bash
php artisan migrate --force --seed
```

> **Database Backup Precaution:**
> Always export a fresh database backup from phpMyAdmin before running any future migrations:
> `phpMyAdmin` → Select database → **Export** → **Quick** → **Go**.

---

## 5. Production Environment Configuration (`.env`)

### Step 1: Create the Production `.env`
On Hostinger (via SSH or File Manager):
```bash
cp .env.example .env
```

### Step 2: Configure Production Values
Edit `.env` and set the following production values:

```ini
APP_NAME="JACARIO"
APP_ENV=production
APP_KEY=base64:qRxNQvd1HtuTf0+s9s4eCvVcC4gTUIWzEssrhdA8YWg=
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Hostinger MySQL Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_jacario
DB_USERNAME=u123456789_jacario_user
DB_PASSWORD="your_hostinger_db_password"

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

# Hostinger Mail / Titan Mail SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=concierge@yourdomain.com
MAIL_PASSWORD="your_email_password"
MAIL_FROM_ADDRESS="concierge@yourdomain.com"
MAIL_FROM_NAME="JACARIO"

# Razorpay (Live Credentials)
RAZORPAY_KEY_ID="rzp_live_xxxxxxxxxxxxxx"
RAZORPAY_KEY_SECRET="your_razorpay_secret"
RAZORPAY_WEBHOOK_SECRET="your_webhook_secret"

# Google OAuth 2.0 (Use explicit full URL, no variable interpolation)
GOOGLE_CLIENT_ID="your_google_client_id.apps.googleusercontent.com"
GOOGLE_CLIENT_SECRET="your_google_client_secret"
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

> [!IMPORTANT]
> **Preserve Existing APP_KEY:**
> Do NOT generate a new `APP_KEY` if you have existing encrypted sessions, cookies, or user passwords. Keep the existing key (`base64:qRxNQvd1HtuTf0+s9s4eCvVcC4gTUIWzEssrhdA8YWg=`).

> [!CAUTION]
> **Avoid Variable Interpolation:**
> Avoid using `${APP_URL}` in `.env` entries like `GOOGLE_REDIRECT_URI` or `MAIL_FROM_NAME`. Explicitly write `https://yourdomain.com/auth/google/callback`.

---

## 6. Pre-Flight Verification Command

Jacario includes a built-in deployment audit command to verify production readiness without leaking credentials.

Run this command on Hostinger via SSH:
```bash
php artisan deploy:check
```

### What It Audits:
1. PHP version (`>= 8.3`)
2. Loaded PHP extensions (`pdo`, `pdo_mysql`, `gd`, `openssl`, etc.)
3. Presence of `.env` and validity of `APP_KEY`
4. Ensures `APP_DEBUG=false` in production
5. Database connectivity test
6. Writable permissions for `storage/` and `bootstrap/cache/`
7. Storage symlink existence (`public/storage`)
8. Vite build manifest (`public/build/manifest.json`) and asset chunks
9. Security checks confirming `.env` is NOT exposed inside `public/`

---

## 7. Production Caching & Performance Optimization

After `php artisan deploy:check` passes, cache the Laravel configuration, routes, and views for optimal performance:

```bash
# Cache configuration, routes, and compiled views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# If you ever modify .env or routes later, clear and rebuild cache:
# php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## 8. SSL & Domain Configuration

1. In Hostinger **hPanel** → **Security** → **SSL**.
2. Click **Install SSL** on your domain (Free Lifetime Let's Encrypt SSL).
3. Toggle on **Force HTTPS**.
4. Test your website in the browser: `https://yourdomain.com`.

---

## 9. Common Errors & Troubleshooting

### 1. Hostinger Build Fails: "Directory dist does not exist"
* **Cause:** Hostinger was configured as a static Vite SPA deployment expecting `dist/`.
* **Fix:** Deploy as a PHP/Laravel Web Application. Ensure Vite outputs to `public/build` (the Laravel standard). Point the Hostinger output directory or document root to `public`.

### 2. "Vite manifest not found at: .../public/build/manifest.json"
* **Cause:** The assets were not compiled on the server or were excluded by `.gitignore`.
* **Fix:** Run `npm ci && npm run build` via SSH, or build assets locally and upload `public/build/` to the server.

### 3. HTTP 500 — Internal Server Error
* **Step 1:** Check Laravel logs: `tail -n 50 ~/jacario/storage/logs/laravel.log`.
* **Step 2:** Ensure permissions are writable: `chmod -R 775 storage bootstrap/cache`.
* **Step 3:** Ensure `.env` exists and contains a valid `APP_KEY`.

### 4. Database Connection Refused / Access Denied
* **Cause:** Incorrect DB credentials or host.
* **Fix:** In Hostinger, use `DB_HOST=127.0.0.1` or `localhost`. Confirm the database user is assigned to the database in **hPanel** → **Databases** with ALL PRIVILEGES.

### 5. Google OAuth 400 redirect_uri_mismatch
* **Fix:** Ensure the exact URL in `GOOGLE_REDIRECT_URI` in `.env` (e.g. `https://yourdomain.com/auth/google/callback`) is added to **Authorized redirect URIs** in Google Cloud Console.

---

## 10. Rollback Procedure

If a deployment encounters critical issues, roll back using Git:

```bash
# 1. Check previous commit hash
git log -n 5 --oneline

# 2. Checkout previous stable commit
git checkout [PREVIOUS_COMMIT_HASH]

# 3. Re-install dependencies and re-build
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 4. Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. If database changes occurred, restore your backup via phpMyAdmin
```
