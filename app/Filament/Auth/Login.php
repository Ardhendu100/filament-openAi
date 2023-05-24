<?php

namespace App\Filament\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Http\Livewire\Auth\Login as BasePage;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class Login extends BasePage
{
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'username' => __('filament::login.messages.throttled', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]),
            ]);
        }
        $data = $this->form->getState();
        if (!Filament::auth()->attempt([
            'username' => $data['username'],
            'password' => $data['password'],
        ])) {
            throw ValidationException::withMessages([
                'username' => __('filament::login.messages.failed'),
            ]);
        }
        session()->regenerate();
        return app(LoginResponse::class);
    }
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('username')
                ->label(__('redqueen-ivf-system::redqueen-ivf-system.login.fields.username'))
                ->required()
                ->autocomplete(),
            TextInput::make('password')
                ->label(__('redqueen-ivf-system::redqueen-ivf-system.login.fields.password'))
                ->password()
                ->required(),
        ];
    }
}
