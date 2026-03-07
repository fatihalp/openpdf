<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function session(Request $request): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'auth' => $this->authPayload($request->user()),
        ]);
    }

    public function google(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'credential' => ['required', 'string'],
        ]);

        $clientId = (string) config('services.google.client_id');
        if ($clientId === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Google login is not enabled on the server.',
            ], 503);
        }

        $response = Http::timeout(12)->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $validated['credential'],
        ]);

        if (! $response->ok()) {
            return response()->json([
                'ok' => false,
                'message' => 'Google token could not be verified.',
            ], 401);
        }

        $payload = $response->json();

        if (($payload['aud'] ?? null) !== $clientId) {
            return response()->json([
                'ok' => false,
                'message' => 'Google client ID mismatch.',
            ], 401);
        }

        $sub = (string) ($payload['sub'] ?? '');
        $email = (string) ($payload['email'] ?? '');
        $name = (string) ($payload['name'] ?? $email);
        $normalizedEmail = mb_strtolower($email);

        if ($sub === '' || $email === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Missing Google user information.',
            ], 401);
        }

        $user = User::query()
            ->where('google_id', $sub)
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = new User;
            $user->password = Hash::make(bin2hex(random_bytes(16)));
        }

        $user->name = $name;
        $user->email = $email;
        $user->google_id = $sub;
        $user->avatar_url = $payload['picture'] ?? null;
        $user->email_verified_at = now();
        $user->is_admin = $user->is_admin || in_array($normalizedEmail, config('openpdf.admin_emails', []), true);
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'ok' => true,
            'auth' => $this->authPayload($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'ok' => true,
            'auth' => $this->authPayload(null),
        ]);
    }

    private function authPayload(?User $user): array
    {
        if (! $user) {
            return [
                'logged_in' => false,
                'provider' => 'visitor',
                'name' => null,
                'email' => null,
            ];
        }

        return [
            'logged_in' => true,
            'provider' => $user->google_id ? 'google' : 'local',
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
