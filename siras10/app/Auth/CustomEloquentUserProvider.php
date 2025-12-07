<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

class CustomEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Convertir 'email' a 'correo' para que funcione con la base de datos
        if (isset($credentials['email'])) {
            $credentials['correo'] = $credentials['email'];
            unset($credentials['email']);
        }

        // Llamar al método padre con las credenciales modificadas
        return parent::retrieveByCredentials($credentials);
    }
}
