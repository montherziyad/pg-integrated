<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ClientAuthController extends Controller
{
    public function create(): View
    {
        return view('client-portal.login');
    }


    public function register(): View
    {
        return view('client-portal.register');
    }

    public function storeRegistration(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'company_profile' => ['nullable', 'string', 'max:2000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $client = Client::create([
            'client_code' => 'WEB-'.now()->format('YmdHis'),
            'name' => $data['company_name'],
            'company_name' => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'industry' => $data['industry'] ?? null,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
            'website' => $data['website'] ?? null,
            'company_profile' => $data['company_profile'] ?? null,
            'password' => $data['password'],
            'is_active' => false,
            'portal_enabled' => false,
        ]);

        $client->sendEmailVerificationNotification();

        return redirect()->route('client.register.pending');
    }

    public function pending(): View
    {
        return view('client-portal.register-pending');
    }


    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        $client = Client::findOrFail($id);

        abort_unless(hash_equals((string) $hash, sha1($client->getEmailForVerification())), 403);
        abort_unless(URL::hasValidSignature($request), 403);

        if (! $client->hasVerifiedEmail()) {
            $client->markEmailAsVerified();
            event(new Verified($client));
        }

        return redirect()->route('client.login')->with('status', 'Email verified. Your registration is now waiting for PG Integrated approval.');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $client = Client::where('email', $data['email'])->first();

        if ($client && ! $client->hasVerifiedEmail()) {
            $client->sendEmailVerificationNotification();
        }

        return back()->with('status', 'If the email exists and is not verified, a verification link has been sent.');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($credentials['email']).'|'.$request->ip();
        $clientRecord = Client::where('email', $credentials['email'])->first();

        if ($clientRecord && ! $clientRecord->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => 'Please verify your email address before opening the client portal.',
            ]);
        }

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again shortly.',
            ]);
        }

        $authenticated = Auth::guard('client')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'is_active' => true,
            'portal_enabled' => true,
        ], $request->boolean('remember'));

        if (! $authenticated) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match an active client portal account.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();
        $request->user('client')->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(route('client.portal'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}
