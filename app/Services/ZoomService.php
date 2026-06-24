<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService
{
    /**
     * Whether the Zoom Server-to-Server OAuth credentials are present.
     * Used to skip meeting creation gracefully when Zoom isn't configured.
     */
    public function isConfigured(): bool
    {
        return filled(config('services.zoom.client_id'))
            && filled(config('services.zoom.client_secret'))
            && filled(config('services.zoom.account_id'));
    }

    public function getAccessToken(): string
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Zoom API credentials are not configured (ZOOM_ACCOUNT_ID / ZOOM_CLIENT_ID / ZOOM_CLIENT_SECRET).');
        }

        $response = Http::asForm()
            ->withBasicAuth(
                (string) config('services.zoom.client_id'),
                (string) config('services.zoom.client_secret'),
            )
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'account_credentials',
                'account_id' => config('services.zoom.account_id'),
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Zoom token error: ' . $response->body());
        }

        return $response->json('access_token');
    }

    public function createMeeting(array $payload): array
    {
        $token = $this->getAccessToken();
        $userId = config('services.zoom.user_id', 'me');

        $response = Http::withToken($token)
            ->post("https://api.zoom.us/v2/users/{$userId}/meetings", $payload);

        if (! $response->successful()) {
            throw new \RuntimeException('Zoom create meeting error: ' . $response->body());
        }

        return $response->json();
    }
}
