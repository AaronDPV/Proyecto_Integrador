<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends SimplePage
{
    protected string $view = 'filament.pages.auth.custom-login';

    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount(): void
    {
        if (filament()->auth()->check()) {
            redirect()->intended(filament()->getUrl());
        }
    }

    public function authenticate(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! filament()->auth()->attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', __('filament-panels::pages/auth/login.messages.failed'));
            return;
        }

        session()->regenerate();

        redirect()->intended(filament()->getUrl());
    }

    public function getTitle(): string | Htmlable
    {
        return '';
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }
}