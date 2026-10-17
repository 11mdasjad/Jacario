<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Resolve the Google OAuth redirect URL accurately for both local and production environments.
     */
    protected function getRedirectUrl(Request $request): string
    {
        $configured = config('services.google.redirect');
        if (!empty($configured) && filter_var($configured, FILTER_VALIDATE_URL)) {
            return $configured;
        }

        // Force HTTPS in production or behind SSL reverse proxies
        $isSecure = $request->isSecure() || app()->isProduction() || $request->header('X-Forwarded-Proto') === 'https';
        $scheme = $isSecure ? 'https' : $request->getScheme();
        $host = $request->getHttpHost();

        return $scheme . '://' . $host . '/auth/google/callback';
    }

    /**
     * Ensure client ID is trimmed and auto-correct common truncation typos.
     */
    protected function sanitizeGoogleConfig(): void
    {
        $clientId = trim((string) config('services.google.client_id'));
        if (str_starts_with($clientId, '046473949504-')) {
            $clientId = '1' . $clientId;
        }
        config(['services.google.client_id' => $clientId]);
    }

    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirectToGoogle(Request $request)
    {
        $this->sanitizeGoogleConfig();

        if ($request->filled('redirect')) {
            session(['oauth_redirect' => $request->input('redirect')]);
        }

        return Socialite::driver('google')
            ->redirectUrl($this->getRedirectUrl($request))
            ->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(Request $request)
    {
        $this->sanitizeGoogleConfig();

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl($this->getRedirectUrl($request))
                ->user();
        } catch (\Throwable $e) {
            Log::error('Google OAuth failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google authentication could not be completed. Please try again.');
        }

        if (!$googleUser || !$googleUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Unable to retrieve your email from Google. Please try again.');
        }

        // Look up by google_id first, then by email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id, avatar, and verify email
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        } else {
            // Register new customer account
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Jacario Client',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => 'customer',
                'is_active' => true,
                'password' => Hash::make(Str::random(32)),
                'email_verified_at' => now(),
            ]);
        }

        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact concierge@jacario.com.');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        // Merge and link guest cart to this authenticated user
        try {
            $this->cartService->getCart();
        } catch (\Throwable $e) {
            Log::warning('Cart linking on Google login failed: ' . $e->getMessage());
        }

        if ($user->isStaff()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        $redirectUrl = session()->pull('oauth_redirect', route('account.dashboard'));

        return redirect()->intended($redirectUrl);
    }
}
