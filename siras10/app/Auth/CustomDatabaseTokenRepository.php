<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository
{
    /**
     * Build the record payload for the table.
     *
     * @param  string  $email
     * @param  string  $token
     * @return array
     */
    protected function getPayload($email, $token)
    {
        return ['email' => $email, 'token' => $this->hasher->make($token), 'created_at' => new \DateTime];
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  string  $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->hasher->check($token, $record['token']);
    }

    /**
     * Create a new token record.
     *
     * @return string
     */
    public function create(CanResetPasswordContract $user)
    {
        $email = $user->getEmailForPasswordReset();

        $this->deleteExisting($user);

        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($email, $token));

        return $token;
    }

    /**
     * Delete existing reset tokens from the database.
     *
     * @return int
     */
    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()->where('email', $user->getEmailForPasswordReset())->delete();
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     * @return bool
     */
    public function recentlyCreatedToken(CanResetPasswordContract $user)
    {
        $record = (array) $this->getTable()->where(
            'email', $user->getEmailForPasswordReset()
        )->first();

        return $record && $this->tokenRecentlyCreated($record['created_at']);
    }

    /**
     * Delete a token record by user.
     *
     * @return void
     */
    public function delete(CanResetPasswordContract $user)
    {
        $this->deleteExisting($user);
    }
}
