<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Hash;

class HashedRememberTokenUserProvider extends EloquentUserProvider
{
    public function retrieveByToken($identifier, #[\SensitiveParameter] $token)
    {
        $model = $this->createModel();

        $retrievedModel = $this->newModelQuery($model)
            ->where($model->getAuthIdentifierName(), $identifier)
            ->first();

        if (! $retrievedModel) {
            return;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        if (! is_string($rememberToken) || $rememberToken === '') {
            return;
        }

        return Hash::check($token, $rememberToken) ? $retrievedModel : null;
    }

    public function updateRememberToken(UserContract $user, #[\SensitiveParameter] $token): void
    {
        $user->setRememberToken(Hash::make($token));

        $timestamps = $user->timestamps;

        $user->timestamps = false;
        $user->save();
        $user->timestamps = $timestamps;
    }
}
