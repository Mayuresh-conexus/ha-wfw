<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class CustomLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            // S-01: 5 attempts per 10 minutes (600 seconds)
            $this->rateLimit(5, 600);
        }
        catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }

        return parent::authenticate();
    }

    protected function getRememberFormComponent(): \Filament\Forms\Components\Component
    {
        return parent::getRememberFormComponent()
            ->hintIcon(
                'heroicon-m-information-circle',
                tooltip: 'Keep your session active on this browser for 30 days. Use only on private devices.',
            );
    }
}
