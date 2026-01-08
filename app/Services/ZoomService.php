<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService
{
    public function getAccessToken(): string
    {
        $response = Http::asForm()
            ->withBasicAuth(config('services.zoom.client_id'), config('services.zoom.client_secret'))
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
