<?php
namespace App\Providers;

use App\Models\Token;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class VbulletinUserProvider extends EloquentUserProvider {
    /**
     * Create a new database user provider.
     *
     * @param  string $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed $identifier
     * @param  string $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        $user = $model->newQuery()->where($model->getAuthIdentifierName(), $identifier)->first();

        if (!$user) {
            return $user;
        }

        $token = $user->token()->where('remember_token', $token)->first();

        if (!$token) {
            return $token;
        }

        return $token->user()->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  string $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        Token::updateOrCreate(['user_id' => $user->userid], ['remember_token' => $token]);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];

        if (!$user->isStaff()) {
            return false;
        }

        return md5(md5($plain) . $user->salt) === $user->getAuthPassword();
    }
}
