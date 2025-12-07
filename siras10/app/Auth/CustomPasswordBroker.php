<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CustomPasswordBroker extends PasswordBroker
{
    /**
     * Send a password reset link to a user.
     *
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // Convertir 'email' a 'correo' para buscar en la BD
        if (isset($credentials['email'])) {
            $credentials['correo'] = $credentials['email'];
            unset($credentials['email']);
        }

        // Get the user using 'correo' field
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        if ($this->tokens->recentlyCreatedToken($user)) {
            return static::RESET_THROTTLED;
        }

        $token = $this->tokens->create($user);

        // Enviar el email
        $user->sendPasswordResetNotification($token);

        return static::RESET_LINK_SENT;
    }

    /**
     * Get the user for the given credentials.
     *
     * @return \Illuminate\Contracts\Auth\CanResetPassword|null
     */
    public function getUser(array $credentials)
    {
        $credentials = array_filter($credentials, function ($key) {
            return ! str_starts_with($key, '_');
        }, ARRAY_FILTER_USE_KEY);

        // Usar 'correo' en lugar de 'email'
        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new \UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }
}
