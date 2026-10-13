<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CheckDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate production deployment readiness for Hostinger without exposing secrets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('  Jacario Production Deployment Readiness Audit    ');
        $this->info('====================================================');

        $hasErrors = false;
        $hasWarnings = false;

        $results = [];

        // 1. PHP Version
        $phpVersion = PHP_VERSION;
        if (version_compare(PHP_VERSION, '8.3.0', '>=')) {
            $results[] = ['PHP Version', 'PASS', "PHP $phpVersion (meets requirement >= 8.3)"];
        } else {
            $results[] = ['PHP Version', 'FAIL', "PHP $phpVersion detected. Jacario requires PHP >= 8.3"];
            $hasErrors = true;
        }

        // 2. Required PHP Extensions
        $requiredExtensions = [
            'pdo',
            'openssl',
            'mbstring',
            'tokenizer',
            'xml',
            'ctype',
            'json',
            'bcmath',
            'fileinfo',
            'curl',
            'gd',
        ];

        $missingExtensions = [];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }

        if (empty($missingExtensions)) {
            $results[] = ['PHP Extensions', 'PASS', 'All core extensions loaded (pdo, openssl, mbstring, gd, etc.)'];
        } else {
            $results[] = ['PHP Extensions', 'FAIL', 'Missing extensions: ' . implode(', ', $missingExtensions)];
            $hasErrors = true;
        }

        // 3. Environment & Secrets Check
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $results[] = ['.env File', 'PASS', '.env file exists at project root'];
        } else {
            $results[] = ['.env File', 'FAIL', '.env file is missing. Copy .env.example to .env and configure.'];
            $hasErrors = true;
        }

        // APP_KEY validation (Never print key!)
        $appKey = config('app.key');
        if (!empty($appKey) && (str_starts_with($appKey, 'base64:') || strlen($appKey) === 32)) {
            $results[] = ['APP_KEY', 'PASS', 'Application encryption key is properly configured'];
        } else {
            $results[] = ['APP_KEY', 'FAIL', 'APP_KEY is missing or invalid. Run: php artisan key:generate'];
            $hasErrors = true;
        }

        // APP_ENV & APP_DEBUG validation
        $appEnv = config('app.env');
        $appDebug = config('app.debug');

        if ($appEnv === 'production') {
            if ($appDebug) {
                $results[] = ['APP_DEBUG', 'FAIL', 'APP_DEBUG is true in production! Set APP_DEBUG=false in .env immediately.'];
                $hasErrors = true;
            } else {
                $results[] = ['APP_DEBUG', 'PASS', 'APP_DEBUG is safely set to false for production'];
            }
        } else {
            $results[] = ['APP_ENV', 'WARN', "Current APP_ENV is '$appEnv' (not 'production'). Set APP_ENV=production on Hostinger."];
            $hasWarnings = true;
        }

        // APP_URL validation
        $appUrl = config('app.url');
        if (empty($appUrl) || str_contains($appUrl, 'localhost') || str_contains($appUrl, '127.0.0.1')) {
            if ($appEnv === 'production') {
                $results[] = ['APP_URL', 'WARN', "APP_URL is set to '$appUrl'. Update to your live Hostinger domain (https://...)."];
                $hasWarnings = true;
            } else {
                $results[] = ['APP_URL', 'PASS', "APP_URL is configured ($appUrl)"];
            }
        } else {
            $results[] = ['APP_URL', 'PASS', "APP_URL is configured with domain"];
        }

        // 4. Database Configuration & Connection
        $dbConnection = config('database.default');
        $results[] = ['DB Driver', 'INFO', "Default connection: $dbConnection"];

        if ($dbConnection === 'mysql') {
            $dbHost = config('database.connections.mysql.host');
            $dbDatabase = config('database.connections.mysql.database');
            $dbUsername = config('database.connections.mysql.username');

            if (empty($dbHost) || empty($dbDatabase) || empty($dbUsername)) {
                $results[] = ['DB Config', 'FAIL', 'MySQL credentials incomplete (DB_HOST, DB_DATABASE, or DB_USERNAME missing).'];
                $hasErrors = true;
            } else {
                $results[] = ['DB Config', 'PASS', 'MySQL parameters configured'];
            }
        }

        try {
            DB::connection()->getPdo();
            $results[] = ['DB Connection', 'PASS', 'Database connection successfully established'];
        } catch (\Throwable $e) {
            $results[] = ['DB Connection', 'FAIL', 'Cannot connect to database: ' . $e->getMessage()];
            $hasErrors = true;
        }

        // 5. Storage & Bootstrap Permissions
        $writablePaths = [
            'storage/app' => storage_path('app'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $unwritable = [];
        foreach ($writablePaths as $label => $path) {
            if (!File::exists($path)) {
                File::makeDirectory($path, 0775, true, true);
            }
            if (!is_writable($path)) {
                $unwritable[] = $label;
            }
        }

        if (empty($unwritable)) {
            $results[] = ['Writable Paths', 'PASS', 'Storage and bootstrap/cache directories are writable'];
        } else {
            $results[] = ['Writable Paths', 'FAIL', 'Paths not writable: ' . implode(', ', $unwritable) . '. Run: chmod -R 775 storage bootstrap/cache'];
            $hasErrors = true;
        }

        // Storage Symlink
        $storageLink = public_path('storage');
        if (File::exists($storageLink) || is_link($storageLink)) {
            $results[] = ['Storage Link', 'PASS', 'public/storage link exists'];
        } else {
            $results[] = ['Storage Link', 'WARN', 'public/storage link missing. Run: php artisan storage:link'];
            $hasWarnings = true;
        }

        // 6. Vite Production Build Assets
        $manifestPath = public_path('build/manifest.json');
        if (File::exists($manifestPath)) {
            $manifest = json_decode(File::get($manifestPath), true);
            $hasCss = isset($manifest['resources/css/app.css']);
            $hasJs = isset($manifest['resources/js/app.js']);

            if ($hasCss && $hasJs) {
                $results[] = ['Vite Build Assets', 'PASS', 'public/build/manifest.json verified with CSS and JS bundles'];
            } else {
                $results[] = ['Vite Build Assets', 'WARN', 'manifest.json found but missing app.css or app.js entries. Re-run npm run build'];
                $hasWarnings = true;
            }
        } else {
            $results[] = ['Vite Build Assets', 'FAIL', 'public/build/manifest.json missing. Run: npm run build'];
            $hasErrors = true;
        }

        // 7. Web Server Security
        $publicHtaccess = public_path('.htaccess');
        if (File::exists($publicHtaccess)) {
            $results[] = ['public/.htaccess', 'PASS', 'public/.htaccess exists for URL rewriting'];
        } else {
            $results[] = ['public/.htaccess', 'WARN', 'public/.htaccess missing. Nginx or custom Apache setup required.'];
            $hasWarnings = true;
        }

        $rootEnvInPublic = public_path('.env');
        if (File::exists($rootEnvInPublic)) {
            $results[] = ['Public .env Leak', 'FAIL', 'CRITICAL: .env file detected inside public/ directory! Remove it immediately!'];
            $hasErrors = true;
        } else {
            $results[] = ['Public .env Leak', 'PASS', 'No .env file found in public/ directory'];
        }

        // 8. Integrations Status Check (Presence only, never print secrets)
        $razorpayKey = config('services.razorpay.key_id') ?: env('RAZORPAY_KEY_ID');
        if (!empty($razorpayKey) && !str_starts_with($razorpayKey, 'rzp_test_placeholder')) {
            $results[] = ['Razorpay Gateway', 'PASS', 'Razorpay API credentials detected'];
        } else {
            $results[] = ['Razorpay Gateway', 'INFO', 'Razorpay in mock/test mode (configure RAZORPAY_KEY_ID / SECRET for live payments)'];
        }

        $googleClientId = config('services.google.client_id');
        if (!empty($googleClientId)) {
            $results[] = ['Google OAuth', 'PASS', 'Google OAuth client configured'];
        } else {
            $results[] = ['Google OAuth', 'INFO', 'Google OAuth not configured (optional: set GOOGLE_CLIENT_ID / SECRET)'];
        }

        // Render Table
        $this->table(['Component', 'Status', 'Details'], $results);

        $this->newLine();
        if ($hasErrors) {
            $this->error('✘ Production audit FAILED. Please resolve the issues marked FAIL before deploying.');
            return Command::FAILURE;
        }

        if ($hasWarnings) {
            $this->warn('⚠ Production audit passed with WARNINGS. Review the warnings above before going live.');
            return Command::SUCCESS;
        }

        $this->info('✔ Production audit PASSED! Jacario is ready for production deployment.');
        return Command::SUCCESS;
    }
}
