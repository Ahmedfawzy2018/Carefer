<?php

namespace App\Services;
use App\Actions\ReservationAction;
use App\Http\Requests\RegisterUserRequest;
use App\Traits\ApiResponseTrait;

class AuthenticationService
{
    use ApiResponseTrait;

    public function login($credentials)
    {
        try {
            if (!$token = auth('users')->attempt($credentials)) {
                return $this->respondUnAuthorized('Your Username/Password is Invalid!');
            }

            return $this->respond(['token' => $token]);

        } catch (\Exception $e) {
            $this->respondBadRequest($e->getMessage());
        }
    }
}
